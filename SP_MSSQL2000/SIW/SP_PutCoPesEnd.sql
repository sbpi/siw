alter procedure dbo.SP_PutCoPesEnd
   (@p_operacao          varchar(1),
    @p_chave             int         = null,
    @p_pessoa            int         = null,
    @p_logradouro        varchar(60) = null,
    @p_complemento       varchar(20) = null,
    @p_tipo_endereco     int         = null,
    @p_cidade            int         = null,    
    @p_cep               varchar(9)  = null,
    @p_bairro            varchar(30) = null,
    @p_padrao            varchar(1)  = null
   ) as
begin
   Declare @w_tipo_end            varchar(4000);
   Declare @w_sq_menu             numeric(18);
   Declare @w_sq_pessoa_endereco  numeric(18);
   Declare c_menu cursor for
     select sq_menu from siw_menu where sq_pessoa = @p_pessoa;
   
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into co_pessoa_endereco 
         (sq_tipo_endereco,     sq_pessoa,     sq_cidade, 
          logradouro,           complemento,   bairro,
          cep,                  padrao
         )
      values (
          @p_tipo_endereco,     @p_pessoa,     @p_cidade,
          @p_logradouro,        @p_complemento,@p_bairro,
          @p_cep,               @p_padrao
      )

      -- Recupera a chave utilizada
      Select @w_sq_pessoa_endereco = @@IDENTITY

      select @w_tipo_end = nome from co_tipo_endereco where sq_tipo_endereco = @p_tipo_endereco;

      If (@w_tipo_end = 'Comercial') Begin
         
         Open c_menu
         Fetch Next from c_menu into @w_sq_menu
         While @@Fetch_Status = 0 Begin
            insert into siw_menu_endereco(sq_menu, sq_pessoa_endereco) values (@w_sq_menu, @w_sq_pessoa_endereco);
            Fetch Next from c_menu into @w_sq_menu
         End
         Close c_menu
         Deallocate c_menu
      End
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update co_pessoa_endereco set
         sq_tipo_endereco     = @p_tipo_endereco,
         logradouro           = @p_logradouro,
         cep                  = @p_cep,
         bairro               = @p_bairro,
         complemento          = @p_complemento,
         sq_cidade            = @p_cidade,
         padrao               = @p_padrao
      where sq_pessoa_endereco= @p_chave
   End Else If @p_operacao = 'E' Begin
      -- Remove as entradas do menu para o endereço indicado
      delete siw_menu_endereco where sq_pessoa_endereco = @p_chave;

      -- Exclui registro
      delete co_pessoa_endereco where sq_pessoa_endereco = @p_chave
   End
end 
