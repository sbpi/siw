create or replace FUNCTION SP_PutStoredProcedure
-- Giderclay Zeballos
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_sp_tipo                numeric,
    p_sq_usuario                numeric,
    p_sq_sistema                numeric,
    p_nome                      varchar,
    p_descricao                 varchar  
    ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_stored_proc
        (sq_stored_proc, sq_sp_tipo, sq_usuario, sq_sistema, nome, descricao)
      (select sq_stored_proc.nextval, p_sq_sp_tipo, p_sq_usuario, p_sq_sistema, p_nome, p_descricao);
     
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_stored_proc
      set 
             sq_sp_tipo     = p_sq_sp_tipo,
             sq_usuario     = p_sq_usuario,
             sq_sistema     = p_sq_sistema,
             nome           = p_nome,
             descricao      = p_descricao
       where sq_stored_proc = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_stored_proc
       where sq_stored_proc = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;