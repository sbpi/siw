create or replace FUNCTION sp_putUnidadeMedida
   (p_operacao    varchar,
    p_cliente     varchar,
    p_chave       numeric,
    p_nome        varchar,
    p_sigla       varchar,
    p_ativo       varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_unidade_medida
        (sq_unidade_medida,         cliente,   nome,   sigla,          ativo)
      values
        (sq_unidade_medidanextVal(''), p_cliente, p_nome, upper(p_sigla), p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_unidade_medida
         set nome          = p_nome,
             sigla         = upper(p_sigla),
             ativo         = p_ativo
       where sq_unidade_medida = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM co_unidade_medida where sq_unidade_medida = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;