create or replace function SP_GetCustomerSite
   (p_cliente  in  numeric,
    p_result   refcursor
   ) returns refcursor as $$
begin
   open p_result for
      select logradouro
        from co_pessoa_endereco a,
             co_tipo_endereco   b
       where a.sq_tipo_endereco = b.sq_tipo_endereco
         and b.internet         = 'S'
         and a.padrao           = 'S'
         and a.sq_pessoa        = p_cliente;
  return p_result;
end; $$ language plpgsql volatile;
