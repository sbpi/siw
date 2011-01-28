create or replace FUNCTION SP_PutCLPesqFornecedor
   (p_operacao                 varchar,
    p_cliente                  numeric,
    p_chave                    numeric,
    p_chave_aux                numeric,
    p_fornecedor               numeric,
    p_inicio                   date,
    p_dias                     numeric,
    p_valor                    numeric,
    p_fabricante               varchar,
    p_marca_modelo             varchar,
    p_embalagem                varchar,
    p_fator                    numeric,
    p_material                 varchar,
    p_origem                   varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao <> 'E' Then
      If p_chave is null Then
         -- Insere registro na tabela CL_ITEM_FORNECEDOR
         insert into cl_item_fornecedor
           (sq_item_fornecedor,         sq_solicitacao_item, sq_material,  fornecedor,     inicio,    fim,   valor_unidade,
            valor_item,                 pesquisa,            fabricante,   marca_modelo,   embalagem, ordem, dias_validade_proposta,
            origem,                     fator_embalagem)
         values
           (sq_item_fornecedor.nextval, null,                p_material,   p_fornecedor,   p_inicio,  (p_inicio + p_dias -1), p_valor, 
            p_valor,                    'S',                 p_fabricante, p_marca_modelo, p_embalagem, '0', p_dias,
            p_origem,                   coalesce(p_fator,1));
         -- Atualiza a tabela de materiais
         sp_ajustapesquisamaterial(p_cliente,p_material);
      Elsif p_chave is not null Then
         update cl_item_fornecedor set
           inicio                 = p_inicio,
           dias_validade_proposta = p_dias,
           fim                    = (p_inicio + p_dias - 1),
           valor_unidade          = p_valor,
           valor_item             = p_valor,
           fabricante             = p_fabricante,
           marca_modelo           = p_marca_modelo,
           embalagem              = p_embalagem,
           fator_embalagem        = coalesce(p_fator,fator_embalagem),
           origem                 = p_origem
         where sq_item_fornecedor = p_chave;
         -- Atualiza a tabela de materiais
         sp_ajustapesquisamaterial(p_cliente,p_material);
      End If;
   Elsif p_operacao = 'E' Then
      -- Exclui pesquisa de preço
      DELETE FROM cl_item_fornecedor a
       where a.sq_item_fornecedor = p_chave;
      -- Atualiza a tabela de materiais
      sp_ajustapesquisamaterial(p_cliente,p_material);
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;