CREATE procedure dbo.SP_VerificaUsuario
   (@p_cliente  int,
    @p_username varchar(30)
   ) as
begin
  select count(*) existe 
    from sg_autenticacao a, co_pessoa b 
   where a.sq_pessoa = b.sq_pessoa 
     and b.sq_pessoa_pai = @p_cliente
     and upper(username) = upper(@p_username)
end
