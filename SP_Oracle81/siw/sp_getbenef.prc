create or replace procedure SP_GetBenef
   (p_cliente            in number,
    p_sq_pessoa          in number   default null,
    p_cpf                in varchar2 default null,
    p_cnpj               in varchar2 default null,
    p_nome               in varchar2 default null,
    p_tipo_pessoa        in number   default null,
    p_passaporte_numero  in varchar2 default null,
    p_sq_pais_passaporte in varchar2 default null,
    p_result      out siw.sys_refcursor
   ) is
begin
   open p_result for
     select a.sq_pessoa, a.nome nm_pessoa, a.nome_resumido, a.sq_pessoa_pai,
            a.cliente, a.fornecedor,
            c.sq_tipo_pessoa, c.nome nm_tipo_pessoa,
            d.sq_tipo_vinculo, d.nome nm_tipo_vinculo, d.interno, d.ativo vinculo_ativo,
            e.sq_pessoa_conta, e.sq_banco, e.sq_agencia, e.cd_agencia, e.operacao, e.numero nr_conta,
            e.nm_agencia, e.cd_banco, e.nm_banco,
            b.sq_pessoa_fax, b.nr_fax,
            f.sq_pessoa_telefone, f.ddd, f.nr_telefone,
            l.sq_pessoa_celular, l.nr_celular,
            g.sq_pessoa_endereco, g.logradouro, g.complemento, g.bairro, g.cep,
            h.sq_cidade, h.nome nm_cidade, h.co_uf, h.sq_pais,
            m.padrao pd_pais, m.nome nm_pais,
            i.email,
            Nvl(j.cpf,n.username) cpf, j.nascimento, j.rg_numero, j.rg_emissao, j.rg_emissor, j.passaporte_numero,
            j.sq_pais_passaporte, j.sexo,
            k.cnpj, k.inscricao_estadual,
            o.nome nm_pais_passaporte,
            decode(sexo,'F','Feminino','Masculino') nm_sexo,
            p.sigla||'/'||q.sigla nm_unidade_benef
       from co_pessoa          a,
            co_tipo_pessoa     c,
            co_tipo_vinculo    d,
            (select w.sq_pessoa_conta, sq_pessoa, x.sq_banco, w.sq_agencia,
                    w.operacao, w.numero, x.codigo cd_agencia, x.nome nm_agencia,
                    y.codigo cd_banco, y.nome nm_banco
               from co_pessoa_conta w,
                    co_agencia      x,
                    co_banco        y
              where (w.sq_agencia = x.sq_agencia)
                and (x.sq_banco   = y.sq_banco)
                and w.ativo       = 'S'
                and w.padrao      = 'S'
                and x.ativo       = 'S'
            )                  e,
            (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_fax, w.numero nr_fax
               from co_pessoa_telefone w,
                    co_tipo_telefone   x,
                    co_pessoa          z
              where (w.sq_tipo_telefone = x.sq_tipo_telefone)
                and (w.sq_pessoa        = z.sq_pessoa)
                and x.sq_tipo_pessoa    = z.sq_tipo_pessoa
                and x.nome              = 'Fax'
                and x.ativo             = 'S'
                and w.padrao            = 'S'
            )                  b,
            (select w.sq_pessoa, w.sq_pessoa_telefone, w.ddd, w.numero nr_telefone
               from co_pessoa_telefone w,
                    co_tipo_telefone   x,
                    co_pessoa          z
              where (w.sq_tipo_telefone = x.sq_tipo_telefone)
                and (w.sq_pessoa        = z.sq_pessoa)
                and x.sq_tipo_pessoa    = z.sq_tipo_pessoa
                and x.nome              = 'Comercial'
                and x.ativo             = 'S'
                 and w.padrao           = 'S'
            )                  f,
            (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_celular, w.numero nr_celular
               from co_pessoa_telefone w,
                    co_tipo_telefone   x,
                    co_pessoa          z
              where (w.sq_tipo_telefone = x.sq_tipo_telefone)
                and (w.sq_pessoa        = z.sq_pessoa)
                and x.sq_tipo_pessoa    = z.sq_tipo_pessoa
                and x.nome              = 'Celular'
                and x.ativo             = 'S'
                and w.padrao            = 'S'
            )                  l,
            (select w.sq_pessoa, sq_pessoa_endereco, sq_cidade, logradouro, complemento,
                    bairro, cep
               from co_pessoa_endereco w,
                    co_tipo_endereco   x,
                    co_pessoa          z
              where (w.sq_tipo_endereco = x.sq_tipo_endereco)
                and (w.sq_pessoa        = z.sq_pessoa)
                and x.sq_tipo_pessoa    = z.sq_tipo_pessoa
                and x.nome              = 'Comercial'
                and x.ativo             = 'S'
                and w.padrao            = 'S'
            )                  g,
            co_cidade          h,
            co_pais            m,
            (select w.sq_pessoa, logradouro email
               from co_pessoa_endereco w,
                    co_tipo_endereco   x,
                    co_pessoa          y,
                    co_tipo_pessoa     z
              where (w.sq_tipo_endereco = x.sq_tipo_endereco)
                and (w.sq_pessoa        = y.sq_pessoa)
                and (y.sq_tipo_pessoa   = z.sq_tipo_pessoa)
                and x.sq_tipo_pessoa    = z.sq_tipo_pessoa
                and x.email             = 'S'
                and x.ativo             = 'S'
                and w.padrao            = 'S'
            )                  i,
            co_pessoa_fisica   j,
            co_pais            o, 
            co_pessoa_juridica k,
            sg_autenticacao    n,
            eo_unidade         p,
            eo_unidade         q
      where (a.sq_tipo_pessoa     = c.sq_tipo_pessoa  (+))
        and (a.sq_tipo_vinculo    = d.sq_tipo_vinculo (+))
        and (a.sq_pessoa          = e.sq_pessoa       (+))
        and (a.sq_pessoa          = b.sq_pessoa       (+))
        and (a.sq_pessoa          = f.sq_pessoa       (+))
        and (a.sq_pessoa          = l.sq_pessoa       (+))
        and (a.sq_pessoa          = g.sq_pessoa       (+))
        and (g.sq_cidade          = h.sq_cidade       (+))
        and (h.sq_pais            = m.sq_pais         (+))
        and (a.sq_pessoa          = i.sq_pessoa       (+))
        and (a.sq_pessoa          = j.sq_pessoa       (+))
        and (j.sq_pais_passaporte = o.sq_pais         (+))
        and (a.sq_pessoa          = k.sq_pessoa       (+))
        and (a.sq_pessoa          = n.sq_pessoa       (+))
        and (n.sq_unidade         = p.sq_unidade      (+))
        and (p.sq_unidade_pai     = q.sq_unidade      (+))
        and (a.sq_pessoa_pai      = p_cliente or (a.sq_pessoa = p_cliente and Nvl(a.sq_pessoa_pai,1) = 1))
        and (p_sq_pessoa          is null     or (p_sq_pessoa          is not null and a.sq_pessoa          = p_sq_pessoa))
        and (p_tipo_pessoa        is null     or (p_tipo_pessoa        is not null and a.sq_tipo_pessoa     = p_tipo_pessoa))
        and (p_nome               is null     or (p_nome               is not null and (a.nome_indice       like(upper(acentos('%'||p_nome||'%')))) or (a.nome_resumido_ind like(upper(acentos('%'||p_nome||'%')))) ))
        and (p_cpf                is null     or (p_cpf                is not null and (j.cpf               = p_cpf or n.username = p_cpf)))
        and (p_cnpj               is null     or (p_cnpj               is not null and k.cnpj               = p_cnpj))
        and (p_passaporte_numero  is null     or (p_passaporte_numero  is not null and j.passaporte_numero  = p_passaporte_numero))
        and (p_sq_pais_passaporte is null     or (p_sq_pais_passaporte is not null and j.sq_pais_passaporte = p_sq_pais_passaporte));
end SP_GetBenef;
/
