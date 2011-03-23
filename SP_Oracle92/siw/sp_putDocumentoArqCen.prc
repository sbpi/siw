create or replace procedure sp_putDocumentoArqCen
   (p_chave               in  number,
    p_usuario             in  number,
    p_local               in  number
   ) is
   
   w_tramite siw_tramite%rowtype;
   w_data    date := sysdate;

   cursor c_dados is
      -- cursor para recuperar os protocolos contidos na caixa
      select a.sq_siw_solicitacao as chave, b.sq_siw_tramite
        from pa_documento               a
             inner join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
       where a.sq_caixa = p_chave;
begin
  for crec in c_dados loop
     -- Recupera os dados do trâmite de arquivamento central
     select b.* into w_tramite 
       from siw_solicitacao        a
            inner join siw_tramite b on (b.sq_menu = b.sq_menu)
       where b.sigla              = 'AT'
         and a.sq_menu            = b.sq_menu
         and a.sq_siw_solicitacao = crec.chave;
        
     -- Atualiza a tabela de solicitações
     Update siw_solicitacao set sq_siw_tramite = w_tramite.sq_siw_tramite, conclusao = w_data Where sq_siw_solicitacao = crec.chave or sq_solic_pai = crec.chave;
  
     -- Atualiza a tabela de documentos
     update pa_documento 
        set data_central  = w_data 
     where sq_siw_solicitacao in (select sq_siw_solicitacao from siw_solicitacao where sq_siw_solicitacao = crec.chave or sq_solic_pai = crec.chave);
  
     -- Atualiza a tabela de caixas
     update pa_caixa set sq_arquivo_local = p_local, arquivo_data = w_data where sq_caixa = p_chave;
  
     -- Registra os dados da autuação
     Insert Into siw_solic_log 
          (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
           sq_siw_tramite,            data,                 devolucao, 
           observacao
          )
     (Select 
           sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_usuario,
           a.sq_siw_tramite,          w_data,               'N',
           'Arquivamento central.'
          from siw_solicitacao a
         where a.sq_siw_solicitacao = crec.chave
            or a.sq_solic_pai       = crec.chave
     );
  end loop;
end sp_putDocumentoArqCen;
/
