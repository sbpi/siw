create or replace function SP_GetSegModData
   (p_sq_segmento numeric,
    p_sq_modulo   numeric,
    p_result      refcursor
   ) returns refcursor as $$
begin
   open p_result for
      select a.*, b.nome, b.objetivo_geral
        from siw_mod_seg a, 
             siw_modulo b
       where a.sq_modulo   = b.sq_modulo
         and a.sq_modulo   = p_sq_modulo
         and a.sq_segmento = p_sq_segmento;
   return p_result;
end; $$ language 'plpgsql' volatile;
