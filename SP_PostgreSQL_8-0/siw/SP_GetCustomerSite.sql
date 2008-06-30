CREATE OR REPLACE FUNCTION siw.SP_GetCustomerSite
   (p_cliente  numeric,
    p_result   refcursor
   )

  RETURNS refcursor AS
$BODY$
begin
   open p_result for
      select logradouro
        from siw.co_pessoa_endereco a,
             siw.co_tipo_endereco   b
       where a.sq_tipo_endereco = b.sq_tipo_endereco
         and b.internet         = 'S'
         and a.padrao           = 'S'
         and a.sq_pessoa        = p_cliente;
  return p_result;
end
 $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
