create or replace procedure SP_GetLcCriterio
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os critérios de julgamento de licitações
   open p_result for
      select a.sq_lcjulgamento chave, a.cliente, a.nome, a.descricao, a.item, a.ativo, a.padrao,
             decode(a.item  ,'S','Sim','Não') nm_item,
             decode(a.ativo ,'S','Sim','Não') nm_ativo,
             decode(a.padrao,'S','Sim','Não') nm_padrao
        from lc_julgamento a
       where a.cliente = p_cliente
         and ((p_chave is null) or (p_chave is not null and a.sq_lcjulgamento = p_chave));
end SP_GetLcCriterio;
/

