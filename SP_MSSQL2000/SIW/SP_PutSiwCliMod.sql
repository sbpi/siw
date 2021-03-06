SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutSiwCliMod
   (@p_operacao          varchar(1),
    @p_modulo            int        = null,
    @p_pessoa            int        = null
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into siw_cliente_modulo ( sq_pessoa, sq_modulo ) values ( @p_pessoa, @p_modulo)
      
      -- Gera as opções de menu do módulo para o cliente, em todos os seus endereços
      exec SG_GeraCliMod @p_pessoa, @p_modulo
      
   End Else If @p_operacao = 'E' Begin
      -- Exclui as permissões de vínculos ligados ao módulo
      delete sg_perfil_menu
      where sq_menu   in (select sq_menu
                            from siw_menu w
                           where w.sq_pessoa = @p_pessoa
                             and w.sq_modulo = @p_modulo
                         )

      -- Exclui as permissões de usuários ligados ao módulo
      delete sg_pessoa_menu
      where sq_menu   in (select sq_menu
                            from siw_menu w
                           where w.sq_pessoa = @p_pessoa
                             and w.sq_modulo = @p_modulo
                         )

      -- Exclui as permissões a trâmites dos serviços ligados ao módulo
      delete sg_tramite_pessoa
      where sq_siw_tramite   in (select x.sq_siw_tramite
                                  from siw_menu w, siw_tramite x
                                 where w.sq_menu   = x.sq_menu
                                   and w.sq_pessoa = @p_pessoa
                                   and w.sq_modulo = @p_modulo
                                )

      -- Exclui os trâmites dos serviços ligados ao módulo
      delete siw_tramite
      where sq_menu   in (select sq_menu
                            from siw_menu w
                           where w.sq_pessoa = @p_pessoa
                             and w.sq_modulo = @p_modulo
                         )

      -- Exclui as opções do menu nos endereços do cliente
      delete siw_menu_endereco
      where sq_menu   in (select sq_menu
                            from siw_menu w
                           where w.sq_pessoa = @p_pessoa
                             and w.sq_modulo = @p_modulo
                         )

      -- Exclui as opções do menu do cliente
      delete siw_menu
      where sq_pessoa = @p_pessoa
        and sq_modulo = @p_modulo

      -- Exclui registro na tabela de módulos contratados pelo cliente
      delete siw_cliente_modulo
      where sq_pessoa = @p_pessoa
        and sq_modulo = @p_modulo
   End
end


GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

