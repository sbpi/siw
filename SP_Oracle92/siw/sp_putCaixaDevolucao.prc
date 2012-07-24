create or replace procedure sp_putCaixaDevolucao
   (p_chave               in  number,
    p_pessoa              in  number,
    p_observacao          in  varchar2
   ) is
   
   w_chave         number(18) := null;
   w_chave_dem     number(18) := null;
   
   cursor c_dados is
      -- cursor para recuperar os protocolos contidos na caixa
      select a.sq_siw_solicitacao as chave, a.unidade_int_posse, b.sq_siw_tramite, c.sq_menu, c.sigla sg_at, c.nome nm_at,
             d.sq_unidade, e.despacho_devolucao,g.cadastrador, 
             h.sq_siw_tramite sq_as, h.nome nm_as
        from pa_documento                    a
             inner     join siw_solicitacao  b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner   join siw_tramite      c on (b.sq_siw_tramite     = c.sq_siw_tramite)
                 inner join siw_tramite      h on (c.sq_menu            = h.sq_menu and h.sigla = 'AS')
             inner     join pa_parametro     e on (a.cliente            = e.cliente)
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
       where a.sq_caixa = p_chave;
begin
  for crec in c_dados loop
     -- Reinicializa o valor das chaves
     w_chave := null;
     
     -- Verifica se é devolução
     If crec.sg_at = 'AT' Then
        -- Recupera a próxima chave
        select sq_siw_solic_log.nextval into w_chave from dual;
        
        -- Se houve mudança de fase, grava o log
        Insert Into siw_solic_log 
                (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, sq_siw_tramite,     data,    devolucao, 
                 observacao
                )
        Values  (w_chave,                   crec.chave,         p_pessoa, crec.sq_siw_tramite, sysdate, 'N',
                 'Devolucao da fase "'||crec.nm_at||'" '||' para a fase "'||crec.nm_as||'".'
                );
    
        Update siw_solicitacao set sq_siw_tramite    = crec.sq_as      Where sq_siw_solicitacao = crec.chave;
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
