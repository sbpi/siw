create or replace procedure SP_PutCLItemFornecedor
   (p_operacao                 in varchar2,
    p_cliente                  in number    default null,
    p_chave                    in number    default null,
    p_chave_aux                in number    default null,
    p_fornecedor               in number    default null,
    p_inicio                   in date      default null,
    p_dias                     in number    default null,
    p_valor                    in number    default null,
    p_fabricante               in varchar2  default null,
    p_marca_modelo             in varchar2  default null,
    p_embalagem                in varchar2  default null,
    p_fator                    in number    default null,
    p_ordem                    in varchar2  default null,
    p_vencedor                 in varchar2  default null,
    p_pesquisa                 in varchar2  default null
   ) is
   w_material   number(18);
   w_quantidade number(18,2);
  cursor c_itens is
    select x.sq_material from cl_solicitacao_item x where x.sq_siw_solicitacao = p_chave;
begin
   If p_operacao = 'I' or p_operacao = 'A' Then
      -- Recupera o material do item
      select sq_material, quantidade into w_material, w_quantidade from cl_solicitacao_item where sq_solicitacao_item = p_chave_aux;
      
      -- Insere registro na tabela CL_ITEM_FORNECEDOR
      insert into cl_item_fornecedor
        (sq_item_fornecedor,         sq_solicitacao_item, sq_material, fornecedor,   inicio,     fim,          valor_unidade,
         valor_item,                 ordem,               vencedor,    pesquisa,     fabricante, marca_modelo, embalagem, 
         dias_validade_proposta,     fator_embalagem)
      values
        (sq_item_fornecedor.nextval, p_chave_aux,         w_material,  p_fornecedor, p_inicio,     (p_inicio + p_dias - 1), p_valor, 
         (p_valor*w_quantidade),     p_ordem,             p_vencedor,  p_pesquisa,   p_fabricante, p_marca_modelo, p_embalagem,
         p_dias,                     coalesce(p_fator,1)
        );
      
      -- Atualiza a tabela CL_MATERIAL
      If p_pesquisa = 'S' Then
         sp_ajustapesquisamaterial(p_cliente,w_material);
      
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
      delete cl_item_fornecedor a
       where a.fornecedor          = p_fornecedor
         and a.pesquisa            = p_pesquisa
         and a.sq_solicitacao_item in (select x.sq_solicitacao_item 
                                         from cl_solicitacao_item x
                                        where x.sq_siw_solicitacao = p_chave);
      -- Ajusta os dados dos materiais que têm pesquisa válida
      If p_pesquisa = 'S' Then
         for crec in c_itens loop
            sp_ajustapesquisamaterial(p_cliente,crec.sq_material);
         end loop;
      End If;
   End If;
end SP_PutCLItemFornecedor;
/
