SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutAgreeType
   (@p_operacao                 varchar(1),
    @p_chave                    int         = null,
    @p_chave_pai                int         = null,
    @p_cliente                  int         = null,
    @p_nome                     varchar(60) = null,
    @p_sigla                    varchar(10) = null,
    @p_modalidade               varchar(1)  = null,
    @p_prazo_indeterm           varchar(1)  = null,
    @p_pessoa_juridica          varchar(1)  = null,
    @p_pessoa_fisica            varchar(1)  = null,
    @p_ativo                    varchar(1)  = null
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into ac_tipo_acordo (
              sq_tipo_acordo_pai,  cliente,         nome,            sigla, 
              modalidade,          prazo_indeterm,  pessoa_juridica, pessoa_fisica, 
              ativo)
      values (@p_chave_pai,
              @p_cliente,
              rtrim(@p_nome),
              rtrim(@p_sigla),
              @p_modalidade,                 
              @p_prazo_indeterm,
              @p_pessoa_juridica,
              @p_pessoa_fisica,
              @p_ativo
             )
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update ac_tipo_acordo set
         sq_tipo_acordo_pai   = @p_chave_pai,
         nome                 = rtrim(@p_nome),
         sigla                = rtrim(@p_sigla),
         modalidade           = @p_modalidade,
         prazo_indeterm       = @p_prazo_indeterm,
         pessoa_juridica      = @p_pessoa_juridica,
         pessoa_fisica        = @p_pessoa_fisica
      where sq_tipo_acordo    = @p_chave
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete ac_tipo_acordo where sq_tipo_acordo = @p_chave
   End Else If @p_operacao = 'T' Begin
      -- Ativa o registro
      update ac_tipo_acordo set ativo = 'S' where sq_tipo_acordo = @p_chave
   End Else If @p_operacao = 'D' Begin
      -- Desativa o registro
      update ac_tipo_acordo set ativo = 'N' where sq_tipo_acordo = @p_chave
   End
end


GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

