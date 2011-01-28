create or replace FUNCTION SP_PutPDUsuario
   (p_operacao                  varchar,
    p_cliente                   numeric,
    p_chave                     numeric
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_usuario
             (cliente,   sq_pessoa)
      (select p_cliente, p_chave from dual);
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pd_usuario where sq_pessoa = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;