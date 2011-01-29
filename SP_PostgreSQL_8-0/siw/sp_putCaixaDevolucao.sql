create or replace FUNCTION sp_putCaixaDevolucao
   (p_chave                numeric,
    p_pessoa               numeric,
    p_observacao           varchar
   ) RETURNS VOID AS $$
DECLARE
   
   w_chave         numeric(18) := null;
   w_chave_dem     numeric(18) := null;
   w_tramite       numeric(18);
   w_sg_tramite    varchar(2);
   
    c_dados CURSOR FOR
      --  para recuperar os protocolos contidos na caixa
      select a.sq_siw_solicitacao as chave, a.unidade_int_posse, b.sq_siw_tramite, c.sq_menu, d.sq_unidade, e.despacho_devolucao
        from pa_documento                   a
             inner     join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner   join siw_menu        c on (b.sq_menu            = c.sq_menu)
                 inner join pa_parametro    e on (c.sq_pessoa          = e.cliente)
             inner     join pa_caixa        d on (a.sq_caixa           = d.sq_caixa)
       where a.sq_caixa = p_chave;
BEGIN
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
        select nextVal('sq_siw_solic_log') into w_chave;
        
        -- Se houve mudança de fase, grava o log
        Insert Into siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
             sq_siw_tramite,            data,               devolucao, 
             observacao
            )
        (Select 
             w_chave,                   crec.chave,         p_pessoa,
             crec.sq_siw_tramite,       now(),            'N',
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
     select nextVal('sq_documento_log') into w_chave_dem;
         
     -- Insere registro na tabela de encaminhamentos do documento
     insert into pa_documento_log
       (sq_documento_log,        sq_siw_solicitacao,         sq_siw_solic_log,          sq_tipo_despacho,           interno, 
        unidade_origem,          unidade_destino,            pessoa_destino,            cadastrador,                data_inclusao,
        resumo,                  envio,                      emite_aviso,               dias_aviso,                 retorno_limite,
        retorno_unidade,         pessoa_externa,             unidade_externa,           quebra_sequencia,           nu_guia,
        ano_guia,                recebedor,                  recebimento)
     values
       (w_chave_dem,             crec.chave,                 w_chave,                   crec.despacho_devolucao,    'S', 
        crec.unidade_int_posse,  crec.sq_unidade,            null,                      p_pessoa,                   now(),
        p_observacao,            now(),                    'N',                       0,                          null,
        null,                    null,                       null,                      'N',                        null,
        null,                    p_pessoa,                   now());
  end loop;
  
  update pa_caixa a
     set arquivo_data        = null,
         arquivo_guia_numero = null,
         arquivo_guia_ano    = null,
         sq_arquivo_local    = null
  where sq_caixa = p_chave;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;