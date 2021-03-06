ALTER procedure dbo.SP_VerificaSenha
   (@p_cliente  int,
    @p_username varchar(30),
    @p_senha    varchar(255)
   ) as
begin
   select ativo 
     from sg_autenticacao a, co_pessoa b 
    where a.sq_pessoa     = b.sq_pessoa 
      and b.sq_pessoa_pai = @p_cliente
      and upper(username) = upper(@p_username)
      and upper(senha)    = dbo.criptografia(upper(@p_senha));

end
