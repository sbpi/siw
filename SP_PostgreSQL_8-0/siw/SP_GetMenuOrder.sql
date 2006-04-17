create or replace function sp_getMenuOrder
   (p_cliente      numeric,
    p_sq_menu      numeric,
    p_chave_aux    numeric,
    p_ultimo_nivel varchar,
    p_result       refcursor
   ) returns refcursor as $$
begin
   -- Recupera o número de ordem das outras opções irmãs à informada
   If p_sq_menu is null Then
      open p_result for
         select a.sq_menu, a.ultimo_nivel, a.acesso_geral, a.ordem, a.nome 
           from siw_menu a 
          where a.sq_menu_pai   is null 
            and a.sq_pessoa     = p_cliente 
            and (p_chave_aux    is null or (p_chave_aux    is not null and a.sq_menu      <> p_chave_aux)) 
            and (p_ultimo_nivel is null or (p_ultimo_nivel is not null and a.ultimo_nivel =  p_ultimo_nivel))
         order by a.ordem;
   Else
      open p_result for
         select a.sq_menu, a.ultimo_nivel, a.acesso_geral, a.ordem, a.nome 
           from siw_menu a 
          where a.sq_menu_pai   = p_sq_menu 
            and a.sq_pessoa     = p_cliente 
            and (p_chave_aux    is null or (p_chave_aux    is not null and a.sq_menu      <> p_chave_aux)) 
            and (p_ultimo_nivel is null or (p_ultimo_nivel is not null and a.ultimo_nivel =  p_ultimo_nivel))
         order by a.ordem;
   End If;
   return p_result;
end; $$ language 'plpgsql' volatile;