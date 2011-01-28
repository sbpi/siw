create or replace FUNCTION SP_PutTipoLancamento
   (p_operacao    varchar,
    p_chave       numeric,
    p_pai         numeric,
    p_cliente     varchar,
    p_nome        varchar,
    p_descricao   varchar,
    p_receita     varchar,
    p_despesa     varchar,
    p_ativo       varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into fn_tipo_lancamento
        (sq_tipo_lancamento,         cliente,   sq_tipo_lancamento_pai, nome,   descricao,   receita,   despesa,   ativo)
      values
        (sq_tipo_lancamento.nextval, p_cliente, p_pai,                  p_nome, p_descricao, p_receita, p_despesa, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update fn_tipo_lancamento
         set sq_tipo_lancamento_pai = p_pai,
             nome                   = p_nome,
             descricao              = p_descricao,
             receita                = p_receita,
             despesa                = p_despesa,
             ativo                  = p_ativo
       where sq_tipo_lancamento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM fn_tipo_lancamento where sq_tipo_lancamento = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;