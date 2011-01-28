CREATE OR REPLACE FUNCTION sp_verificausuario(p_cliente numeric, p_username character varying, p_result refcursor) RETURNS refcursor AS $$
begin
   open p_result for 
       select count(*) as existe 
         from sg_autenticacao a, co_pessoa b 
        where a.sq_pessoa = b.sq_pessoa 
          and b.sq_pessoa_pai = p_cliente
          and upper(username) = upper(p_username);
   return p_result;
END; $$ LANGUAGE 'plpgsql' VOLATILE;
