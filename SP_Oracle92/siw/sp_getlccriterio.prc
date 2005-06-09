create or replace procedure SP_GetLcCriterio
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out sys_refcursor) is
begin
   -- Recupera os critérios de julgamento de licitações
   open p_result for 
      select a.sq_lcjulgamento chave, a.cliente, a.nome, a.descricao, a.item, a.ativo, a.padrao,
             case a.item   when 'S' then 'Sim' else 'Não' end nm_item,
             case a.ativo  when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao when 'S' then 'Sim' else 'Não' end nm_padrao
        from lc_julgamento a
       where a.cliente = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_lcjulgamento = p_chave));
end SP_GetLcCriterio;
/

