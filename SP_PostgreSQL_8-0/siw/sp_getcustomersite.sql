create or replace FUNCTION SP_GetCustomerSite
   (p_cliente   numeric,
    p_result   REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   open p_result for
      select logradouro
        from co_pessoa_endereco a,
             co_tipo_endereco   b
       where a.sq_tipo_endereco = b.sq_tipo_endereco
         and b.internet         = 'S'
         and a.padrao           = 'S'
         and a.sq_pessoa        = p_cliente;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;