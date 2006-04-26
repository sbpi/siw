create or replace function SP_GetMenuList
   (p_cliente   numeric,
    p_operacao  varchar,
    p_chave     numeric,
    p_result    refcursor
   ) returns refcursor as $$
begin
   If upper(p_operacao) = 'X' Then
      -- Recupera os links vinculados a serviços
      open p_result for
        select a.sq_menu,
               case when a.sq_modulo is null then a.nome else a.nome||' ('||b.nome||')' end as nome,
               a.acesso_geral, a.ultimo_nivel, a.tramite
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = p_cliente
           and a.tramite   = 'S'
        order by acentos(a.nome, null);
   ElsIf upper(p_operacao) <> 'I' and upper(p_operacao) <> 'H' Then
      -- Se for alteração, evita a exibição do próprio registro e dos seus subordinados
      open p_result for
        select a.sq_menu,
               case when a.sq_modulo is null then a.nome else a.nome||' ('||b.nome||')' end as nome 
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = p_cliente
           and a.sq_menu not in (select sq_menu from siw_menu_level(p_chave,0,'DOWN'))
        order by acentos(a.nome, null);
   Else
      -- Recupera os links existentes para o cliente informado
      open p_result for
        select a.sq_menu,
               case when a.sq_modulo is null then a.nome else a.nome||' ('||b.nome||')' end as nome,
               a.acesso_geral, a.ultimo_nivel, a.tramite
          from siw_menu              a
               inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
         where a.sq_pessoa = p_cliente
        order by acentos(a.nome, null);
    End If;
    return p_result;
end; $$ language 'plpgsql' volatile;
