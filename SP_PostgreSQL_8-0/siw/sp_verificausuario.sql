-- Function: siw.sp_verificausuario(numeric, character varying, refcursor)

DROP FUNCTION siw.sp_verificausuario(numeric, character varying, refcursor);

CREATE OR REPLACE FUNCTION siw.sp_verificausuario(p_cliente numeric, p_username character varying, p_result refcursor)
  RETURNS refcursor AS
$BODY$
begin
   open p_result for 
       select count(*) as existe 
         from sg_autenticacao a, co_pessoa b 
        where a.sq_pessoa = b.sq_pessoa 
          and b.sq_pessoa_pai = p_cliente
          and upper(username) = upper(p_username);
   return p_result;
end; $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.sp_verificausuario(numeric, character varying, refcursor) OWNER TO siw;
