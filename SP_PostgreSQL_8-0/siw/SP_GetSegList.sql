create or replace function SP_GetSegList
   (p_ativo       varchar,
    p_result      refcursor
   ) returns refcursor as $$
begin
   --Recupera a lista de segmentos
   open p_result for
      select sq_segmento, nome, padrao, ativo 
        from co_segmento
       where (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
   return p_result;
end; $$ language 'plpgsql' volatile;
