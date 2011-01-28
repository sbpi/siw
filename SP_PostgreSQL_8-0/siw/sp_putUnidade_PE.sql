create or replace FUNCTION sp_putUnidade_PE
   (p_operacao           varchar,
    p_cliente            numeric,
    p_chave              numeric,
    p_descricao          varchar,
    p_planejamento       varchar,
    p_execucao           varchar,
    p_recursos           varchar,
    p_ativo              varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pe_unidade (sq_unidade, cliente, descricao, planejamento, execucao, gestao_recursos, ativo) 
      values (p_chave, p_cliente, p_descricao, p_planejamento, p_execucao, p_recursos, p_ativo);
   Elsif p_operacao = 'A' Then
      update pe_unidade
         set descricao       = p_descricao,
             planejamento    = p_planejamento,
             execucao        = p_execucao,
             gestao_recursos = p_recursos,
             ativo           = p_ativo
       where sq_unidade = p_chave;
             
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pe_unidade where sq_unidade = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;