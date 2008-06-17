CREATE OR REPLACE FUNCTION siw.sp_verificasenha(p_cliente numeric, p_username character varying, p_senha character varying, p_result refcursor)
  RETURNS refcursor AS $BODY$
begin
   open p_result for
       select ativo
         from sg_autenticacao a, co_pessoa b
        where a.sq_pessoa     = b.sq_pessoa
          and b.sq_pessoa_pai = p_cliente
          and upper(username) = upper(p_username)
          and upper(senha)    = criptografia(upper(p_senha));
   return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE

