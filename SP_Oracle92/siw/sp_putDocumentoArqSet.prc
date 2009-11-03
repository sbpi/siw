create or replace procedure sp_putDocumentoArqSet
   (p_chave               in  number,
    p_usuario             in  number,
    p_observacao          in  varchar2
   ) is
   
   w_tramite siw_tramite%rowtype;
   w_data    date := sysdate;
begin
   -- Recupera os dados do trâmite de arquivamento setorial
   select b.* into w_tramite 
     from siw_solicitacao        a
          inner join siw_tramite b on (b.sq_menu = b.sq_menu)
     where b.sigla              = 'AS'
       and a.sq_siw_solicitacao = p_chave;
      
   -- Atualiza a tabela de solicitações
   Update siw_solicitacao set sq_siw_tramite = w_tramite.sq_siw_tramite Where sq_siw_solicitacao = p_chave;

   -- Atualiza a tabela de documentos
   update pa_documento set
       observacao_setorial = p_observacao,
       data_setorial       = w_data
    where sq_siw_solicitacao = p_chave;

    -- Registra os dados da autuação
    Insert Into siw_solic_log 
        (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
         sq_siw_tramite,            data,               devolucao, 
         observacao
        )
    (Select 
         sq_siw_solic_log.nextval,  p_chave,            p_usuario,
         a.sq_siw_tramite,          w_data,             'N',
         'Arquivamento setorial: '||p_observacao
        from siw_solicitacao a
       where a.sq_siw_solicitacao = p_chave
    );
end sp_putDocumentoArqSet;
/
