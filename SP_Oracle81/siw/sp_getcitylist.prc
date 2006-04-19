create or replace procedure SP_GetCityList
   (p_pais      in  number,
    p_estado    in  varchar2,
    p_nome      in  varchar2  default null,
    p_restricao in  varchar2  default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera as cidades existentes
   open p_result for
      select a.sq_cidade, a.sq_cidade sq_cidade, b.co_uf, c.nome sq_pais, a.nome, nvl(a.ddd,'-') ddd,
             decode(a.capital,'S','Sim','Não') capital,
             Nvl(a.codigo_ibge,'-') codigo_ibge
        from co_cidade            a,
             co_uf   b,
               co_pais c
       where (a.co_uf   = b.co_uf and
              a.sq_pais = b.sq_pais
             )
         and (b.sq_pais = c.sq_pais)
         and b.co_uf   = p_estado
         and c.sq_pais = p_pais
         and (p_nome is null or (p_nome is not null and acentos(a.nome) like acentos(p_nome)));
end SP_GetCityList;
/
