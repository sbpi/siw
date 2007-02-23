create or replace procedure sp_putDocumentoAssunto
   (p_operacao            in  varchar2,
    p_chave               in number default null,
    p_assunto             in number default null,
    p_principal           in varchar2 default null
   ) is
   w_cont number(18);
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Insere apenas se o assunto já não estiver ligado ao documento
      select count(sq_assunto) into w_cont from pa_documento_assunto where sq_siw_solicitacao = p_chave and sq_assunto = p_assunto;
      
      If w_cont = 0 Then
         -- Insere assunto na tabela de assuntos do documento
         insert into pa_documento_assunto (sq_siw_solicitacao, sq_assunto, principal) values (p_chave, p_assunto, p_principal);
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de assuntos do documento
      update pa_documento_assunto set principal = p_principal where sq_siw_solicitacao = p_chave and sq_assunto = p_assunto;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de interessados da solicitação
      delete pa_documento_assunto where sq_siw_solicitacao = p_chave and sq_assunto = p_assunto;
   End If;
end sp_putDocumentoAssunto;
/
