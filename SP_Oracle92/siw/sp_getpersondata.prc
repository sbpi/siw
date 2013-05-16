create or replace procedure SP_GetPersonData
   (p_cliente   in number,
    p_sq_pessoa in number   default null,
    p_cpf       in varchar2 default null,
    p_cnpj      in varchar2 default null,
    p_result   out sys_refcursor
   ) is
begin
   open p_result for 
     select a.sq_pessoa, a.sq_pessoa_pai, a.sq_tipo_vinculo, a.sq_tipo_pessoa, 
            a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind, 
            a.cliente, a.fornecedor, a.entidade, a.parceiro, a.funcionario, a.dependente, 
            a.codigo_externo, a.sq_recurso, a.inclusao,
            
            b.username, b.ativo, b.sq_unidade, b.sq_localizacao, b.tipo_autenticacao,
            b.gestor_portal, b.gestor_dashbord, b.gestor_conteudo,
            b.gestor_seguranca, b.gestor_sistema, coalesce(b.email,i.email) as email,
            b.nm_tipo_autenticacao, b.nm_gestor_seguranca, b.nm_gestor_sistema,
            b.nm_gestor_portal, b.nm_gestor_dashbord, b.nm_gestor_conteudo,
            b.nm_ativo, b.unidade, b.sigla, b.email_unidade,
            b.localizacao, b.fax, b.telefone, b.ramal, b.telefone2,
            b.endereco, b.cidade, b.ddd,

            c.sq_usuario_central, c.sq_central_fone, c.codigo codigo_central,
            d.sq_tipo_vinculo, d.nome nome_vinculo, d.interno, d.ativo vinculo_ativo, d.contratado,
            case d.interno          when 'S' then 'Sim' else 'Não' end as nm_interno,
            case d.contratado       when 'S' then 'Sim' else 'Não' end as nm_contratado,
            j.sexo, coalesce(j.cpf, b.username) as cpf,
            case j.sexo when 'F' then 'Feminino' when 'M' then 'Masculino' else null end as nm_sexo,
            null cnpj,
            l.sq_tipo_pessoa, l.nome as nm_tipo_pessoa
       from co_pessoa                             a
            inner     join co_pessoa_fisica       j on (a.sq_pessoa          = j.sq_pessoa and p_cnpj is null)
            inner     join co_tipo_pessoa         l on (a.sq_tipo_pessoa     = l.sq_tipo_pessoa)
            left      join tt_usuario             c on (a.sq_pessoa          = c.usuario)
            left      join co_tipo_vinculo        d on (a.sq_tipo_vinculo    = d.sq_tipo_vinculo)
            left      join (select k.sq_pessoa, k.username, k.ativo, k.sq_unidade, k.sq_localizacao, k.tipo_autenticacao,
                                   k.gestor_portal, k.gestor_dashboard as gestor_dashbord, k.gestor_conteudo,
                                   k.gestor_seguranca, k.gestor_sistema, k.email,
                                   case k.tipo_autenticacao when 'B' then 'BD' when 'A' then 'MS-AD' else 'O-LDAP' end as nm_tipo_autenticacao,
                                   case k.gestor_seguranca when 'S' then 'Sim' else 'Não' end as nm_gestor_seguranca,
                                   case k.gestor_sistema   when 'S' then 'Sim' else 'Não' end as nm_gestor_sistema,
                                   case k.gestor_portal    when 'S' then 'Sim' else 'Não' end as nm_gestor_portal,
                                   case k.gestor_dashboard when 'S' then 'Sim' else 'Não' end as nm_gestor_dashbord,
                                   case k.gestor_conteudo  when 'S' then 'Sim' else 'Não' end as nm_gestor_conteudo,
                                   case k.ativo            when 'S' then 'Sim' else 'Não' end as nm_ativo,
                                   l.nome as unidade, l.sigla, l.email as email_unidade,
                                   m.nome as localizacao, m.fax, m.telefone, m.ramal, m.telefone2,
                                   n.logradouro as endereco, (o.nome||'-'||o.co_uf) as cidade, o.ddd
                              from sg_autenticacao                       k
                                   inner     join  eo_unidade            l on (k.sq_unidade         = l.sq_unidade)
                                   inner     join  eo_localizacao        m on (k.sq_localizacao     = m.sq_localizacao)
                                     inner   join  co_pessoa_endereco    n on (m.sq_pessoa_endereco = n.sq_pessoa_endereco)
                                       inner join  co_cidade             o on (n.sq_cidade          = o.sq_cidade)
                             where k.cliente    = p_cliente
                               and (p_sq_pessoa is null or (p_sq_pessoa  is not null and k.sq_pessoa  = p_sq_pessoa))
                               and (p_cpf       is null or (p_cpf        is not null and k.username = p_cpf))
                           )                      b on (a.sq_pessoa          = b.sq_pessoa)
            left      join (select y.sq_pessoa, w.logradouro email
                              from co_pessoa_endereco            w
                                   inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                   inner   join co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                     inner join co_pessoa_fisica z on (y.sq_pessoa          = z.sq_pessoa)
                             where y.sq_pessoa_pai = p_cliente
                               and x.email  = 'S'
                               and x.ativo  = 'S'
                               and w.padrao = 'S'
                               and (p_cpf  is null or (p_cpf is not null and z.cpf = p_cpf))
                           )                      i on (a.sq_pessoa          = i.sq_pessoa)
      where p_cnpj          is null
        and a.sq_pessoa_pai = p_cliente
        and (p_sq_pessoa    is null or (p_sq_pessoa    is not null and a.sq_pessoa  = p_sq_pessoa))
        and (p_cpf          is null or (p_cpf          is not null and (j.cpf       = p_cpf or b.username = p_cpf)))
     UNION
     select a.sq_pessoa, a.sq_pessoa_pai, a.sq_tipo_vinculo, a.sq_tipo_pessoa, 
            a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind, 
            a.cliente, a.fornecedor, a.entidade, a.parceiro, a.funcionario, a.dependente, 
            a.codigo_externo, a.sq_recurso, a.inclusao,
            
            null username, null ativo, null sq_unidade, null sq_localizacao, null tipo_autenticacao,
            null gestor_portal, null gestor_dashbord, null gestor_conteudo,
            null gestor_seguranca, null gestor_sistema, i.email,
            null nm_tipo_autenticacao, null nm_gestor_seguranca, null nm_gestor_sistema,
            null nm_gestor_portal, null nm_gestor_dashbord, null nm_gestor_conteudo,
            null nm_ativo, null unidade, null sigla, null email_unidade,
            null localizacao, null fax, null telefone, null ramal, null telefone2,
            null endereco, null cidade, null ddd,

            null sq_usuario_central, null sq_central_fone, null codigo_central,
            d.sq_tipo_vinculo, d.nome nome_vinculo, d.interno, d.ativo vinculo_ativo, d.contratado,
            case d.interno          when 'S' then 'Sim' else 'Não' end as nm_interno,
            case d.contratado       when 'S' then 'Sim' else 'Não' end as nm_contratado,
            null sexo, null cpf,
            null nm_sexo,
            k.cnpj,
            l.sq_tipo_pessoa, l.nome as nm_tipo_pessoa
       from co_pessoa                            a
            inner     join co_pessoa_juridica    k on (a.sq_pessoa          = k.sq_pessoa and p_cpf is null)
            inner     join co_tipo_pessoa        l on (a.sq_tipo_pessoa     = l.sq_tipo_pessoa)
            left      join co_tipo_vinculo       d on (a.sq_tipo_vinculo    = d.sq_tipo_vinculo)
            left      join (select y.sq_pessoa, w.logradouro email
                              from co_pessoa_endereco              w
                                   inner   join co_tipo_endereco   x on (w.sq_tipo_endereco = x.sq_tipo_endereco)
                                   inner   join co_pessoa          y on (w.sq_pessoa        = y.sq_pessoa)
                                     inner join co_pessoa_juridica z on (y.sq_pessoa        = z.sq_pessoa)
                             where x.email    = 'S'
                               and x.ativo    = 'S'
                               and w.padrao   = 'S'
                               and (p_cliente = 1     or y.sq_pessoa_pai = p_cliente)
                               and (p_cnpj    is null or (p_cnpj  is not null and z.cnpj = p_cnpj))
                           )                     i on (a.sq_pessoa          = i.sq_pessoa)
      where p_cpf        is null
        and (p_cliente   = 1     or a.sq_pessoa_pai = p_cliente)
        and (p_sq_pessoa is null or (p_sq_pessoa    is not null and a.sq_pessoa  = p_sq_pessoa))
        and (p_cnpj      is null or (p_cnpj         is not null and k.cnpj       = p_cnpj));
end SP_GetPersonData;
/
