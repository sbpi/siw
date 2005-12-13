create or replace function SP_VerificaUsuario
   (p_cliente  in numeric,
    p_username in varchar,
    p_result   refcursor
   ) returns refcursor as $$
begin
   open p_result for 
       select count(*) as existe 
         from sg_autenticacao a, co_pessoa b 
        where a.sq_pessoa = b.sq_pessoa 
          and b.sq_pessoa_pai = p_cliente
          and upper(username) = upper(p_username);
   return p_result;
end; $$ language plpgsql volatile;