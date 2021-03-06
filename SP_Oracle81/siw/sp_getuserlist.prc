create or replace procedure SP_GetUserList
   (p_cliente     in number,
    p_localizacao in number   default null,
    p_lotacao     in number   default null,
    p_gestor      in varchar2 default null,
    p_nome        in varchar2 default null,
    p_modulo      in number   default null,
    p_uf          in varchar2 default null,
    p_ativo       in varchar2 default null,
    p_contratado  in varchar2 default null,
    p_result      out siw.sys_refcursor
   ) is
begin
   open p_result for
     select a.sq_pessoa sq_pessoa, a.username, a.gestor_seguranca, a.ativo,
            b.nome_resumido, b.nome nome, b.nome_indice, b.nome_resumido_ind,
            c.sigla lotacao, c.sq_unidade, c.codigo,
            d.nome localizacao, d.sq_localizacao, d.ramal,
            e.nome vinculo, e.contratado,
            f.logradouro, g.nome nm_cidade, g.co_uf
       from sg_autenticacao    a,
            eo_unidade         c,
            co_pessoa_endereco f,
            co_cidade          g,
            eo_localizacao     d,
            co_pessoa          b,
            co_tipo_vinculo    e
      where (a.sq_unidade         = c.sq_unidade (+))
        and (c.sq_pessoa_endereco = f.sq_pessoa_endereco (+))
        and (f.sq_cidade          = g.sq_cidade (+))
        and (a.sq_localizacao     = d.sq_localizacao (+))
        and (a.sq_pessoa          = b.sq_pessoa)
        and (b.sq_tipo_vinculo    = e.sq_tipo_vinculo (+))
        and a.cliente      = p_cliente
        and (p_ativo       is null or (p_ativo       is not null and a.ativo             = p_ativo))
        and (p_contratado  is null or (p_contratado  is not null and e.contratado        = p_contratado))
        and (p_localizacao is null or (p_localizacao is not null and d.sq_localizacao    = p_localizacao))
        and (p_lotacao     is null or (p_lotacao     is not null and c.sq_unidade        = p_lotacao))
        and (p_gestor      is null or (p_gestor      is not null and (a.gestor_sistema   = p_gestor or
                                                                      a.gestor_seguranca = p_gestor)))
        and (p_nome        is null or (p_nome        is not null and acentos(b.nome)     like '%'||acentos(p_nome)||'%'))
        and (p_uf          is null or (p_uf          is not null and g.co_uf             = p_uf));
end SP_GetUserList;
/
