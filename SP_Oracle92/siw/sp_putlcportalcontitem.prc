create or replace procedure SP_PutLcPortalContItem
   (p_operacao                 in  varchar2,
    p_chave                    in  number,
    p_sq_portal_lic_item       in  number default null,
    p_valor                    in  number default null,
    p_quantidade               in  number default null
   ) is
begin
   if p_operacao = 'I' Then
      -- Insere os registro
      insert into lc_portal_contrato_item(sq_portal_contrato, sq_portal_lic_item) 
                  (select p_chave, p_sq_portal_lic_item from dual);
      update lc_portal_lic_item set 
         valor_unitario = p_valor,
         valor_total    = (p_valor*p_quantidade)
       where sq_portal_lic_item = p_sq_portal_lic_item; 

   Elsif p_operacao = 'E' Then
      -- Apaga os registro
      update lc_portal_lic_item set 
         valor_unitario = null,
         valor_total    = null
       where sq_portal_lic_item in (select sq_portal_lic_item 
                                     from lc_portal_contrato_item
                                   where sq_portal_contrato = p_chave); 
      delete lc_portal_contrato_item where sq_portal_contrato = p_chave;
   End If;
end SP_PutLcPortalContItem;
/

