create or replace FUNCTION SP_PutCategoriaDiaria
   (p_operacao                  varchar,
    p_cliente                   numeric,
    p_chave                     numeric,
    p_nome                      varchar,
    p_ativo                     varchar,
    p_tramite_especial          varchar,
    p_dias_prest_contas         numeric,
    p_valor_complemento         numeric   
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_categoria_diaria ( sq_categoria_diaria,         cliente,   nome,         ativo,   tramite_especial,   dias_prestacao_contas, valor_complemento)
      (select                           nextVal('sq_categoria_diaria'), p_cliente, trim(p_nome), p_ativo, p_tramite_especial, p_dias_prest_contas,   p_valor_complemento);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pd_categoria_diaria set
         nome                   = trim(p_nome),
         ativo                  = p_ativo,
         tramite_especial       = p_tramite_especial,
         dias_prestacao_contas  = p_dias_prest_contas,
         valor_complemento      = p_valor_complemento
      where sq_categoria_diaria = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pd_categoria_diaria where sq_categoria_diaria = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;