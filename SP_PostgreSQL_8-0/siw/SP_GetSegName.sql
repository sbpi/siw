create or replace function SP_GetSegName
   (p_sq_segmento numeric,
    p_result      refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados da etnia informada
   open p_result for 
      select nome from co_segmento where sq_segmento = p_sq_segmento;
   return p_result;
end; $$ language 'plpgsql' volatile;