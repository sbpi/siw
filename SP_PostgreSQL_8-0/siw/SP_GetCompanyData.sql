create or replace function SP_GetCompanyData
   (p_cliente  numeric,
    p_cnpj     varchar,
    p_result   refcursor
   ) returns refcursor as $$
begin
   open p_result for 
     select a.*, b.nome, b.nome_resumido
       from co_pessoa_juridica a,
            co_pessoa          b
      where a.sq_pessoa             = b.sq_pessoa
        and p_cliente		    = coalesce(b.sq_pessoa_pai,1)
        and a.cnpj                  = p_cnpj;
   return p_result;
end; $$ language 'plpgsql' volatile;

