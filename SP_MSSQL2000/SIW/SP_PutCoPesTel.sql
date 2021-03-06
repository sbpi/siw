alter procedure SP_PutCoPesTel
   (@p_operacao      varchar(1),
    @p_chave         int   = null,
    @p_pessoa        int,
    @p_ddd           varchar(4),
    @p_numero        varchar(25) = null,
    @p_tipo_telefone int,    
    @p_cidade        int,    
    @p_padrao        varchar(1)
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into co_pessoa_telefone 
         (sq_tipo_telefone,     sq_pessoa,     sq_cidade, 
          ddd,                        numero,               padrao
         )
      (select 
          @p_tipo_telefone,      @p_pessoa,      @p_cidade,
          @p_ddd,                      @p_numero,             @p_padrao
      );
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update co_pessoa_telefone set
         sq_tipo_telefone     = @p_tipo_telefone,
         ddd                  = @p_ddd,
         numero               = @p_numero,
         sq_cidade            = @p_cidade,
         padrao               = @p_padrao
      where sq_pessoa_telefone= @p_chave;
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete co_pessoa_telefone where sq_pessoa_telefone = @p_chave;
   End
end