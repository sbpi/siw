create or replace function SP_GetPersonData
   (p_cliente   numeric,
    p_sq_pessoa numeric,
    p_cpf       varchar,
    p_cnpj      varchar,
    p_result   refcursor
   ) returns refcursor as $$
begin
   open p_result for 
     select a.*, 
           b.username, b.ativo, b.sq_unidade, b.sq_localizacao, b.tipo_autenticacao,
           b.gestor_seguranca, b.gestor_sistema, Nvl(b.email,i.email) as email,
           case b.tipo_autenticacao when 'B' then 'BD' when 'A' then 'MS-AD' else 'O-LDAP' end as nm_tipo_autenticacao,
           c.sq_usuario_central, c.sq_central_fone, c.codigo as codigo_central, c.codigo as codigo_central,
           d.sq_tipo_vinculo, d.nome as nome_vinculo, d.interno, d.ativo as vinculo_ativo,
           e.nome as unidade, e.sigla, e.email as email_unidade,
           f.nome as localizacao, f.fax, f.telefone, f.ramal, f.telefone2,
           g.logradouro as endereco, (h.nome||'-'||h.co_uf) as Cidade, h.ddd,
           coalesce(j.cpf, b.username) as cpf,
           k.cnpj,
           l.sq_tipo_pessoa, l.nome as nm_tipo_pessoa
       from co_pessoa                           a
            left outer join  sg_autenticacao    b on (a.sq_pessoa = b.sq_pessoa)
            left outer join  tt_usuario         c on (a.sq_pessoa = c.usuario)
            left outer join  co_tipo_vinculo    d on (a.sq_tipo_vinculo = d.sq_tipo_vinculo)
            left outer join  co_tipo_pessoa     l on (a.sq_tipo_pessoa  = l.sq_tipo_pessoa)
            left outer join  eo_unidade         e on (b.sq_unidade = e.sq_unidade)
            left outer join  eo_localizacao     f on (b.sq_localizacao = f.sq_localizacao)
            left outer join  (select w.sq_pessoa, w.sq_pessoa_endereco, w.sq_cidade, w.logradouro
                                from co_pessoa_endereco          w
                                     inner join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                     inner join co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                     inner join co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                               where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                 and x.nome               = 'Comercial'
                                 and x.ativo              = 'S'
                                 and w.padrao             = 'S'
                             )                  g on (a.sq_pessoa_pai = g.sq_pessoa)
            left outer join  co_cidade          h on (g.sq_cidade     = h.sq_cidade)
            left outer join  (select w.sq_pessoa, w.logradouro as email
                                from co_pessoa_endereco            w
                                     inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                     inner   join co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                       inner join co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                               where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                 and x.email              = 'S'
                                 and x.ativo              = 'S'
                                 and w.padrao             = 'S'
                             )                  i on (a.sq_pessoa = i.sq_pessoa)
            left outer join co_pessoa_fisica    j on (a.sq_pessoa = j.sq_pessoa)
            left outer join co_pessoa_juridica  k on (a.sq_pessoa = k.sq_pessoa)
      where a.sq_pessoa_pai = p_cliente
        and (p_sq_pessoa    is null or (p_sq_pessoa  is not null and a.sq_pessoa  = p_sq_pessoa))
        and (p_cpf          is null or (p_cpf        is not null and (j.cpf       = p_cpf or b.username = p_cpf)))
        and (p_cnpj         is null or (p_cnpj       is not null and k.cnpj       = p_cnpj));
   return p_result;
end; $$ language 'plpgsql' volatile;
