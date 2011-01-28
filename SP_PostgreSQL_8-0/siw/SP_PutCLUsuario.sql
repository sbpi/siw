create or replace FUNCTION SP_PutCLUsuario
   (p_operacao                  varchar,
    p_cliente                   numeric,
    p_chave                     numeric
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into cl_usuario
             (cliente,   sq_pessoa)
      (select p_cliente, p_chave);
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM cl_usuario where sq_pessoa = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;