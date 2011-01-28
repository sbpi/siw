create or replace FUNCTION SP_PutSistema
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,                   
    p_nome                      varchar,
    p_sigla                     varchar,
    p_descricao                 varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_sistema
        (sq_sistema, cliente, nome, sigla, descricao)
      (select sq_sistema.nextval, p_cliente, p_nome, p_sigla, p_descricao);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_sistema set 
         nome      = p_nome,
         sigla     = p_sigla,
         descricao = p_descricao
       where sq_sistema = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_sistema
       where sq_sistema = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;