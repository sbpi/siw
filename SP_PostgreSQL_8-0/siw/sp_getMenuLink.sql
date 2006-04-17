create or replace function SP_GetMenuLink
   (p_cliente   numeric,
    p_endereco  numeric,
    p_modulo	numeric,
    p_restricao varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os links permitidos ao usuário informado
   If p_restricao is not null Then
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_menu, a.sq_menu_pai, a.nome, a.link, a.ordem, a.p1, a.p2, a.p3, a.p4, a.sigla, a.imagem,
                   a.descentralizado, a.ativo, a.externo, a.tramite, a.ultimo_nivel, a.sq_modulo, 
                   coalesce(b.Filho,0) as Filho
              from siw_menu a
                    left outer join
                     (select sq_menu_pai,count(*) as Filho from siw_menu x group by sq_menu_pai) b
                    on (a.sq_menu = b.sq_menu_pai) 
             where a.sq_menu_pai      is null
               and (p_endereco        is null or
                    (p_endereco       is not null and
                     (a.descentralizado='N' or
                      a.sq_menu        in (select sq_menu from siw_menu_endereco where ativo = 'S' and sq_pessoa_endereco = p_endereco)
                     )
                    )
                   )
               and (p_modulo          is null or (p_modulo is not null and a.sq_modulo = p_modulo))
               and a.sq_pessoa        = p_cliente
            order by 5,3;
      Else
         open p_result for
            select a.sq_menu, a.sq_menu_pai, a.nome, a.link, a.ordem, a.p1, a.p2, a.p3, a.p4, a.sigla, a.imagem,
                   a.descentralizado, a.ativo, a.externo, a.tramite, a.ultimo_nivel, a.sq_modulo, 
                   coalesce(b.Filho,0) as Filho
              from siw_menu a
                    left outer join
                     (select sq_menu_pai,count(*) as Filho from siw_menu x group by sq_menu_pai) b
                    on (a.sq_menu = b.sq_menu_pai) 
             where (p_endereco        is null or
                    (p_endereco       is not null and
                     (a.descentralizado='N' or
                      a.sq_menu        in (select sq_menu from siw_menu_endereco where ativo = 'S' and sq_pessoa_endereco = p_endereco)
                     )
                    )
                   )
               and a.sq_pessoa        = p_cliente
               and (p_modulo          is null or (p_modulo is not null and a.sq_modulo = p_modulo))
               and a.sq_menu_pai      = p_restricao
            order by 5,3;
      End If;
    End If;
    return p_result;
end; $$ language 'plpgsql' volatile;