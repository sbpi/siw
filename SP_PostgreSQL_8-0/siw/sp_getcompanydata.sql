create or replace FUNCTION SP_GetCompanyData
   (p_cliente  numeric,
    p_cnpj     varchar,
    p_result   REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   open p_result for 
     select a.*, b.nome, b.nome_resumido
       from co_pessoa_juridica a,
            co_pessoa          b
      where a.sq_pessoa             = b.sq_pessoa
        and Nvl(b.sq_pessoa_pai,1)  = p_cliente
        and a.cnpj                  = p_cnpj;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;