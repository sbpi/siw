create or replace function SP_GetSegModList
   (p_sq_segmento numeric,
    p_result      refcursor
   ) returns refcursor as $$
begin
   --Recupera a lista de módulos
   open p_result for
      select sq_modulo, nome 
        from siw_modulo
       where sq_modulo not in ( select a.sq_modulo
                                  from siw_modulo a, 
                                       siw_mod_seg b
                                 where a.sq_modulo = b.sq_modulo
                                   and sq_segmento = p_sq_segmento);
   return p_result;
end; $$ language 'plpgsql' volatile;