create or replace FUNCTION SP_PutLcPortalLicItem
   (p_operacao             varchar,
    p_cliente             numeric,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_ordem               numeric,
    p_nome                varchar,
    p_quantidade          numeric,
    p_descricao           varchar,
    p_unidade_fornec      numeric,
    p_cancelado           varchar,
    p_situacao            varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
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
      DELETE FROM lc_portal_lic_item where sq_portal_lic_item = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;