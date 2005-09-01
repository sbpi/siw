create or replace procedure SP_GetPersonData
   (p_cliente   in number,
    p_sq_pessoa in number   default null,
    p_cpf       in varchar2 default null,
    p_cnpj      in varchar2 default null,
    p_result   out siw.sys_refcursor
   ) is
begin
   open p_result for
     select a.*,
           b.username, b.ativo, b.sq_unidade, b.sq_localizacao,
           b.gestor_seguranca, b.gestor_sistema, Nvl(b.email,i.email) email,
           c.sq_usuario_central, c.sq_central_fone, c.codigo codigo_central, c.codigo codigo_central,
           d.sq_tipo_vinculo, d.nome nome_vinculo, d.interno, d.ativo vinculo_ativo,
           e.nome unidade, e.sigla, e.email email_unidade,
           f.nome localizacao, f.fax, f.telefone, f.ramal, f.telefone2,
           g.logradouro endereco, (h.nome||'-'||h.co_uf) Cidade, h.ddd,
           Nvl(j.cpf, b.username) cpf,
           k.cnpj,
           l.sq_tipo_pessoa, l.nome nm_tipo_pessoa
       from co_pessoa                           a,
            sg_autenticacao    b,
            tt_usuario         c,
            co_tipo_vinculo    d,
            co_tipo_pessoa     l,
            eo_unidade         e,
            eo_localizacao     f,
            (select w.sq_pessoa, w.sq_pessoa_endereco, w.sq_cidade, w.logradouro
                                from co_pessoa_endereco          w,
                                     co_tipo_endereco x,
                                     co_pessoa        y,
                                     co_tipo_pessoa   z
                               where (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                 and (w.sq_pessoa          = y.sq_pessoa)
                                 and (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                                 and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                 and x.nome               = 'Comercial'
                                 and x.ativo              = 'S'
                                 and w.padrao             = 'S'
                             )                  g,
            co_cidade          h,
            (select w.sq_pessoa, w.logradouro email
                                from co_pessoa_endereco            w,
                                     co_tipo_endereco x,
                                     co_pessoa        y,
                                     co_tipo_pessoa   z
                               where (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                 and (w.sq_pessoa          = y.sq_pessoa)
                                 and (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                                 and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                 and x.email              = 'S'
                                 and x.ativo              = 'S'
                                 and w.padrao             = 'S'
                             )                  i,
            co_pessoa_fisica    j,
            co_pessoa_juridica  k
      where (a.sq_pessoa_pai = g.sq_pessoa (+))
        and (a.sq_pessoa = b.sq_pessoa (+))
        and (a.sq_pessoa = c.usuario (+))
        and (a.sq_tipo_vinculo = d.sq_tipo_vinculo (+))
        and (a.sq_tipo_pessoa  = l.sq_tipo_pessoa (+))
        and (b.sq_unidade = e.sq_unidade (+))
        and (b.sq_localizacao = f.sq_localizacao (+))
        and (g.sq_cidade     = h.sq_cidade (+))
        and (a.sq_pessoa = i.sq_pessoa (+))
        and (a.sq_pessoa = j.sq_pessoa (+))
        and (a.sq_pessoa = k.sq_pessoa (+))
        and a.sq_pessoa_pai = p_cliente
        and (p_sq_pessoa    is null or (p_sq_pessoa  is not null and a.sq_pessoa  = p_sq_pessoa))
        and (p_cpf          is null or (p_cpf        is not null and (j.cpf        = p_cpf or b.username = p_cpf)))
        and (p_cnpj         is null or (p_cnpj       is not null and k.cnpj       = p_cnpj));
end SP_GetPersonData;
/
