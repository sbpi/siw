create or replace procedure sp_putDocumentoDescarte
   (p_chave               in  number,
    p_usuario             in  number,
    p_observacao          in  varchar2
   ) is
   
   w_tram_solic siw_tramite%rowtype;
   w_tramite    siw_tramite%rowtype;
   w_data       date := sysdate;
begin
   -- Recupera os dados do trâmite de descarte, que é igual ao de cancelamento
   select b.* into w_tramite 
     from siw_solicitacao        a
          inner join siw_tramite b on (a.sq_menu = b.sq_menu)
     where b.sigla              = 'CA'
       and a.sq_siw_solicitacao = p_chave;
      
   -- Recupera o trâmite atual da solicitacao
   select b.* into w_tram_solic
     from siw_solicitacao        a
          inner join siw_tramite b on (a.sq_siw_tramite = b.sq_siw_tramite)
     where a.sq_siw_solicitacao = p_chave;
      
   -- Atualiza a tabela de solicitações
   Update siw_solicitacao set sq_siw_tramite = w_tramite.sq_siw_tramite Where sq_siw_solicitacao = p_chave or sq_solic_pai = p_chave;

   -- Atualiza a tabela de documentos
   Insert Into siw_solic_log 
        (sq_siw_solic_log,         sq_siw_solicitacao,   sq_pessoa, sq_siw_tramite,   data,   devolucao, observacao
        )
   (Select 
         sq_siw_solic_log.nextval, a.sq_siw_solicitacao, p_usuario, a.sq_siw_tramite, w_data, 'N',       p_observacao
        from siw_solicitacao a
       where a.sq_siw_solicitacao = p_chave
          or a.sq_solic_pai       = p_chave
   );
end sp_putDocumentoDescarte;
/
