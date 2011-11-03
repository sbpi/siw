create or replace procedure sp_putCaixaDevolucao
   (p_chave               in  number,
    p_pessoa              in  number,
    p_observacao          in  varchar2
   ) is
   
   w_chave         number(18) := null;
   w_chave_dem     number(18) := null;
   w_tramite       number(18);
   w_sg_tramite    varchar2(2);
   
   cursor c_dados is
      -- cursor para recuperar os protocolos contidos na caixa
      select a.sq_siw_solicitacao as chave, a.unidade_int_posse, b.sq_siw_tramite, c.sq_menu, d.sq_unidade, e.despacho_devolucao,
             g.cadastrador, h.nome
        from pa_documento                    a
             inner     join siw_solicitacao  b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner   join siw_menu         c on (b.sq_menu            = c.sq_menu)
                 inner join pa_parametro     e on (c.sq_pessoa          = e.cliente)
             inner     join pa_caixa         d on (a.sq_caixa           = d.sq_caixa)
             inner     join (select x.sq_siw_solicitacao, max(sq_documento_log) sq_documento_log
                               from pa_documento_log          w
                                    inner   join pa_documento x on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
                                      inner join pa_caixa     y on (x.sq_caixa           = y.sq_caixa)
                                    inner join pa_parametro   z on (w.sq_tipo_despacho   = z.despacho_arqcentral)
                              where y.sq_caixa = p_chave
                             group by x.sq_siw_solicitacao
                            )                f on (a.sq_siw_solicitacao = f.sq_siw_solicitacao)
               inner   join pa_documento_log g on (f.sq_documento_log   = g.sq_documento_log)
                 inner join co_pessoa        h on (g.cadastrador        = h.sq_pessoa)
       where a.sq_caixa = p_chave;
begin
  for crec in c_dados loop
     -- Recupera os dados do trâmite atual
     select sigla into w_sg_tramite from siw_tramite a where a.sq_siw_tramite = crec.sq_siw_tramite;
   
     -- Recupera o trâmite para o qual está sendo enviada a solicitação
     If w_sg_tramite = 'AT' Then
        select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
           from siw_tramite a
          where a.sq_menu = crec.sq_menu
            and a.sigla   = 'AS';
  
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
             'Devolucao da fase "'||a.nome||'" '||' para a fase "'||b.nome||'".'
            from siw_tramite a,
                 siw_tramite b
           where a.sq_siw_tramite = crec.sq_siw_tramite
             and b.sq_siw_tramite = w_tramite
        );
    
        Update siw_solicitacao set sq_siw_tramite = w_tramite          Where sq_siw_solicitacao = crec.chave;
        Update pa_documento    set unidade_int_posse = crec.sq_unidade Where sq_siw_solicitacao = crec.chave;
     End If;
  
     -- Recupera a nova chave da tabela de encaminhamentos da demanda
     select sq_documento_log.nextval into w_chave_dem from dual;
         
     -- Insere registro na tabela de encaminhamentos do documento
     insert into pa_documento_log
       (sq_documento_log,        sq_siw_solicitacao,         sq_siw_solic_log,          sq_tipo_despacho,           interno, 
        unidade_origem,          unidade_destino,            pessoa_destino,            cadastrador,                data_inclusao,
        resumo,                  envio,                      emite_aviso,               dias_aviso,                 retorno_limite,
        retorno_unidade,         pessoa_externa,             unidade_externa,           quebra_sequencia,           nu_guia,
        ano_guia,                recebedor,                  recebimento)
     values
       (w_chave_dem,             crec.chave,                 w_chave,                   crec.despacho_devolucao,    'S', 
        crec.unidade_int_posse,  crec.sq_unidade,            null,                      p_pessoa,                   sysdate,
        p_observacao,            sysdate,                    'N',                       0,                          null,
        null,                    null,                       null,                      'N',                        null,
        null,                    crec.cadastrador,           sysdate);
  end loop;
  
  update pa_caixa a
     set arquivo_data        = null,
         arquivo_guia_numero = null,
         arquivo_guia_ano    = null,
         sq_arquivo_local    = null
  where sq_caixa = p_chave;
end sp_putCaixaDevolucao;
/
