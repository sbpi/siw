SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutEOResp
   (@p_operacao                 varchar(1),
    @p_chave                    int      = null,
    @p_fim_substituto           datetime = null,
    @p_sq_pessoa_substituto     int      = null,
    @p_inicio_substituto        datetime = null,
    @p_fim_titular              datetime = null,
    @p_sq_pessoa                int      = null,
    @p_inicio_titular           datetime = null
   ) as
begin
   delete eo_unidade_resp where fim is null and sq_unidade = @p_chave
   If @p_operacao <> 'E' Begin
      If not @p_sq_pessoa_substituto is null Begin
         insert into eo_unidade_resp (fim, sq_unidade, sq_pessoa, tipo_respons, inicio)         
         values (
                     @p_fim_substituto,
                     @p_chave,
                     @p_sq_pessoa_substituto,
                     'S',
                     @p_inicio_substituto
         )
      End
      insert into eo_unidade_resp (fim, sq_unidade, sq_pessoa, tipo_respons, inicio)
      values (
               @p_fim_titular,
               @p_chave,
               @p_sq_pessoa,
               'T',
               @p_inicio_titular
        )
    End
end 





GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

