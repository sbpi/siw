create or replace function SP_GetVincKindData
   (p_sq_tipo_vinculo   numeric,
    p_result            refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do tipo de vinculo
   open p_result for 
      select nome, sq_tipo_pessoa, interno, contratado, ativo, padrao
      from co_tipo_vinculo 
      where sq_tipo_vinculo = p_sq_tipo_vinculo;
   return p_result;
end; $$ language 'plpgsql' volatile;