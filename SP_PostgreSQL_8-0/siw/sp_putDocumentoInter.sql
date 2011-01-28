create or replace FUNCTION sp_putDocumentoInter
   (p_operacao             varchar,
    p_chave               numeric,
    p_pessoa             numeric,
    p_principal           varchar 
   ) RETURNS VOID AS $$
DECLARE
   w_cont numeric(18);
BEGIN
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
      DELETE FROM pa_documento_interessado where sq_siw_solicitacao = p_chave and sq_pessoa = p_pessoa;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;