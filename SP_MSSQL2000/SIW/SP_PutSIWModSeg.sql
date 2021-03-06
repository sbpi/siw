SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutSIWModSeg
   (@p_operacao                 varchar(1),
    @p_objetivo_especifico      varchar(4000) = null,
    @p_sq_modulo                int           = null,
    @p_sq_segmento              varchar(1)    = null,
    @p_comercializar            varchar(1)    = null,
    @p_ativo                    varchar(1)    = null
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into siw_mod_seg (objetivo_especif, sq_modulo, sq_segmento, comercializar, ativo)
      values (
               rtrim(@p_objetivo_especifico),
               @p_sq_modulo,
               @p_sq_segmento,
               @p_comercializar,
               @p_ativo
              )
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update siw_mod_seg set
         objetivo_especif    = rtrim(@p_objetivo_especifico),
         comercializar       = @p_comercializar,
         ativo               = @p_ativo
      where sq_modulo   = @p_sq_modulo
        and sq_segmento = @p_sq_segmento
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
       delete siw_mod_seg 
        where sq_modulo   = @p_sq_modulo
          and sq_segmento = @p_sq_segmento
   End
end 





GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

