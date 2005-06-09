create or replace procedure SP_GetMenuOrder
   (p_cliente   in  number,
    p_sq_menu   in  number default null,
    p_result    out sys_refcursor
   ) is
begin
   -- Recupera o n�mero de ordem das outras op��es irm�s � informada
   If p_sq_menu is null Then
      open p_result for
         select a.sq_menu, a.ultimo_nivel, a.acesso_geral, a.ordem, a.nome from siw_menu a where a.sq_menu_pai is null and a.sq_pessoa = p_cliente order by a.ordem;
   Else
      open p_result for
         select a.sq_menu, a.ultimo_nivel, a.acesso_geral, a.ordem, a.nome from siw_menu a where a.sq_menu_pai = p_sq_menu and a.sq_pessoa = p_cliente order by a.ordem;
   End If;
end SP_GetMenuOrder;
/

