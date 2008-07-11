create or replace function SP_GetUserList
   (p_cliente          numeric,
    p_localizacao      numeric,
    p_lotacao          numeric,
    p_endereco	       numeric,
    p_gestor_seguranca varchar,
    p_gestor_sistema   varchar,
    p_nome             varchar,
    p_modulo           numeric,
    p_uf               varchar,
    p_interno          varchar,
    p_ativo            varchar,
    p_contratado       varchar,
    p_visao_especial   varchar,
    p_dirigente	       varchar,
    p_result      refcursor
   ) returns refcursor as $$
begin
   open p_result for 
     select a.sq_pessoa, a.username, a.gestor_seguranca, a.gestor_sistema, a.ativo, a.email,
            a.tipo_autenticacao,
            case a.tipo_autenticacao when 'B' then 'BD' when 'A' then 'MS-AD' else 'O-LDAP' end as nm_tipo_autenticacao,
            b.nome_resumido, b.nome, b.nome_indice, b.nome_resumido_ind, 
            c.sigla as lotacao, c.sq_unidade, c.codigo, 
            d.nome as localizacao, d.sq_localizacao, d.ramal, 
            e.nome as vinculo, e.contratado,
            f.logradouro, g.nome as nm_cidade, g.co_uf,
            coalesce(h.qtd,0) as qtd_modulo,
            coalesce(i.qtd,0) as qtd_visao,
            coalesce(j.qtd,0) as qtd_dirigente,
            coalesce(l.qtd,0) as qtd_tramite
       from sg_autenticacao                        a 
            left outer     join eo_unidade         c on (a.sq_unidade         = c.sq_unidade)
              left outer   join co_pessoa_endereco f on (c.sq_pessoa_endereco = f.sq_pessoa_endereco)
                left outer join co_cidade          g on (f.sq_cidade          = g.sq_cidade)
            left outer     join eo_localizacao     d on (a.sq_localizacao     = d.sq_localizacao)
            inner          join co_pessoa          b on (a.sq_pessoa          = b.sq_pessoa)
              left outer   join co_tipo_vinculo    e on (b.sq_tipo_vinculo    = e.sq_tipo_vinculo)
            left outer     join (select x.sq_pessoa, count(*) as qtd 
                                   from sg_pessoa_modulo x 
                                  where x.cliente = p_cliente
                                 group by x.sq_pessoa
                                )                  h on (a.sq_pessoa          = h.sq_pessoa)
            left outer     join (select x.sq_pessoa, count(*) as qtd 
                                   from siw_pessoa_cc x 
                                 group by x.sq_pessoa
                                )                  i on (a.sq_pessoa          = i.sq_pessoa)
            left outer     join (select x.sq_pessoa, count(*) as qtd 
                                   from eo_unidade_resp x 
                                  where x.fim is null
                                 group by x.sq_pessoa
                                )                  j on (a.sq_pessoa          = j.sq_pessoa)
            left outer     join (select x.sq_pessoa, count(*) as qtd 
                                   from sg_tramite_pessoa x 
                                 group by x.sq_pessoa
                                )                  l on (a.sq_pessoa          = l.sq_pessoa)                                
      where a.cliente           = p_cliente
        and (p_ativo            is null or (p_ativo            is not null and a.ativo              = p_ativo))
        and (p_contratado       is null or (p_contratado       is not null and e.contratado         = p_contratado))
        and (p_localizacao      is null or (p_localizacao      is not null and d.sq_localizacao     = p_localizacao))
        and (p_lotacao          is null or (p_lotacao          is not null and c.sq_unidade         = p_lotacao))
        and (p_endereco         is null or (p_endereco         is not null and c.sq_pessoa_endereco = p_endereco))
        and (p_gestor_seguranca is null or (p_gestor_seguranca is not null and a.gestor_seguranca   = p_gestor_seguranca))
        and (p_gestor_sistema   is null or (p_gestor_sistema   is not null and a.gestor_sistema     = p_gestor_sistema)) 
        and (p_nome             is null or (p_nome             is not null and (b.nome_indice like '%'||acentos(p_nome)||'%' or
                                                                                b.nome_resumido_ind like '%'||acentos(p_nome)||'%'
                                                                               )
                                           )
            )
        and (p_uf               is null or (p_uf               is not null and g.co_uf              = p_uf))
        and (p_interno          is null or (p_interno          is not null and e.interno            = p_interno))
        and (p_modulo           is null or ((p_modulo          = 'S'       and 0                    < coalesce(h.qtd,0)) or
                                            (p_modulo          = 'N'       and 0                    = coalesce(h.qtd,0))
                                           )
            )
        and (p_visao_especial   is null or ((p_visao_especial  = 'S'       and 0                    < coalesce(i.qtd,0)) or
                                            (p_visao_especial  = 'N'       and 0                    = coalesce(i.qtd,0))
                                           )
            )
        and (p_dirigente        is null or ((p_dirigente       = 'S'       and 0                    < coalesce(j.qtd,0)) or
                                            (p_dirigente       = 'N'       and 0                    = coalesce(j.qtd,0))
                                           )
            )            
        order by b.nome;
   return p_result;
end; $$ language 'plpgsql' volatile;