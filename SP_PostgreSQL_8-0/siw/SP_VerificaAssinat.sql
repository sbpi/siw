create or replace function SP_VerificaAssinat
   (p_cliente  numeric,
    p_username varchar,
    p_senha    varchar,
    p_result   refcursor
   ) returns refcursor as $$
begin
   open p_result for 
       select ativo 
         from sg_autenticacao a, co_pessoa b 
        where a.sq_pessoa     = b.sq_pessoa 
          and b.sq_pessoa_pai = p_cliente
          and upper(username) = upper(p_username) 
          and upper(Assinatura)    = criptografia(upper(p_senha));
   return p_result;
end; $$ language 'plpgsql' volatile;