SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutSgTraPes
   (@p_operacao            varchar(1),
    @p_Pessoa              int        = null,
    @p_Tramite             int        = null,
    @p_Endereco            int        = null
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro em SG_TRAMITE_PESSOA, para cada endereço que conténha a opção
      insert into sg_tramite_pessoa (sq_pessoa, sq_siw_tramite, sq_pessoa_endereco)
        (select @p_pessoa sq_pessoa, c.sq_siw_tramite, b.sq_pessoa_endereco 
           from siw_menu a, siw_menu_endereco b, siw_tramite c
          where a.sq_menu        = b.sq_menu
            and a.sq_menu        = c.sq_menu
            and c.sq_siw_tramite = @p_Tramite
        )
   End Else If @p_operacao = 'E' Begin
      -- Remove a permissão
       delete sg_tramite_pessoa
        where sq_pessoa          = @p_pessoa
          and sq_siw_tramite     = @p_tramite
          and sq_pessoa_endereco = @p_endereco
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

