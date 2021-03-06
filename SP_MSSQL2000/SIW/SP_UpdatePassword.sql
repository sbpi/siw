alter procedure dbo.SP_UpdatePassword
   (@p_cliente  int,
    @p_sq_pessoa int,
    @p_valor    varchar(255),
    @p_tipo     varchar(50)
   ) as
begin
   If @p_tipo = 'PASSWORD' Begin
      update sg_autenticacao
         set senha                   = dbo.criptografia(upper(@p_valor)),
             ultima_troca_senha      = getdate(),
             tentativas_senha        = 0
       where sq_pessoa               = @p_sq_pessoa
   End Else If @p_tipo = 'SIGNATURE' Begin
      update sg_autenticacao
         set assinatura              = dbo.criptografia(upper(@p_valor)),
             ultima_troca_assin      = getdate(),
             tentativas_assin        = 0
       where sq_pessoa               = @p_sq_pessoa
   End
end