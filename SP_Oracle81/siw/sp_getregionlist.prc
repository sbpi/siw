create or replace procedure SP_GetRegionList
   (p_pais      in  number default null,
    p_tipo      in  varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
If p_tipo = 'N' then
   -- Recupera a lista daas regiões existentes
   open p_result for
      select a.sq_regiao, a.nome, a.nome nome, a.ordem, a.sigla, b.nome nome_pais, b.sq_pais sq_pais, b.padrao,
             b.padrao padrao, a.sq_regiao sq_regiao
        from co_regiao a, co_pais b
       where a.sq_pais = b.sq_pais;
Else
   -- Recupera as regiões de um determinado pais
   open p_result for
      select *
        from co_regiao
       where sq_pais = p_pais;
End If;
end SP_GetRegionList;
/

