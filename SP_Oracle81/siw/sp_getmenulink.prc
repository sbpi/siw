create or replace procedure SP_GetMenuLink
   (p_cliente   in  number,
    p_endereco  in number    default null,
    p_modulo    in number    default null,
    p_restricao in  varchar2 default null,
    p_result    out siw.sys_refcursor
   ) is
begin
   -- Recupera os links permitidos ao usuário informado
   If p_restricao is not null Then
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_menu, a.sq_menu_pai, a.nome, a.link, a.ordem, a.p1, a.p2, a.p3, a.p4, a.sigla, a.imagem,
                   a.descentralizado, a.ativo, a.externo, a.tramite, a.ultimo_nivel, a.sq_modulo,
                   Nvl(b.Filho,0) Filho
              from siw_menu a,
                   (select sq_menu_pai,count(*) Filho from siw_menu x group by sq_menu_pai) b
             where (a.sq_menu = b.sq_menu_pai (+))
               and a.sq_menu_pai      is null
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
                   Nvl(b.Filho,0) Filho
              from siw_menu a,
                   (select sq_menu_pai,count(*) Filho from siw_menu x group by sq_menu_pai) b
             where (a.sq_menu = b.sq_menu_pai (+))
               and (p_endereco        is null or
                    (p_endereco       is not null and
                     (a.descentralizado='N' or
                      a.sq_menu        in (select sq_menu from siw_menu_endereco where ativo = 'S' and sq_pessoa_endereco = p_endereco)
                     )
                    )
                   )
               and (p_modulo          is null or (p_modulo is not null and a.sq_modulo = p_modulo))
               and a.sq_pessoa        = p_cliente
               and a.sq_menu_pai      = p_restricao
            order by 5,3;
      End If;
    End If;
end SP_GetMenuLink;
/
