create or replace procedure SP_GetBenef
   (p_cliente            in number,
    p_sq_pessoa          in number   default null,
    p_username           in varchar2 default null,
    p_cpf                in varchar2 default null,
    p_cnpj               in varchar2 default null,
    p_nome               in varchar2 default null,
    p_tipo_pessoa        in number   default null,
    p_tipo_vinculo       in number   default null,
    p_passaporte_numero  in varchar2 default null,
    p_sq_pais_passaporte in number   default null,
    p_clientes           in varchar2 default null,
    p_fornecedor         in varchar2 default null,
    p_entidade           in varchar2 default null,
    p_parceiro           in varchar2 default null,
    p_pais               in number   default null,
    p_regiao             in number   default null,
    p_uf                 in varchar2 default null,
    p_cidade             in number   default null,
    p_restricao          in varchar2 default null,
    p_result             out sys_refcursor
   ) is
begin
   open p_result for
     select /*+ ordered*/ distinct a.sq_pessoa, a.nome as nm_pessoa, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
            a.cliente, a.fornecedor, a.codigo_externo,
            a.sq_pessoa_pai, 
            c.sq_tipo_pessoa, c.nome as nm_tipo_pessoa,
            d.sq_tipo_vinculo, d.nome as nm_tipo_vinculo, d.interno, d.contratado, d.ativo as vinculo_ativo,
            e.sq_pessoa_conta, e.sq_banco, e.sq_agencia, e.cd_agencia, e.operacao, e.numero as nr_conta,
            e.nm_agencia, e.cd_banco, e.nm_banco,  e.devolucao_valor,
            e.sq_pais_estrang, e.aba_code, e.swift_code, e.endereco_estrang, e.banco_estrang, e.agencia_estrang, e.cidade_estrang, e.informacoes,
            b.sq_pessoa_fax, b.nr_fax,
            f.sq_pessoa_telefone, f.ddd, f.nr_telefone,
            l.sq_pessoa_celular, l.nr_celular,
            coalesce(g.sq_pessoa_endereco,g1.sq_pessoa_endereco) as sq_pessoa_endereco, 
            coalesce(g.logradouro, g1.logradouro) as logradouro, 
            coalesce(g.complemento, g1.complemento) as complemento, 
            coalesce(g.bairro, g1.bairro) as bairro, 
            coalesce(g.cep, g1.cep) as cep, 
            coalesce(g.sq_cidade, g1.sq_cidade,b.sq_cidade,f.sq_cidade,l.sq_cidade,i.sq_cidade,a1.sq_cidade_padrao) as sq_cidade, 
            coalesce(g.nm_cidade,g1.nm_cidade,i.nm_cidade,a2.nome) as nm_cidade, 
            coalesce(g.co_uf, g1.co_uf, b.co_uf,f.co_uf,l.co_uf,i.co_uf,a2.co_uf) as co_uf,
            coalesce(g.sq_pais,g1.sq_pais,b.sq_pais,f.sq_pais,l.sq_pais,i.sq_pais) as sq_pais,
            coalesce(g.pd_pais,g1.pd_pais,i.pd_pais,a3.padrao) as pd_pais, 
            coalesce(g.nm_pais,g1.nm_pais,i.nm_pais,a3.nome) as nm_pais,
            coalesce(i.email, n.email) as email,
            coalesce(j.cpf,n.username) as cpf, j.nascimento, j.rg_numero, j.rg_emissao, j.rg_emissor, j.passaporte_numero,
            j.sq_pais_passaporte, j.sexo,
            null cnpj, null inscricao_estadual, null inicio_atividade, null sede,
            o.nome as nm_pais_passaporte,
            case j.sexo when 'F' then 'Feminino' when 'M' then 'Masculino' else null end as nm_sexo,
            n.sq_unidade as sq_unidade_benef, n.username, 
            n.gestor_portal, n.gestor_dashboard as gestor_dashbord, n.gestor_conteudo,
            p.sigla||'/'||q.sigla as nm_unidade_benef,
            coalesce(j.cpf, n.username) as identificador_primario
       from co_pessoa                             a
            inner   join co_tipo_pessoa           c on (a.sq_tipo_pessoa     = c.sq_tipo_pessoa)
            inner   join co_pessoa_fisica         j on (a.sq_pessoa          = j.sq_pessoa and 
                                                        (p_cpf               is null or (p_cpf is not null and j.cpf = p_cpf and j.cliente = p_cliente)))
              left  join co_pais                  o on (j.sq_pais_passaporte = o.sq_pais)
            inner   join siw_cliente             a1 on (a.sq_pessoa_pai      = a1.sq_pessoa)
              inner join co_cidade               a2 on (a1.sq_cidade_padrao  = a2.sq_cidade)
              inner join co_pais                 a3 on (a2.sq_pais           = a3.sq_pais)
            left    join co_tipo_vinculo          d on (a.sq_tipo_vinculo    = d.sq_tipo_vinculo)
            left join  (select w.sq_pessoa_conta, w.sq_pessoa, x.sq_banco, w.sq_agencia, 
                               w.operacao, w.numero,  w.devolucao_valor,   w.sq_pais_estrang,
                               w.aba_code, w.swift_code, w.endereco_estrang, w.banco_estrang,
                               w.agencia_estrang, w.cidade_estrang, w.informacoes,
                               x.codigo as cd_agencia, x.nome as nm_agencia,
                               y.codigo as cd_banco, y.nome as nm_banco
                          from co_pessoa_conta         w
                               -- As liga��es abaixo devem ser do tipo LEFT JOIN pois a ag�ncia n�o � obrigat�ria para dados banc�rios no exterior
                               left    join co_agencia x on (w.sq_agencia = x.sq_agencia and x.ativo = 'S')
                                 left  join co_banco   y on (x.sq_banco   = y.sq_banco)
                         where w.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        e on (a.sq_pessoa          = e.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_fax, w.numero nr_fax, w.sq_cidade,
                               y.co_uf, y.sq_pais
                          from co_pessoa_telefone          w
                               inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                               inner join co_cidade        y on (w.sq_cidade          = y.sq_cidade)
                               inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.nome               = 'Fax'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        b on (a.sq_pessoa          = b.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_pessoa_telefone, w.ddd, w.numero nr_telefone,w.sq_cidade,
                               y.co_uf, y.sq_pais
                          from co_pessoa_telefone          w
                               inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                               inner join co_cidade        y on (w.sq_cidade          = y.sq_cidade)
                               inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.nome               = 'Comercial'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        f on (a.sq_pessoa          = f.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_celular, w.numero nr_celular,w.sq_cidade,
                               y.co_uf, y.sq_pais
                          from co_pessoa_telefone          w
                               inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                               inner join co_cidade        y on (w.sq_cidade          = y.sq_cidade)
                               inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.nome               = 'Celular'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        l on (a.sq_pessoa          = l.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_pessoa_endereco, w.sq_cidade, w.logradouro, w.complemento,
                               w.bairro, w.cep, 
                               w1.co_uf, w1.sq_regiao, w1.sq_pais, w1.nome as nm_cidade,
                               w2.padrao as pd_pais, w2.nome as nm_pais
                          from co_pessoa_endereco            w
                               inner   join co_cidade       w1 on (w.sq_cidade          = w1.sq_cidade)
                                 inner join co_pais         w2 on (w1.sq_pais           = w2.sq_pais)
                               inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                               inner   join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.nome               = 'Comercial'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        g on (a.sq_pessoa          = g.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_pessoa_endereco, w.sq_cidade, w.logradouro, w.complemento,
                               w.bairro, w.cep, 
                               w1.co_uf, w1.sq_regiao, w1.sq_pais, w1.nome as nm_cidade,
                               w2.padrao as pd_pais, w2.nome as nm_pais
                          from co_pessoa_endereco            w
                               inner   join co_cidade       w1 on (w.sq_cidade          = w1.sq_cidade)
                                 inner join co_pais         w2 on (w1.sq_pais           = w2.sq_pais)
                               inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                               inner   join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.nome               = 'Residencial'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        g1 on (a.sq_pessoa         = g1.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_cidade, w.logradouro as email, 
                               w1.co_uf, w1.sq_pais, w1.nome as nm_cidade,
                               w2.padrao as pd_pais, w2.nome as nm_pais
                          from co_pessoa_endereco            w
                               inner   join co_cidade       w1 on (w.sq_cidade          = w1.sq_cidade)
                                 inner join co_pais         w2 on (w1.sq_pais           = w2.sq_pais)
                               inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                               inner   join co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                 inner join co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.email              = 'S'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        i on (a.sq_pessoa          = i.sq_pessoa)
            left join sg_autenticacao           n on (a.sq_pessoa          = n.sq_pessoa)
              left join eo_unidade              p on (n.sq_unidade         = p.sq_unidade)
                left join eo_unidade            q on (p.sq_unidade_pai     = q.sq_unidade)
      where a.sq_pessoa_pai        = p_cliente
        and p_cnpj                is null
        and (p_sq_pessoa          is null     or (p_sq_pessoa          is not null and ((coalesce(p_restricao,'-')<>'EXISTE' and a.sq_pessoa = p_sq_pessoa) or (p_restricao = 'EXISTE' and a.sq_pessoa <> p_sq_pessoa))))
        and (p_tipo_pessoa        is null     or (p_tipo_pessoa        is not null and a.sq_tipo_pessoa     = p_tipo_pessoa))
        and (p_tipo_vinculo       is null     or (p_tipo_vinculo       is not null and a.sq_tipo_vinculo    = p_tipo_vinculo))
        and (p_nome               is null     or (p_nome               is not null and (a.nome_indice       like '%'||upper(acentos(p_nome))||'%' or 
                                                                                        a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%'
                                                                                       )
                                                 )
            )
        and (p_username           is null     or (p_username           is not null and n.username           = p_username))
        and (p_passaporte_numero  is null     or (p_passaporte_numero  is not null and j.passaporte_numero  = p_passaporte_numero))
        and (p_sq_pais_passaporte is null     or (p_sq_pais_passaporte is not null and j.sq_pais_passaporte = p_sq_pais_passaporte))
        and (p_clientes           is null     or (p_clientes           is not null and a.cliente            = p_clientes))
        and (p_fornecedor         is null     or (p_fornecedor         is not null and a.fornecedor         = p_fornecedor))
        and (p_entidade           is null     or (p_entidade           is not null and a.entidade           = p_entidade))
        and (p_parceiro           is null     or (p_parceiro           is not null and a.parceiro           = p_parceiro))
        and (p_pais               is null     or (p_pais               is not null and g.sq_pais            = p_pais))
        and (p_regiao             is null     or (p_regiao             is not null and g.sq_regiao          = p_regiao))
        and (p_cidade             is null     or (p_cidade             is not null and g.sq_cidade          = p_cidade))
        and (p_uf                 is null     or (p_uf                 is not null and g.co_uf              = p_uf))
        and (p_restricao          is null     or (p_restricao          is not null and 
                                                  (p_restricao         = 'NUSUARIO' and 0 = (select count(*) from sg_autenticacao where ativo = 'S' and sq_pessoa = a.sq_pessoa))
                                                 )
            )
     UNION
     select /*+ ordered*/ distinct a.sq_pessoa, a.nome as nm_pessoa, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
            a.cliente, a.fornecedor, a.codigo_externo,
            a.sq_pessoa_pai, 
            c.sq_tipo_pessoa, c.nome as nm_tipo_pessoa,
            d.sq_tipo_vinculo, d.nome as nm_tipo_vinculo, d.interno, d.contratado, d.ativo as vinculo_ativo,
            e.sq_pessoa_conta, e.sq_banco, e.sq_agencia, e.cd_agencia, e.operacao, e.numero as nr_conta,
            e.nm_agencia, e.cd_banco, e.nm_banco,  e.devolucao_valor,
            e.sq_pais_estrang, e.aba_code, e.swift_code, e.endereco_estrang, e.banco_estrang, e.agencia_estrang, e.cidade_estrang, e.informacoes,
            b.sq_pessoa_fax, b.nr_fax,
            f.sq_pessoa_telefone, f.ddd, f.nr_telefone,
            l.sq_pessoa_celular, l.nr_celular,
            coalesce(g.sq_pessoa_endereco,g1.sq_pessoa_endereco) as sq_pessoa_endereco, 
            coalesce(g.logradouro, g1.logradouro) as logradouro, 
            coalesce(g.complemento, g1.complemento) as complemento, 
            coalesce(g.bairro, g1.bairro) as bairro, 
            coalesce(g.cep, g1.cep) as cep, 
            coalesce(g.sq_cidade, g1.sq_cidade,b.sq_cidade,f.sq_cidade,l.sq_cidade,i.sq_cidade,a1.sq_cidade_padrao) as sq_cidade, 
            coalesce(g.nm_cidade,g1.nm_cidade,i.nm_cidade,a2.nome) as nm_cidade, 
            coalesce(g.co_uf, g1.co_uf, b.co_uf,f.co_uf,l.co_uf,i.co_uf,a2.co_uf) as co_uf,
            coalesce(g.sq_pais,g1.sq_pais,b.sq_pais,f.sq_pais,l.sq_pais,i.sq_pais) as sq_pais,
            coalesce(g.pd_pais,g1.pd_pais,i.pd_pais,a3.padrao) as pd_pais, 
            coalesce(g.nm_pais,g1.nm_pais,i.nm_pais,a3.nome) as nm_pais,
            i.email as email,
            null as cpf, null nascimento, null rg_numero, null rg_emissao, null rg_emissor, null passaporte_numero,
            null sq_pais_passaporte, null sexo,
            k.cnpj, k.inscricao_estadual, k.inicio_atividade, k.sede,
            null as nm_pais_passaporte,
            null as nm_sexo,
            null as sq_unidade_benef, null username, 
            null gestor_portal, null as gestor_dashbord, null gestor_conteudo,
            null as nm_unidade_benef,
            k.cnpj as identificador_primario
       from co_pessoa                           a
            inner join co_pessoa_juridica       k on (a.sq_pessoa          = k.sq_pessoa and 
                                                      (p_cnpj              is null or (p_cnpj is not null and k.cnpj = p_cnpj and k.cliente = p_cliente))
                                                     )
            inner join co_tipo_pessoa           c on (a.sq_tipo_pessoa     = c.sq_tipo_pessoa)
            left join  siw_cliente             a1 on (a.sq_pessoa_pai      = a1.sq_pessoa)
            left join  co_cidade               a2 on (a1.sq_cidade_padrao  = a2.sq_cidade)
            left join  co_pais                 a3 on (a2.sq_pais           = a3.sq_pais)
            left join  co_tipo_vinculo          d on (a.sq_tipo_vinculo    = d.sq_tipo_vinculo)
            left join  (select w.sq_pessoa_conta, w.sq_pessoa, x.sq_banco, w.sq_agencia, 
                               w.operacao, w.numero,  w.devolucao_valor,   w.sq_pais_estrang,
                               w.aba_code, w.swift_code, w.endereco_estrang, w.banco_estrang,
                               w.agencia_estrang, w.cidade_estrang, w.informacoes,
                               x.codigo as cd_agencia, x.nome as nm_agencia,
                               y.codigo as cd_banco, y.nome as nm_banco
                          from co_pessoa_conta         w
                               -- As liga��es abaixo devem ser do tipo LEFT JOIN pois a ag�ncia n�o � obrigat�ria para dados banc�rios no exterior
                               left    join co_agencia x on (w.sq_agencia = x.sq_agencia and x.ativo = 'S')
                                 left  join co_banco   y on (x.sq_banco   = y.sq_banco)
                         where w.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        e on (a.sq_pessoa          = e.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_fax, w.numero nr_fax, w.sq_cidade,
                               y.co_uf, y.sq_pais
                          from co_pessoa_telefone          w
                               inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                               inner join co_cidade        y on (w.sq_cidade          = y.sq_cidade)
                               inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.nome               = 'Fax'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        b on (a.sq_pessoa          = b.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_pessoa_telefone, w.ddd, w.numero nr_telefone,w.sq_cidade,
                               y.co_uf, y.sq_pais
                          from co_pessoa_telefone          w
                               inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                               inner join co_cidade        y on (w.sq_cidade          = y.sq_cidade)
                               inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.nome               = 'Comercial'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        f on (a.sq_pessoa          = f.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_celular, w.numero nr_celular,w.sq_cidade,
                               y.co_uf, y.sq_pais
                          from co_pessoa_telefone          w
                               inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                               inner join co_cidade        y on (w.sq_cidade          = y.sq_cidade)
                               inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.nome               = 'Celular'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        l on (a.sq_pessoa          = l.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_pessoa_endereco, w.sq_cidade, w.logradouro, w.complemento,
                               w.bairro, w.cep, 
                               w1.co_uf, w1.sq_regiao, w1.sq_pais, w1.nome as nm_cidade,
                               w2.padrao as pd_pais, w2.nome as nm_pais
                          from co_pessoa_endereco            w
                               inner   join co_cidade       w1 on (w.sq_cidade          = w1.sq_cidade)
                                 inner join co_pais         w2 on (w1.sq_pais           = w2.sq_pais)
                               inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                               inner   join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.nome               = 'Comercial'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        g on (a.sq_pessoa          = g.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_pessoa_endereco, w.sq_cidade, w.logradouro, w.complemento,
                               w.bairro, w.cep, 
                               w1.co_uf, w1.sq_regiao, w1.sq_pais, w1.nome as nm_cidade,
                               w2.padrao as pd_pais, w2.nome as nm_pais
                          from co_pessoa_endereco            w
                               inner   join co_cidade       w1 on (w.sq_cidade          = w1.sq_cidade)
                                 inner join co_pais         w2 on (w1.sq_pais           = w2.sq_pais)
                               inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                               inner   join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.nome               = 'Residencial'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        g1 on (a.sq_pessoa         = g1.sq_pessoa)
            left join  (select w.sq_pessoa, w.sq_cidade, w.logradouro as email, 
                               w1.co_uf, w1.sq_pais, w1.nome as nm_cidade,
                               w2.padrao as pd_pais, w2.nome as nm_pais
                          from co_pessoa_endereco            w
                               inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                               inner   join co_cidade       w1 on (w.sq_cidade          = w1.sq_cidade)
                                 inner join co_pais         w2 on (w1.sq_pais           = w2.sq_pais)
                               inner   join co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                 inner join co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                         where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                           and x.email              = 'S'
                           and x.ativo              = 'S'
                           and w.padrao             = 'S'
                       )                        i on (a.sq_pessoa          = i.sq_pessoa)
      where (a.sq_pessoa_pai      = p_cliente or (p_cliente = coalesce(p_sq_pessoa,0)) or (a.sq_pessoa = p_cliente and a.sq_pessoa_pai is null))
        and p_cpf is null and p_username is null and p_passaporte_numero is null  and p_sq_pais_passaporte is null
        and (p_sq_pessoa          is null     or (p_sq_pessoa          is not null and ((coalesce(p_restricao,'-')<>'EXISTE' and a.sq_pessoa = p_sq_pessoa) or (p_restricao = 'EXISTE' and a.sq_pessoa <> p_sq_pessoa))))
        and (p_tipo_pessoa        is null     or (p_tipo_pessoa        is not null and a.sq_tipo_pessoa     = p_tipo_pessoa))
        and (p_tipo_vinculo       is null     or (p_tipo_vinculo       is not null and a.sq_tipo_vinculo    = p_tipo_vinculo))
        and (p_nome               is null     or (p_nome               is not null and (a.nome_indice       like '%'||upper(acentos(p_nome))||'%' or 
                                                                                        a.nome_resumido_ind like '%'||upper(acentos(p_nome))||'%'
                                                                                       )
                                                 )
            )
        and (p_clientes           is null     or (p_clientes           is not null and a.cliente            = p_clientes))
        and (p_fornecedor         is null     or (p_fornecedor         is not null and a.fornecedor         = p_fornecedor))
        and (p_entidade           is null     or (p_entidade           is not null and a.entidade           = p_entidade))
        and (p_parceiro           is null     or (p_parceiro           is not null and a.parceiro           = p_parceiro))
        and (p_pais               is null     or (p_pais               is not null and g.sq_pais            = p_pais))
        and (p_regiao             is null     or (p_regiao             is not null and g.sq_regiao          = p_regiao))
        and (p_cidade             is null     or (p_cidade             is not null and g.sq_cidade          = p_cidade))
        and (p_uf                 is null     or (p_uf                 is not null and g.co_uf              = p_uf))
        and (p_restricao          is null     or (p_restricao          is not null and 
                                                  (p_restricao         = 'NUSUARIO' and 0 = (select count(*) from sg_autenticacao where ativo = 'S' and sq_pessoa = a.sq_pessoa))
                                                 )
            );

end SP_GetBenef;
/
