SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO

create procedure dbo.SP_PutSiwPesCC
   (@p_operacao    varchar(1),
    @p_pessoa      int,
    @p_menu        int,
    @p_cc          int        = null
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro em SG_menu_pessoa, para cada endereço que conténha a opção
      insert into siw_pessoa_cc (sq_pessoa, sq_menu, sq_cc) values (@p_pessoa, @p_menu, @p_cc);
   End Else If @p_operacao = 'E' Begin
      -- Remove a permissão
       delete siw_pessoa_cc
        where sq_pessoa = @p_pessoa
          and sq_menu   = @p_menu
          and ((@p_cc    is null) or (@p_cc is not null and sq_cc = @p_cc));
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

