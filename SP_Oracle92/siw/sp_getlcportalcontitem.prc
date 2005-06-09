create or replace procedure SP_GetLcPortalContItem
   (p_cliente    in number,
    p_chave      in number,
    p_chave_aux  in number,
    p_chave_aux1 in number,
    p_cancelado  in varchar2,
    p_result    out sys_refcursor) is
begin
   -- Recupera os itens de um contrato
   open p_result for 
     select a.ordem, a.nome, a.sq_portal_lic_item, b.sq_portal_contrato Existe,
            a.descricao, a.quantidade, a.cancelado, a.situacao, a.valor_unitario, a.valor_total,
            case a.cancelado when 'S' then 'Sim' else 'Não' end nm_cancelado,
            c.nome nm_unidade_fornec, c.sigla sg_unidade_fornec
        from lc_portal_lic_item                a
             left outer join (select x.sq_portal_lic_item, x.sq_portal_contrato 
                                from lc_portal_contrato_item x
                             )                 b on (a.sq_portal_lic_item = b.sq_portal_lic_item)
             left outer join lc_unidade_fornec c on (a.sq_unidade_fornec  = c.sq_unidade_fornec)
       where a.cliente       = p_cliente
         and a.sq_portal_lic = p_chave
         and (p_chave_aux    is null or (p_chave_aux is not null and b.sq_portal_contrato = p_chave_aux))
         and (p_cancelado    is null or (p_cancelado is not null and a.cancelado          = p_cancelado))
      order by a.ordem;
end SP_GetLcPortalContItem;
/

