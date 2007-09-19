create or replace procedure SP_PutCLItemFornecedor
   (p_operacao                 in varchar2,
    p_cliente                  in number    default null,
    p_chave                    in number    default null,
    p_chave_aux                in number    default null,
    p_material                 in number    default null,
    p_fornecedor               in number    default null,
    p_inicio                   in date      default null,
    p_fim                      in date      default null,
    p_valor                    in number    default null,
    p_quantidade               in number    default null,
    p_fabricante               in varchar2  default null,
    p_marca_modelo             in varchar2  default null,
    p_embalagem                in varchar2  default null,
    p_ordem                    in number    default null,
    p_vencedor                 in varchar2  default null,
    p_pesquisa                 in varchar2  default null
   ) is
  cursor c_itens is
    select x.sq_material from cl_solicitacao_item x where x.sq_siw_solicitacao = p_chave;
begin
   If p_operacao = 'I' or p_operacao = 'A' Then
      -- Insere registro na tabela CL_ITEM_FORNECEDOR
      insert into cl_item_fornecedor
        (sq_item_fornecedor,         sq_solicitacao_item, sq_material, fornecedor,   inicio,     fim,          valor_unidade,
         valor_item,                 ordem,               vencedor,    pesquisa,     fabricante, marca_modelo, embalagem)
      values
        (sq_item_fornecedor.nextval, p_chave_aux,         p_material,  p_fornecedor, p_inicio,     p_fim,          p_valor, 
         (p_valor*p_quantidade),     p_ordem,             p_vencedor,  p_pesquisa,   p_fabricante, p_marca_modelo, p_embalagem);
      
      -- Atualiza a tabela CL_MATERIAL
      sp_ajustapesquisamaterial(p_cliente,p_material);
        
   Elsif p_operacao = 'E' Then
      -- Exclui todos os registros de uma solicitacao
      delete cl_item_fornecedor a
       where a.fornecedor          = p_fornecedor
         and a.sq_solicitacao_item in (select x.sq_solicitacao_item 
                                         from cl_solicitacao_item x
                                        where x.sq_siw_solicitacao = p_chave);
      -- Ajusta os dados dos materiais que têm pesquisa válida
      for crec in c_itens loop
         sp_ajustapesquisamaterial(p_cliente,crec.sq_material);
      end loop;
   End If;
end SP_PutCLItemFornecedor;
/
