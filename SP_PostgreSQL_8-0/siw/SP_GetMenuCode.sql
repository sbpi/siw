create or replace function SP_GetMenuCode
   (p_cliente   numeric,
    p_sigla     varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera o c�digo de uma op��o do menu a partir de sua sigla
   open p_result for
      select *
      from siw_menu a 
      where a.sq_pessoa = p_cliente
        and a.sigla     = p_sigla;
   return p_result;
end $$ language 'plpgsql' volatile;


