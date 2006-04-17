create or replace function SP_GetLinkDataHelp
   (p_cliente   numeric,
    p_modulo    numeric,
    p_sq_pessoa numeric,
    p_restricao varchar,
    p_result    refcursor
   ) returns refcursor as $$
declare
  l_pessoa numeric := 0;
begin
  -- Garante que a pessoa será igual a zero, caso não seja informada.
  if p_sq_pessoa is not null then l_pessoa = p_sq_pessoa; end if;

  -- Recupera os links permitidos ao usuário informado (pessoa > 0)  ou ao cliente informado (pessoa = 0)
  If p_restricao is not null Then
     If upper(p_restricao) = 'IS NULL' Then
        open p_result for
           select a.sq_menu,a.nome,a.link,a.ordem,a.p1,a.p2,a.p3,a.p4,a.sigla,a.imagem, a.externo, coalesce(a.target,'content') as target, a.ultimo_nivel, a.ativo, coalesce(b.filho,0) as Filho,
                  a.finalidade, a.tramite, a.como_funciona
             from siw_menu a
                    left outer join
                      (select sq_menu_pai,count(*) as Filho from siw_menu x where ativo = 'S' group by sq_menu_pai) b
                    on (a.sq_menu = b.sq_menu_pai)  
            where a.ativo            = 'S'
              and a.sq_menu_pai      is null
              and a.sq_pessoa        = p_cliente
              and a.sq_modulo        = p_modulo
              and (l_pessoa          <= 0 or (l_pessoa > 0 and marcado(a.sq_menu, l_pessoa, null, null, null) > 0))
           order by 4,2;
     Else
        open p_result for
           select a.sq_menu,a.nome,a.link,a.ordem,a.p1,a.p2,a.p3,a.p4,a.sigla,a.imagem, a.externo, coalesce (a.target,'content') as target, a.ultimo_nivel, a.ativo, coalesce(b.filho,0) as Filho,
                  a.finalidade, a.tramite, a.como_funciona
             from siw_menu a
                    left outer join
                      (select sq_menu_pai,count(*) as Filho from siw_menu x where ativo = 'S' group by sq_menu_pai) b
                    on (a.sq_menu = b.sq_menu_pai)  
            where a.ativo            = 'S'
              and a.sq_menu_pai      = p_restricao
              and a.sq_pessoa        = p_cliente
              and a.sq_modulo        = p_modulo
              and (l_pessoa          <= 0 or (l_pessoa > 0 and marcado(a.sq_menu, l_pessoa, null, null, null) > 0))
           order by 4,2;
     End If;
  End If;
  return p_result;
end; $$ language 'plpgsql' volatile;