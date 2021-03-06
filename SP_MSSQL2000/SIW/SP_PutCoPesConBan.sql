alter procedure dbo.SP_PutCoPesConBan
   (@p_operacao      varchar(1),
    @p_chave         int   = null,
    @p_pessoa        int,
    @p_agencia       int,    
    @p_oper          varchar(6),
    @p_numero        varchar(30) = null,
    @p_tipo_conta    int,    
    @p_ativo         varchar(4),
    @p_padrao        varchar(1)
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into co_pessoa_conta
         (operacao,              sq_pessoa,      sq_agencia, 
          numero,                           ativo,                 padrao,         tipo_conta
         )
      (select 
          @p_oper,                @p_pessoa,       @p_agencia,
          @p_numero,                         @p_ativo,               @p_padrao,       @p_tipo_conta
      );
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update co_pessoa_conta set
         operacao             = @p_oper,
         tipo_conta           = @p_tipo_conta,
         ativo                = @p_ativo,
         padrao               = @p_padrao
      where sq_pessoa_conta   = @p_chave;
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete co_pessoa_conta where sq_pessoa_conta = @p_chave;
   End
end
