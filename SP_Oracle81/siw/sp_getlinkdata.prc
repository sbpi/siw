create or replace procedure SP_GetLinkData
   (p_cliente   in  number,
    p_sg        in  varchar2,
    p_result    out siw.sys_refcursor
   ) is
begin
   -- Recupera os dados do link informado e se ele tem links vinculados
   open p_result for
      select a.sq_modulo, a.sq_menu,a.nome,a.link,a.ordem,a.p1,a.p2,a.p3,a.p4,
             a.sigla, c.sigla sg_pai, a.imagem, Nvl(a.target,'content') target,
             a.ultimo_nivel, count(b.sq_menu) Filho
        from siw_menu a,
             siw_menu b,
             siw_menu c
       where (a.sq_menu     = b.sq_menu_pai (+))
         and (a.sq_menu_pai = c.sq_menu (+))
         and a.sigla         = upper(trim(p_sg))
         and a.ativo         = 'S'
         and a.sq_pessoa     = p_cliente
      group by a.sq_modulo, a.sq_menu,a.nome,a.link,a.ordem,a.p1,a.p2,a.p3,a.p4,a.sigla,c.sigla,a.imagem,a.target, a.ultimo_nivel;
end SP_GetLinkData;
/

