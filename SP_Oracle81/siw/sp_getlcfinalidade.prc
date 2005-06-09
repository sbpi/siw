create or replace procedure SP_GetLcFinalidade
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera as finalidades de licitação
   open p_result for
      select a.sq_lcfinalidade chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao,
             decode(a.ativo ,'S','Sim','Não') nm_ativo,
             decode(a.padrao,'S','Sim','Não') nm_padrao
        from lc_finalidade a
       where a.cliente = p_cliente
         and ((p_chave is null) or (p_chave is not null and a.sq_lcfinalidade = p_chave));
end SP_GetLcFinalidade;
/

