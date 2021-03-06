alter  procedure Sp_PutSiwMenEnd
   (@operacao              varchar(1),
    @p_menu                int       ,
    @p_endereco            int   = null
   ) as
begin
   If @operacao = 'I' begin
      -- Insere registro em SIW_MENU_ENDERECO
      insert into siw_menu_endereco(sq_menu, sq_pessoa_endereco) values (@p_menu, @p_endereco);
   end Else if @operacao = 'E' begin
      -- Remove a opção de todos os endereços da organização
      delete siw_menu_endereco where sq_menu = @p_menu;
   End 
     
end