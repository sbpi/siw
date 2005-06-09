create or replace procedure SP_GetLcFonte
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera as fontes de recurso de licitações
   open p_result for
      select a.sq_lcfonte_recurso chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao, a.orcamentario,
             decode(a.orcamentario ,'S','Sim','Não') nm_orcamentario,
             decode(a.ativo        ,'S','Sim','Não') nm_ativo,
             decode(a.padrao       ,'S','Sim','Não') nm_padrao
        from lc_fonte_recurso a
       where a.cliente = p_cliente
         and ((p_chave is null) or (p_chave is not null and a.sq_lcfonte_recurso = p_chave));
end SP_GetLcFonte;
/

