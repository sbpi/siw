create or replace procedure sp_putDocumentoArqSet
   (p_chave               in  number,
    p_usuario             in  number,
    p_observacao          in  varchar2
   ) is
   
   w_tram_solic siw_tramite%rowtype;
   w_tramite    siw_tramite%rowtype;
   w_data       date := sysdate;
begin
   -- Recupera os dados do tr�mite de arquivamento setorial
   select b.* into w_tramite 
     from siw_solicitacao        a
          inner join siw_tramite b on (a.sq_menu = b.sq_menu)
     where b.sigla              = 'AS'
       and a.sq_siw_solicitacao = p_chave;
      
   -- Recupera o tr�mite atual da solicitacao
   select b.* into w_tram_solic
     from siw_solicitacao        a
          inner join siw_tramite b on (a.sq_siw_tramite = b.sq_siw_tramite)
     where a.sq_siw_solicitacao = p_chave;
      
   If w_tram_solic.ordem >= w_tramite.ordem Then
      -- Altera��o do texto de acondicionamento

      -- Atualiza a tabela de documentos
      update pa_documento set
         observacao_setorial = p_observacao
      where sq_siw_solicitacao in (select sq_siw_solicitacao from siw_solicitacao where sq_siw_solicitacao = p_chave or sq_solic_pai = p_chave);

      -- Registra os dados da altera��o
      Insert Into siw_solic_log 
           (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
            sq_siw_tramite,            data,                 devolucao, 
            observacao
           )
      (Select 
            sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_usuario,
            a.sq_siw_tramite,          w_data,               'N',
            'Arquivamento setorial: '||p_observacao
           from siw_solicitacao a
          where a.sq_siw_solicitacao = p_chave
             or a.sq_solic_pai       = p_chave
      );
   Else
      -- Arquivamento setorial inicial

      -- Atualiza a tabela de solicita��es
      Update siw_solicitacao set sq_siw_tramite = w_tramite.sq_siw_tramite Where sq_siw_solicitacao = p_chave or sq_solic_pai = p_chave;

      -- Atualiza a tabela de documentos
      update pa_documento set
         observacao_setorial = p_observacao,
         data_setorial       = w_data
      where sq_siw_solicitacao in (select sq_siw_solicitacao from siw_solicitacao where sq_siw_solicitacao = p_chave or sq_solic_pai = p_chave);

      -- Registra os dados da autua��o
      Insert Into siw_solic_log 
           (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
            sq_siw_tramite,            data,                 devolucao, 
            observacao
           )
      (Select 
            sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_usuario,
            a.sq_siw_tramite,          w_data,               'N',
            'Arquivamento setorial: '||p_observacao
           from siw_solicitacao a
          where a.sq_siw_solicitacao = p_chave
             or a.sq_solic_pai       = p_chave
      );
   End If;
end sp_putDocumentoArqSet;
/
