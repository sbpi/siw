create or replace FUNCTION sp_putDocumentoArqSet
   (p_chave                numeric,
    p_usuario              numeric,
    p_observacao           varchar
   ) RETURNS VOID AS $$
DECLARE
   
   w_tramite siw_tramite%rowtype;
   w_data    date := now();
BEGIN
   -- Recupera os dados do trâmite de arquivamento setorial
   select b.* into w_tramite 
     from siw_solicitacao        a
          inner join siw_tramite b on (b.sq_menu = b.sq_menu)
     where b.sigla              = 'AS'
       and a.sq_siw_solicitacao = p_chave;
      
   -- Atualiza a tabela de solicitações
   Update siw_solicitacao set sq_siw_tramite = w_tramite.sq_siw_tramite Where sq_siw_solicitacao = p_chave or sq_solic_pai = p_chave;

   -- Atualiza a tabela de documentos
   update pa_documento set
       observacao_setorial = p_observacao,
       data_setorial       = w_data
    where sq_siw_solicitacao in (select sq_siw_solicitacao from siw_solicitacao where sq_siw_solicitacao = p_chave or sq_solic_pai = p_chave);

    -- Registra os dados da autuação
    Insert Into siw_solic_log 
        (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
         sq_siw_tramite,            data,               devolucao, 
         observacao
        )
    (Select 
         nextVal('sq_siw_solic_log'),  p_chave,            p_usuario,
         a.sq_siw_tramite,          w_data,             'N',
         'Arquivamento setorial: '||p_observacao
        from siw_solicitacao a
       where a.sq_siw_solicitacao = p_chave
          or a.sq_solic_pai       = p_chave
    );END; $$ LANGUAGE 'PLPGSQL' VOLATILE;