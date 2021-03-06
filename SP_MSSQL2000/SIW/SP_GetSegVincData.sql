SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetSegVincData
   (@p_sigla       varchar(50)=null,
    @p_sq_segmento int
   ) as
begin
   If @p_sigla = 'SEGMENTOVINC' Begin
      -- Recupera os dados do vículo do segmento  escolhido
         select sq_seg_vinculo, b.sq_tipo_pessoa, b.nome nm_tipo_pessoa, a.nome nome_pessoa,
                a.ativo, a.padrao, a.sq_tipo_pessoa sq_tipo_pessoa, a.interno, a.contratado, a.ordem
           from dm_seg_vinculo a, 
                co_tipo_pessoa      b
          where a.sq_tipo_pessoa = b.sq_tipo_pessoa
            and a.sq_segmento = @p_sq_segmento
   End Else If @p_sigla = 'SEGMENTOMENU' Begin
      -- Recupera os dados do menu do segmento escolhido
         select a.nome nome, b.objetivo_especif objetivo, c.nome nm_modulo, a.sq_modulo sq_modulo,
                a.ativo ativo, b.comercializar
           from dm_segmento_menu     a
                  left outer join siw_mod_seg b on (a.sq_segmento = b.sq_segmento and a.sq_modulo = b.sq_modulo) 
                  left outer join siw_modulo  c on (b.sq_modulo = c.sq_modulo)
          where a.sq_segmento = @p_sq_segmento
   End Else If @p_sigla = 'SEGMENTOMOD' Begin
      -- Recupera os dados dos módulos do segmento escolhido
         select b.sq_modulo, b.nome nm_modulo, a.objetivo_especif,
                a.ativo, a.comercializar
           from siw_mod_seg a left outer join siw_modulo b on (a.sq_modulo = b.sq_modulo) 
          where a.sq_segmento = @p_sq_segmento
   End 
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

