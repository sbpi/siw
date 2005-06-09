create or replace procedure SP_GetCityList
   (p_pais      in  number,
    p_estado    in  varchar2,
    p_result    out sys_refcursor) is
begin
   -- Recupera as cidades existentes
   open p_result for 
      select a.sq_cidade, a.sq_cidade sq_cidade, b.co_uf, c.nome sq_pais, a.nome, nvl(a.ddd,'-') ddd,
             case a.capital when 'S' then 'Sim' else 'Não' end capital, 
             Nvl(a.codigo_ibge,'-') codigo_ibge
        from co_cidade            a
             inner   join co_uf   b on (a.co_uf   = b.co_uf and 
                                        a.sq_pais = b.sq_pais
                                       )
               inner join co_pais c on (b.sq_pais = c.sq_pais)
       where b.co_uf   = p_estado
         and c.sq_pais = p_pais;
end SP_GetCityList;
/

