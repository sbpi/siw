SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO



create procedure dbo.SP_PutSgPesMen
   (@p_operacao            varchar(1),
    @p_Pessoa              int        = null,
    @p_Menu                int        = null,
    @p_Endereco            int        = null
   ) as

begin

   Declare @w_existe   int
   Declare @w_menu     int

   Begin Transaction
   If @p_operacao = 'I' Begin
      -- Insere registro em SG_PESSOA_MENU, para cada endereço da organização
      insert into sg_pessoa_menu (sq_pessoa, sq_menu, sq_pessoa_endereco)
        (select @p_pessoa, a.sq_menu, b.sq_pessoa_endereco 
           from siw_menu a, siw_menu_endereco b 
          where a.sq_menu = b.sq_menu 
            and 0         = (select count(*) from sg_pessoa_menu where sq_pessoa = @p_pessoa and sq_menu=a.sq_menu and sq_pessoa_endereco=b.sq_pessoa_endereco)
            and a.sq_menu in     (select * from SIW.SP_fPutSgPerMen(@p_menu))
         )
   End Else If @p_operacao = 'E' Begin

      Declare c_permissao cursor for
         select * from SIW.SP_fPutSgPerMen(@p_menu)

      -- Para todas as opções superiores à informada, executa o bloco abaixo
      Open c_permissao

      Fetch next from c_permissao into @w_menu

      While @@Fetch_Status = 0
      Begin
         -- Verifica se a opção a ser excluída tem opções subordinadas a ela.
         -- Exclui apenas se não tiver, para evitar erro.
         select @w_existe = count(*)
           from siw_menu a, siw_menu_endereco b, sg_pessoa_menu c 
          where a.sq_menu            = b.sq_menu 
            and b.sq_menu            = c.sq_menu 
            and b.sq_pessoa_endereco = c.sq_pessoa_endereco 
            and c.sq_pessoa          = @p_pessoa
            and b.sq_pessoa_endereco = @p_endereco
            and a.sq_menu            <> @w_menu
            and a.sq_menu            in (select * from SIW.SP_fGetMenuList (@w_menu))
         
         If @w_existe = 0 Begin
            delete sg_pessoa_menu
             where sq_pessoa          = @p_pessoa
               and sq_menu            = @w_menu
               and sq_pessoa_endereco = @p_endereco
            
         End
         Fetch next from c_permissao into @w_menu
      End
      Close c_permissao
      Deallocate c_permissao
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

