create or replace procedure SP_PutCLPesqFornecedor
   (p_operacao                 in varchar2,
    p_cliente                  in number    default null,
    p_chave                    in number    default null,
    p_chave_aux                in number    default null,
    p_fornecedor               in number    default null,
    p_inicio                   in date      default null,
    p_fim                      in date      default null,
    p_valor                    in number    default null,
    p_fabricante               in varchar2  default null,
    p_marca_modelo             in varchar2  default null,
    p_embalagem                in varchar2  default null,
    p_material                 in varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro na tabela CL_ITEM_FORNECEDOR
      insert into cl_item_fornecedor
        (sq_item_fornecedor,         sq_solicitacao_item, sq_material, fornecedor,   inicio,     fim,          valor_unidade,
         valor_item,                 pesquisa,            fabricante, marca_modelo, embalagem)
      values
        (sq_item_fornecedor.nextval, null,                p_material,   p_fornecedor, p_inicio,     p_fim,          p_valor, 
         p_valor,     'S',                 p_fabricante, p_marca_modelo, p_embalagem);
      
      sp_ajustapesquisamaterial(p_cliente,p_material);
   Elsif p_operacao = 'A' Then
      update cl_item_fornecedor set
        inicio = p_inicio,
        fim    = p_fim,
        valor_unidade = p_valor,
        valor_item    = p_valor,
        fabricante    = p_fabricante,
        marca_modelo  = p_marca_modelo,
        embalagem     = p_embalagem
      where sq_item_fornecedor = p_chave;
         
   Elsif p_operacao = 'E' Then
      -- Exclui todos os registros de uma solicitacao
      delete cl_item_fornecedor a
       where a.sq_item_fornecedor = p_chave;
      
      sp_ajustapesquisamaterial(p_cliente,p_material);
   End If;
end SP_PutCLPesqFornecedor;
/
