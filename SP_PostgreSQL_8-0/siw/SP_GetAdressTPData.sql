create or replace function SP_GetAdressTPData
   (p_sq_tipo_endereco numeric,
    p_result           refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do tipo de endereço
   open p_result for 
      select * from co_tipo_endereco where sq_tipo_endereco = p_sq_tipo_endereco;
   return p_result;
end; $$ language 'plpgsql' volatile;