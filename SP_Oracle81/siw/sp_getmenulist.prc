create or replace procedure SP_GetMenuList
   (p_cliente   in  number,
    p_operacao  in  varchar2,
    p_chave     in  number   default null,
    p_modulo    in  varchar2 default null,
    p_result    out siw.sys_refcursor
   ) is
begin
   If upper(p_operacao) = 'L' Then
      -- Recupera os links que referenciam rotinas do sistema
      open p_result for
        select a.sq_menu, a.nome, a.link, a.ativo,
               b.nome, 
               MontaOrdemMenu(a.sq_menu) or_menu,
               MontaNomeMenu(a.sq_menu)  nm_menu
          from siw_menu   a,
               siw_modulo b
         where a.sq_modulo = b.sq_modulo
           and a.sq_pessoa = p_cliente
           and a.externo   = 'N'
           and a.link      is not null;
   Elsif upper(p_operacao) = 'X' Then
      -- Recupera os links vinculados a serviços
      open p_result for
        select a.sq_menu,
               decode(a.sq_modulo,null,a.nome,decode(p_modulo,null,a.nome||' ('||b.nome||')',a.nome)) nome,
               a.acesso_geral, a.ultimo_nivel, a.tramite
          from siw_menu              a,
               siw_modulo b
         where (a.sq_modulo = b.sq_modulo)
           and a.sq_pessoa = p_cliente
           and a.tramite   = 'S'
           and b.sigla     = decode(p_modulo,null,b.sigla,p_modulo)
        order by acentos(a.nome);
   Elsif upper(p_operacao) = 'XVINC' Then
      -- Recupera os links vinculados a serviços
      open p_result for
        select a.sq_menu,
               decode(a.sq_modulo,null,a.nome,a.nome||' ('||b.nome||')') nome,
               a.acesso_geral, a.ultimo_nivel, a.tramite
          from siw_menu              a,
               siw_modulo b
         where (a.sq_modulo = b.sq_modulo)
           and a.sq_pessoa = p_cliente
           and a.tramite   = 'S'
           and a.sq_menu <> p_chave
        order by acentos(a.nome);        
   ElsIf upper(p_operacao) <> 'I' and upper(p_operacao) <> 'H' Then
      -- Se for alteração, evita a exibição do próprio registro e dos seus subordinados
      open p_result for
        select a.sq_menu,
               decode(a.sq_modulo,null,a.nome,a.nome||' ('||b.nome||')') nome
          from siw_menu              a,
               siw_modulo b
         where (a.sq_modulo = b.sq_modulo)
           and a.sq_pessoa = p_cliente
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
               decode(a.sq_modulo,null,a.nome,a.nome||' ('||b.nome||')') nome,
               a.acesso_geral, a.ultimo_nivel, a.tramite
          from siw_menu              a,
               siw_modulo b
         where (a.sq_modulo = b.sq_modulo)
           and a.sq_pessoa = p_cliente
        order by acentos(a.nome);
    End If;
end SP_GetMenuList;
/
