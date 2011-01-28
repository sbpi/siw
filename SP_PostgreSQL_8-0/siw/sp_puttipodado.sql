create or replace FUNCTION SP_PutTipoDado
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_nome                      varchar,
    p_descricao                 varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_dado_tipo (sq_dado_tipo, nome, descricao)
      (select sq_dado_tipo.nextval, p_nome, p_descricao from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_dado_tipo set
         nome      = p_nome,
         descricao = p_descricao
       where sq_dado_tipo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_dado_tipo where sq_dado_tipo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;