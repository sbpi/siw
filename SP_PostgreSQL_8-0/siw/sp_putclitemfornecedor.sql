create or replace FUNCTION SP_PutCLItemFornecedor
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
    p_ordem                    varchar,
    p_vencedor                 varchar,
    p_pesquisa                 varchar,
    p_origem                   varchar      
   ) RETURNS VOID AS $$
DECLARE
   w_material   numeric(18);
   w_quantidade numeric(18,2);
   c_itens CURSOR FOR
    select x.sq_material from cl_solicitacao_item x where x.sq_siw_solicitacao = p_chave;
BEGIN
   If p_operacao = 'I' or p_operacao = 'A' Then
      -- Recupera o material do item
      select sq_material, quantidade into w_material, w_quantidade from cl_solicitacao_item where sq_solicitacao_item = p_chave_aux;
      
      -- Insere registro na tabela CL_ITEM_FORNECEDOR
      insert into cl_item_fornecedor
        (sq_item_fornecedor,         sq_solicitacao_item, sq_material, fornecedor,   inicio,     fim,          valor_unidade,
         valor_item,                 ordem,               vencedor,    pesquisa,     fabricante, marca_modelo, embalagem, 
         dias_validade_proposta,     origem,              fator_embalagem)
      values
        (nextVal('sq_item_fornecedor'), p_chave_aux,         w_material,  p_fornecedor, p_inicio,     (p_inicio + p_dias - 1), p_valor, 
         (p_valor*w_quantidade),     p_ordem,             p_vencedor,  p_pesquisa,   p_fabricante, p_marca_modelo, p_embalagem,
         p_dias,                     case                 p_pesquisa   when 'S' then p_origem else 'PF' end,              coalesce(p_fator,1)
        );
      
      -- Atualiza a tabela CL_MATERIAL
      If p_pesquisa = 'S' Then
         PERFORM sp_ajustapesquisamaterial(p_cliente,w_material);
      
         -- Atualiza o valor da solicitação
         update siw_solicitacao a
            set a.valor = (select sum(x.quantidade * nvl(y.pesquisa_preco_medio,0))
                             from cl_solicitacao_item           x
                                  inner join cl_material        y on (x.sq_material = y.sq_material)
                            where x.sq_siw_solicitacao = p_chave
                          )
         where a.sq_siw_solicitacao = p_chave;
      End If;
        
   Elsif p_operacao = 'E' Then
      -- Exclui todos os registros de uma solicitacao
      DELETE FROM cl_item_fornecedor a
       where a.fornecedor          = p_fornecedor
         and a.pesquisa            = p_pesquisa
         and a.sq_solicitacao_item in (select x.sq_solicitacao_item 
                                         from cl_solicitacao_item x
                                        where x.sq_siw_solicitacao = p_chave);
      -- Ajusta os dados dos materiais que têm pesquisa válida
      If p_pesquisa = 'S' Then
         for crec in c_itens loop
            PERFORM sp_ajustapesquisamaterial(p_cliente,crec.sq_material);
         end loop;
      End If;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;