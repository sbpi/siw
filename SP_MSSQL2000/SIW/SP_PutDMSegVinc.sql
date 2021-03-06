SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutDMSegVinc
   (@p_operacao                 varchar(1),
    @p_chave                    int         = null,
    @p_sq_segmento              int         = null,
    @p_sq_tipo_pessoa           int         = null,
    @p_nome                     varchar(20) = null,
    @p_padrao                   varchar(1)  = null,
    @p_ativo                    varchar(1)  = null,
    @p_interno                  varchar(1)  = null,
    @p_contratado               varchar(1)  = null,
    @p_ordem                    int         = null
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into dm_seg_vinculo 
         (sq_segmento,   sq_tipo_pessoa, nome,           padrao, 
          ativo,         interno,        contratado,     ordem
         )
      values (
               @p_sq_segmento,
               @p_sq_tipo_pessoa,
               rtrim(@p_nome),
               @p_padrao,
               @p_ativo,
               @p_interno,
               @p_contratado,
               @p_ordem
          )
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update dm_seg_vinculo set
         sq_tipo_pessoa  = @p_sq_tipo_pessoa,
         nome            = rtrim(@p_nome),
         padrao          = @p_padrao,
         ativo           = @p_ativo,
         interno         = @p_interno,
         contratado      = @p_contratado,
         ordem           = @p_ordem
      where sq_seg_vinculo = @p_chave
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
       delete dm_seg_vinculo where sq_seg_vinculo = @p_chave
   End
end 





GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

