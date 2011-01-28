create or replace FUNCTION SP_PutTrigger
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_tabela                 numeric,
    p_sq_usuario                numeric,
    p_sq_sistema                numeric,
    p_nome                      varchar,
    p_descricao                 varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_trigger
        (sq_trigger, sq_tabela, sq_usuario, sq_sistema, nome, descricao)
      (Select sq_trigger.nextval, p_sq_tabela, p_sq_usuario, p_sq_sistema, p_nome, p_descricao from dual);

   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_trigger
         set 
             sq_tabela     = p_sq_tabela,
             sq_usuario    = p_sq_usuario,
             sq_sistema    = p_sq_sistema,
             nome          = p_nome,
             descricao     = p_descricao
       where sq_trigger    = p_chave;
   
   Elsif p_operacao = 'E' Then
      -- Exclui os eventos ligados à trigger
      DELETE FROM dc_trigger_evento where sq_trigger = p_chave;
      
      -- Exclui registro
      DELETE FROM dc_trigger where sq_trigger = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;