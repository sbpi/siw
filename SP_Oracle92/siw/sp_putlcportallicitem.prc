create or replace procedure SP_PutLcPortalLicItem
   (p_operacao            in  varchar2,
    p_cliente             in number,
    p_chave               in number    default null,
    p_chave_aux           in number    default null,
    p_ordem               in number    default null,
    p_nome                in varchar2  default null,
    p_quantidade          in number    default null,
    p_descricao           in varchar2  default null,
    p_unidade_fornec      in number    default null,
    p_cancelado           in varchar2  default null,
    p_situacao            in varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de itens de licitação
      insert into lc_portal_lic_item
        (sq_portal_lic_item,         cliente,        sq_portal_lic,   ordem,       nome,   
         descricao,                  quantidade,     cancelado,       situacao,    sq_unidade_fornec)
      values
        (sq_portal_lic_item.nextval, p_cliente,      p_chave,         p_ordem,     p_nome, 
         p_descricao,                p_quantidade,   p_cancelado,     p_situacao,  p_unidade_fornec);
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de itens de licitação
      update lc_portal_lic_item set 
         ordem             = p_ordem,
         nome              = p_nome,
         descricao         = p_descricao,
         sq_unidade_fornec = p_unidade_fornec,
         quantidade        = p_quantidade,
         cancelado         = p_cancelado,
         situacao          = p_situacao
       where sq_portal_lic_item = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de itens de licitação
      delete lc_portal_lic_item where sq_portal_lic_item = p_chave_aux;
   End If;
end SP_PutLcPortalLicItem;
/

