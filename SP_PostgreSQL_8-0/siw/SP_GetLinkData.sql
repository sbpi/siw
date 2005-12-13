create or replace function SP_GetLinkData
   (p_cliente   in  numeric,
    p_sg        in  varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do link informado e se ele tem links vinculados
   open p_result for 
      select a.sq_modulo, a.sq_menu,a.nome,a.link,a.ordem,a.p1,a.p2,a.p3,a.p4,
             a.sigla, c.sigla as sg_pai, a.imagem, coalesce(a.target,'content') as target, 
             a.ultimo_nivel, count(b.sq_menu) as Filho
        from siw_menu a
             left outer join siw_menu b on (a.sq_menu     = b.sq_menu_pai)
             left outer join siw_menu c on (a.sq_menu_pai = c.sq_menu)
       where a.sigla         = upper(trim(p_sg))
         and a.ativo         = 'S'
         and a.sq_pessoa     = p_cliente
      group by a.sq_modulo, a.sq_menu,a.nome,a.link,a.ordem,a.p1,a.p2,a.p3,a.p4,a.sigla,c.sigla,a.imagem,a.target, a.ultimo_nivel;
   return p_result;
end; $$ language plpgsql volatile;
