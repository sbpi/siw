create or replace FUNCTION SP_PutProcedure
-- Giderclay Zeballos
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_arquivo                numeric,
    p_sq_sistema                numeric,
    p_sq_sp_tipo                numeric,
    p_nome                      varchar,
    p_descricao                 varchar  
    ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_procedure
        (sq_procedure, sq_arquivo, sq_sistema, sq_sp_tipo, nome, descricao)
      (select sq_procedure.nextval, p_sq_arquivo, p_sq_sistema, p_sq_sp_tipo, p_nome, p_descricao);
     
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_procedure
      set 
             sq_arquivo     = p_sq_arquivo,
             sq_sistema     = p_sq_sistema,
             sq_sp_tipo     = p_sq_sp_tipo,
             nome           = p_nome,
             descricao      = p_descricao
       where sq_procedure = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_procedure
       where sq_procedure = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;