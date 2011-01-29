create or replace FUNCTION SP_PutRelacionamento
-- Giderclay Zeballos
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_nome                      varchar,
    p_descricao                 varchar,
    p_sq_tabela_pai             numeric,
    p_sq_tabela_filha           numeric,
    p_sq_sistema                numeric    

    ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_relacionamento
        (sq_relacionamento, nome, descricao, tabela_pai, tabela_filha, sq_sistema)
      (select nextVal('sq_relacionamento'), p_nome, p_descricao, p_sq_tabela_pai, p_sq_tabela_filha, p_sq_sistema);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_relacionamento
         set 
             nome = p_nome,
             descricao = p_descricao,
             tabela_pai = p_sq_tabela_pai,
             tabela_filha = p_sq_tabela_filha,
             sq_sistema = p_sq_sistema
       where sq_relacionamento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_relacionamento
       where sq_relacionamento = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;