create or replace procedure sp_putDocumentoInter
   (p_operacao            in  varchar2,
    p_chave               in number default null,
    p_pessoa             in number default null,
    p_principal           in varchar2 default null
   ) is
   w_cont number(18);
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Insere apenas se o pessoa já não estiver ligado ao documento
      select count(sq_pessoa) into w_cont from pa_documento_interessado where sq_siw_solicitacao = p_chave and sq_pessoa = p_pessoa;
      
      If w_cont = 0 Then
         -- Insere pessoa na tabela de pessoas do documento
         insert into pa_documento_interessado (sq_siw_solicitacao, sq_pessoa, principal) values (p_chave, p_pessoa, p_principal);
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de pessoas do documento
      update pa_documento_interessado set principal = p_principal where sq_siw_solicitacao = p_chave and sq_pessoa = p_pessoa;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de interessados da solicitação
      delete pa_documento_interessado where sq_siw_solicitacao = p_chave and sq_pessoa = p_pessoa;
   End If;
end sp_putDocumentoInter;
/
