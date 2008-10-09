create or replace procedure sp_putDocumentoAutua
   (p_chave               in  number,
    p_unidade             in  number,
    p_usuario             in  number,
    p_descricao           in  varchar2
   ) is
begin
   -- Atualiza a tabela de solicitações
   Update siw_solicitacao set descricao = p_descricao, ultima_alteracao = sysdate where sq_siw_solicitacao = p_chave;
      
   -- Atualiza a tabela de documentos
   update pa_documento set
       processo              = 'S',
       unidade_autuacao      = p_unidade,
       data_autuacao         = sysdate
    where sq_siw_solicitacao = p_chave;

    -- Registra os dados da autuação
    Insert Into siw_solic_log 
        (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
         sq_siw_tramite,            data,               devolucao, 
         observacao
        )
    (Select 
         sq_siw_solic_log.nextval,  p_chave,            p_usuario,
         a.sq_siw_tramite,          sysdate,            'N',
         'Autuação de processo.'
        from siw_solicitacao a
       where a.sq_siw_solicitacao = p_chave
    );
end sp_putDocumentoAutua;
/
