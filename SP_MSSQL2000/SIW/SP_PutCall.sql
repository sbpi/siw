SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutCall
   (@p_Operacao  varchar(1),
    @p_chave     int,
    @p_destino   int           = null,
    @p_sq_cc     int           = null,
    @p_contato   varchar(60)   = null,
    @p_assunto   varchar(1000) = null,
    @p_pessoa    int           = null,
    @p_fax       varchar(1)    = null,
    @p_trabalho  varchar(1)    = null
   ) as
begin
   If @p_Operacao = 'I' Begin
      -- Atualiza a ligação
      update tt_ligacao set
         sq_cc               = @p_sq_cc,
         outra_parte_cont    = ltrim(rtrim(@p_contato)),
         assunto             = ltrim(rtrim(@p_assunto)),
         sq_usuario_central  = (select sq_usuario_central from tt_usuario where usuario = @p_pessoa),
         fax                 = @p_fax,
         trabalho            = @p_trabalho
      where sq_ligacao       = @p_chave
   End Else If @p_Operacao = 'A' Begin
      -- insere o log de transferência
      insert into tt_ligacao_log 
             (sq_ligacao, data,     usuario_origem,       usuario_destino,      observacao)
      (select @p_chave, getdate(), a.sq_usuario_central, b.sq_usuario_central, ltrim(rtrim(@p_assunto))
         from tt_usuario a,
              tt_usuario b
        where a.usuario = @p_pessoa
          and b.usuario = @p_destino
      )

      -- coloca a ligação para o destinatário da transferência
      update tt_ligacao set
         sq_usuario_central  = (select sq_usuario_central from tt_usuario where usuario = @p_destino)
      where sq_ligacao       = @p_chave
   End
End



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

