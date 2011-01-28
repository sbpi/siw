create or replace FUNCTION SP_PutUsuario
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_sistema                numeric,
    p_nome                      varchar,
    p_descricao                 varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_usuario
        (sq_usuario, sq_sistema, nome, descricao)
      (select sq_usuarionextVal(''), p_sq_sistema, p_nome, p_descricao);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_Usuario set
         nome      = p_nome,
         descricao = p_descricao,
         sq_sistema= p_sq_sistema
       where sq_Usuario = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_Usuario where sq_usuario = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;