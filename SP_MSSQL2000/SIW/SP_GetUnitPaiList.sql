SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_GetUnitPaiList
   (@p_operacao    varchar(10),
    @p_sq_pessoa   int,
    @p_sq_unidade  int=null
   ) as
begin
   If @p_operacao = 'A' Begin
   --Recupera a lista de unidades quem podem ser pai
      select a.sq_unidade, a.nome
        from eo_unidade a
       where sq_pessoa = @p_sq_pessoa
         and a.sq_unidade not in (select chave from SIW.SP_fGetUnitPaiList(@p_sq_unidade))
   End Else Begin
         select a.sq_unidade, a.nome
           from eo_unidade a, co_pessoa_endereco b 
          where a.sq_pessoa_endereco = b.sq_pessoa_endereco
            and b.sq_pessoa          = @p_sq_pessoa;
   End
end



GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

