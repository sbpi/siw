create or replace procedure sp_putCaixaEnvio
   (p_menu                in  number,
    p_chave               in  number,
    p_pessoa              in  number,
    p_interno             in  varchar2,
    p_unidade_origem      in  number,
    p_unidade_destino     in  number   default null,
    p_tipo_despacho       in  number,
    p_despacho            in  varchar2,
    p_nu_guia             in out number,
    p_ano_guia            in out number,
    p_unidade_autuacao    in out number
   ) is
   
   w_chave         number(18)  := null;
   w_chave_dem     number(18)  := null;
   w_devolucao     varchar2(1) := 'N';
   w_parametro     pa_parametro%rowtype;
   
   cursor c_dados is
      -- cursor para recuperar os protocolos contidos na caixa
      select a.sq_siw_solicitacao as chave, a.unidade_int_posse,
             b.sq_siw_tramite, c.sigla as sg_tramite, 
             d.sq_arquivo_local, d.arquivo_data, d.arquivo_guia_numero, d.arquivo_guia_ano, d.sq_unidade,
             e.sq_siw_tramite as sq_at, e.nome as nm_at,
             f.sq_siw_tramite as sq_as, f.nome as nm_as
        from pa_documento                 a
             inner   join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner join siw_tramite     c on (b.sq_siw_tramite     = c.sq_siw_tramite)
               inner join siw_tramite     e on (b.sq_menu            = e.sq_menu and e.sigla = 'AT')
               inner join siw_tramite     f on (b.sq_menu            = f.sq_menu and f.sigla = 'AS')
             inner   join pa_caixa        d on (a.sq_caixa           = d.sq_caixa)
       where a.sq_caixa = p_chave;
begin
   
   -- Recupera os parâmetros do módulo
   select a.* into w_parametro
     from pa_parametro        a 
          inner join siw_menu b on (a.cliente = b.sq_pessoa)
    where b.sq_menu = p_menu;
   
  for crec in c_dados loop
     -- Devolução
     If crec.sg_tramite = 'AT' Then w_devolucao := 'S'; End If;
        
        
     
     -- Recupera a próxima chave
     select sq_siw_solic_log.nextval into w_chave from dual;
        
     -- Se houve mudança de fase, grava o log
     Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
     (Select 
          w_chave,                   crec.chave,         p_pessoa,
          crec.sq_siw_tramite,       sysdate,            'N',
          'Envio da fase "'||a.nome||'" '||' para a fase "'||b.nome||'".'
         from siw_tramite a,
              siw_tramite b
        where a.sq_siw_tramite = crec.sq_siw_tramite
          and b.sq_siw_tramite = case crec.sg_tramite when 'AS' then crec.sq_at else crec.sq_as end
     );
    
     Update siw_solicitacao set sq_siw_tramite = case crec.sg_tramite when 'AS' then crec.sq_at else crec.sq_as end Where sq_siw_solicitacao = crec.chave;
       
     -- Atualiza a unidade de posse
     update pa_documento a set a.unidade_int_posse = p_unidade_destino where a.sq_siw_solicitacao = crec.chave;
     
     -- Recupera a nova chave da tabela de encaminhamentos da demanda
     select sq_documento_log.nextval into w_chave_dem from dual;
         
     -- Insere registro na tabela de encaminhamentos do documento
     insert into pa_documento_log
       (sq_documento_log,        sq_siw_solicitacao,         sq_siw_solic_log,          sq_tipo_despacho,           interno, 
        unidade_origem,          unidade_destino,            pessoa_destino,            cadastrador,                data_inclusao,
        resumo,                  envio,                      emite_aviso,               dias_aviso,                 retorno_limite,
        retorno_unidade,         pessoa_externa,             unidade_externa,           quebra_sequencia,           nu_guia,
        ano_guia)
     values
       (w_chave_dem,             crec.chave,                 w_chave,                   p_tipo_despacho,            p_interno, 
        crec.unidade_int_posse,  p_unidade_destino,          null,                      p_pessoa,                   sysdate,
        p_despacho,              sysdate,                    'N',                       0,                          null,
        null,                    null,                       null,                      'N',                        p_nu_guia,
        p_ano_guia);
     
     -- Recupera o número e o ano da guia de remessa de documentos
     select a.nu_guia, a.ano_guia, coalesce(c.sq_unidade_pai, sq_unidade)
       into p_nu_guia, p_ano_guia, p_unidade_autuacao 
      from pa_documento_log          a 
           inner   join pa_documento b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
             inner join pa_unidade   c on (b.unidade_autuacao   = c.sq_unidade)
     where sq_documento_log = w_chave_dem;
  end loop;
  
  -- Atualiza os dados do arquivamento da caixa
  update pa_caixa
     set arquivo_guia_numero = p_nu_guia,
         arquivo_guia_ano    = p_ano_guia,
         sq_arquivo_local    = null
  where sq_caixa = p_chave;
end sp_putCaixaEnvio;
/
