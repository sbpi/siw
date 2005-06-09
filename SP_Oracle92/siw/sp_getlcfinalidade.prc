create or replace procedure SP_GetLcFinalidade
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out sys_refcursor) is
begin
   -- Recupera as finalidades de licitação
   open p_result for 
      select a.sq_lcfinalidade chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao,
             case a.ativo  when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao when 'S' then 'Sim' else 'Não' end nm_padrao
        from lc_finalidade a
       where a.cliente = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_lcfinalidade = p_chave));
end SP_GetLcFinalidade;
/

