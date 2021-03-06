SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO



create procedure dbo.SP_PutSIWCliConf
   (@p_chave                   int,
    @p_tamanho_minimo_senha    int          = null,
    @p_tamanho_maximo_senha    int          = null,
    @p_maximo_tentativas       int          = null,
    @p_dias_vigencia_senha     int          = null,
    @p_dias_aviso_expiracao    int          = null,
    @p_smtp_server             varchar(60)  = null, 
    @p_siw_email_nome          varchar(60)  = null,
    @p_siw_email_conta         varchar(60)  = null,
    @p_siw_email_senha         varchar(60)  = null,
    @p_logo                    varchar(60)  = null,
    @p_logo1                   varchar(60)  = null,
    @p_fundo                   varchar(60)  = null,
    @p_tipo                    varchar(50)  = null
   ) as
begin
   If @p_Tipo = 'AUTENTICACAO' Begin
      -- Altera dados relativos à autenticação de usuários
      update siw_cliente set
         tamanho_min_senha    = @p_tamanho_minimo_senha,
         tamanho_max_senha    = @p_tamanho_maximo_senha,
         maximo_tentativas    = @p_maximo_tentativas,
         dias_vig_senha       = @p_dias_vigencia_senha,
         dias_aviso_expir     = @p_dias_aviso_expiracao
      where sq_pessoa         = @p_chave
   End Else If @p_Tipo = 'SERVIDOR' Begin
      -- Altera dados relativos ao serviço de SMTP e imagens do cliente
      update siw_cliente set
         smtp_server          = @p_smtp_server,
         siw_email_nome       = @p_siw_email_nome,
         siw_email_conta      = @p_siw_email_conta,
         siw_email_senha      = IsNull(@p_siw_email_senha, siw_email_senha),
         logo                 = IsNull(@p_logo, logo),
         logo1                = IsNull(@p_logo1, logo1),
         fundo                = IsNull(@p_fundo, fundo)
      where sq_pessoa         = @p_chave
   End
end 






GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

