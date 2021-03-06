SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutSIWTramite
   (@p_operacao            varchar(1),
    @p_chave               int          = null,
    @p_chave_aux           int          = null,
    @p_nome                varchar(50)  = null,
    @p_ordem               int          = null,
    @p_sigla               varchar(2)   = null,
    @p_descricao           varchar(500) = null,
    @p_chefia_imediata     varchar(1)   = null,
    @p_ativo               varchar(1)   = null,
    @p_solicita_cc         varchar(1)   = null,
    @p_envia_mail          varchar(1)   = null
   ) as
begin

   Declare @w_chave int

   If @p_operacao = 'I' Begin
      -- Insere registro em SIW_MENU
      insert into siw_tramite 
         (sq_menu,        nome,               ordem,    sigla, 
          descricao,      chefia_imediata,    ativo,    solicita_cc,
          envia_mail)
      values 
         (@p_chave_aux,   @p_nome,            @p_ordem, upper(@p_sigla),
          @p_descricao,   @p_chefia_imediata, @p_ativo, @p_solicita_cc,
          @p_envia_mail
         )

      -- Recupera a próxima chave
      Set @w_Chave = @@IDENTITY
      
      -- Cria a opção do menu para todos os endereços da organização
      insert into siw_menu_endereco (sq_menu, sq_pessoa_endereco) 
        (select @w_chave, sq_pessoa_endereco 
           from co_pessoa_endereco a, co_tipo_endereco b 
          where a.sq_tipo_endereco = b.sq_tipo_endereco 
            and b.internet         = 'N' 
            and b.email            = 'N' 
            and sq_pessoa          = @p_chave_aux
        )
      
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update siw_tramite set
          nome             = rtrim(@p_nome),
          ordem            = @p_ordem,
          chefia_imediata  = @p_chefia_imediata,
          envia_mail       = @p_envia_mail,
          solicita_cc      = @p_solicita_cc,
          sigla            = upper(@p_sigla),
          descricao        = rtrim(@p_descricao),
          ativo            = @p_ativo
      where sq_siw_tramite = @p_chave
   End Else If @p_operacao = 'E' Begin
      -- Remove o trâmite do serviço
      delete siw_tramite where sq_siw_tramite = @p_chave
   End
   
   commit   
end 





GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

