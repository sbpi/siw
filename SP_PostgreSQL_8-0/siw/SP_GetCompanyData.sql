CREATE OR REPLACE FUNCTION siw.SP_GetCompanyData
   (p_cliente  numeric,
    p_cnpj     varchar)

  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   open p_result for
     select a.*, b.nome, b.nome_resumido
       from siw.co_pessoa_juridica a,
            siw.co_pessoa          b
      where a.sq_pessoa             = b.sq_pessoa
        and Nvl(b.sq_pessoa_pai,1)  = p_cliente
        and a.cnpj                  = p_cnpj;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCompanyData
   (p_cliente  numeric,
    p_cnpj     varchar)
 OWNER TO siw;
