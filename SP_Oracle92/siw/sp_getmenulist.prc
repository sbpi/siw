create or replace procedure SP_GetMenuList
   (p_cliente   in  number,
    p_operacao  in  varchar2,
    p_chave     in  number default null,
    p_result    out sys_refcursor
   ) is
begin
   If upper(p_operacao) = 'X' Then
      -- Recupera os links vinculados a serviços
      open p_result for
        select a.sq_menu,
               case when a.sq_modulo is null then a.nome else a.nome||' ('||b.nome||')' end nome,
               a.acesso_geral, a.ultimo_nivel, a.tramite
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = p_cliente
           and a.tramite   = 'S'
        order by acentos(a.nome);
   ElsIf upper(p_operacao) <> 'I' and upper(p_operacao) <> 'H' Then
      -- Se for alteração, evita a exibição do próprio registro e dos seus subordinados
      open p_result for
        select a.sq_menu,
               case when a.sq_modulo is null then a.nome else a.nome||' ('||b.nome||')' end nome 
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = p_cliente
           and a.sq_menu not in (select a.sq_menu 
                                   from siw_menu a 
                                  where a.sq_pessoa   = p_cliente
                                 start with a.sq_menu = p_chave
                                 connect by prior a.sq_menu = a.sq_menu_pai 
                                ) 
        order by acentos(a.nome);
   Else
      -- Recupera os links existentes para o cliente informado
      open p_result for
        select a.sq_menu,
               case when a.sq_modulo is null then a.nome else a.nome||' ('||b.nome||')' end nome,
               a.acesso_geral, a.ultimo_nivel, a.tramite
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = p_cliente
        order by acentos(a.nome);
    End If;
end SP_GetMenuList;
/

