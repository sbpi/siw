create or replace function SP_GetSegData
   (p_chave     numeric,
    p_result    refcursor
   ) returns refcursor as $$
begin
   --Recupera a lista de módulos
   open p_result for
      select * from co_segmento where sq_segmento = p_chave;
   return p_result;
end; $$ language 'plpgsql' volatile;