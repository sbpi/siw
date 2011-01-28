create or replace FUNCTION SP_PutTipoTabela
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_nome                      varchar,
    p_descricao                 varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_tabela_tipo (sq_tabela_tipo, nome, descricao)
      (select sq_tabela_tipo.nextval, p_nome, p_descricao);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_tabela_tipo set
         nome      = p_nome,
         descricao = p_descricao
       where sq_tabela_tipo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_tabela_tipo where sq_tabela_tipo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;