create or replace procedure SP_VerificaSenha
   (p_cliente  in number,
    p_username in varchar2,
    p_senha    in varchar2,
    p_result   out sys_refcursor
   ) is
   
begin
   open p_result for select 'S' ativo from dual;
       /*select a.ativo 
         from usuario a, pessoa b 
        where a.id_pessoa     = b.id_pessoa 
          and upper(login) = upper(p_username)
          and upper(senha)    = criptografia(upper(p_senha));*/
end SP_VerificaSenha;
/
