create or replace function SP_GetLnkDataPrnt
   (p_cliente   numeric,
    p_sg        varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do link pai do que foi informado
   open p_result for 
      select a.sq_menu as menu_pai, b.*
        from siw_menu a, siw_menu b
       where a.sq_menu       = b.sq_menu_pai
         and a.sigla         = p_sg
         and a.sq_pessoa     = p_cliente;
   return p_result;
end; $$ language 'plpgsql' volatile;

