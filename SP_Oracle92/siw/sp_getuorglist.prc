create or replace procedure SP_GetUorgList
   (p_cliente   in number,
    p_chave     in number   default null,
    p_restricao in varchar2 default null,
    p_nome      in varchar2 default null,
    p_sigla     in varchar2 default null,
    p_ano       in number   default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null          or p_restricao = 'LICITACAO'        or p_restricao = 'ATIVO' or 
      p_restricao = 'CODIGO'       or p_restricao = 'CODIGONULL'       or p_restricao = 'MOD_PE' or 
      p_restricao = 'RECURSO'      or p_restricao = 'PLANEJAMENTO'     or p_restricao = 'EXECUCAO' or 
      p_restricao = 'MOD_PA'       or p_restricao = 'MOD_PA_PAI'       or p_restricao = 'EXTERNO'
   Then
      -- Recupera as unidades organizacionais do cliente
      open p_result for 
         select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                a.codigo, a.sq_unidade_pai, a.ordem, Nvl(d.nome,'Informar') responsavel, a.ativo,
                a.externo,
                a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                b.sq_pessoa_endereco, b.logradouro,
                e.sq_unidade lc_chave, e.cnpj lc_cnpj, e.padrao lc_padrao, 
                e.licita lc_licita, e.contrata lc_contrata, e.ativo lc_ativo,
                f.nome nm_cidade, f.co_uf
           from eo_unidade                         a
                inner      join co_pessoa_endereco b on (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
                  inner    join co_cidade          f on (b.sq_cidade          = f.sq_cidade)
                left outer join eo_unidade_resp    c on (a.sq_unidade         = c.sq_unidade and 
                                                         c.tipo_respons       = 'T' and c.fim is null)
                left outer join co_pessoa          d on (c.sq_pessoa          = d.sq_pessoa)
                left outer join lc_unidade         e on (a.sq_unidade         = e.sq_unidade)
                left outer join pe_unidade         g on (a.sq_unidade         = g.sq_unidade)
                left outer join pa_unidade         h on (a.sq_unidade         = h.sq_unidade)
          where b.sq_pessoa            = p_cliente
            and (p_restricao           is null or (p_restricao is not null and
                                                   (p_restricao         <> 'EXTERNO' and a.externo = 'N') or 
                                                   (p_restricao          = 'EXTERNO' and (a.externo  = 'N' or (a.externo  = 'S' and 0 = (select count(sq_unidade) from eo_unidade where sq_unidade_pai = a.sq_unidade))))
                                                  )
                )
            and (p_chave               is null or (p_chave is not null and a.sq_unidade = p_chave))
            and (p_nome                is null or (p_nome  is not null and acentos(a.nome)  like '%'||acentos(p_nome)||'%'))
            and (p_sigla               is null or (p_sigla is not null and acentos(a.sigla) like '%'||acentos(p_sigla)||'%'))
            and (p_restricao           is null or (p_restricao is not null and
                                                   ((p_restricao = 'LICITACAO'    and a.ativo    = 'S' and e.ativo = 'S' and e.contrata = 'S') or 
                                                    (p_restricao = 'ATIVO'        and a.ativo    = 'S') or 
                                                    (p_restricao = 'EXTERNO') or 
                                                    (p_restricao = 'CODIGO'       and a.informal = 'N' and a.sq_unidade_pai is null) or 
                                                    (p_restricao = 'CODIGONULL'   and a.informal = 'N' and a.codigo <> '00') or 
                                                    (p_restricao = 'MOD_PE'       and g.sq_unidade is not null) or 
                                                    (p_restricao = 'MOD_PA'       and h.sq_unidade is not null) or 
                                                    (p_restricao = 'MOD_PA_PAI'   and h.sq_unidade is not null and h.sq_unidade_pai is null) or 
                                                    (p_restricao = 'RECURSO'      and g.sq_unidade is not null and g.gestao_recursos = 'S') or 
                                                    (p_restricao = 'PLANEJAMENTO' and g.sq_unidade is not null and g.planejamento    = 'S') or 
                                                    (p_restricao = 'EXECUCAO'     and g.sq_unidade is not null and g.execucao        = 'S')
                                                   )
                                                  )
                )
         order by a.nome;
   Else
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, a.ativo, Nvl(d.nome,'Informar') responsavel
              from eo_unidade             a  left outer join eo_unidade_resp c on (a.sq_unidade = c.sq_unidade
                                                                               and c.tipo_respons = 'T'
                                                                               and c.fim is null)
                                             left outer join co_pessoa d on (c.sq_pessoa = d.sq_pessoa),                                   
                   co_pessoa_endereco     b
             where a.sq_pessoa_endereco   = b.sq_pessoa_endereco
               and a.sq_unidade_pai       is null
               and b.sq_pessoa            = p_cliente
               and (p_nome                is null or (p_nome  is not null and acentos(a.nome)  like '%'||acentos(p_nome)||'%'))
               and (p_sigla               is null or (p_sigla is not null and acentos(a.sigla) like '%'||acentos(p_sigla)||'%'))
            order by nome;
      Elsif p_restricao = 'VIAGEM' Then
         open p_result for 
            select a.sq_unidade, a.ativo, 
                   case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                   b.nome, b.sigla,
                   c.sq_pessoa_endereco, c.logradouro,
                   f.nome nm_cidade, f.co_uf
              from pd_unidade                        a
                   inner     join eo_unidade         b on (a.sq_unidade         = b.sq_unidade)
                     inner   join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
                       inner join co_cidade          f on (c.sq_cidade          = f.sq_cidade)
             where c.sq_pessoa = p_cliente 
               and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));
      Elsif p_restricao = 'PDUNIDLIM' Then
         open p_result for 
            select a.sq_unidade, a.ativo, 
                   case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                   b.nome, b.sigla,
                   c.sq_pessoa_endereco, c.logradouro,
                   f.nome nm_cidade, f.co_uf,
                   g.ano, coalesce(g.limite_passagem,0) limite_passagem, coalesce(g.limite_diaria,0) limite_diaria
              from pd_unidade                        a
                   inner     join eo_unidade         b on (a.sq_unidade = b.sq_unidade)
                     inner   join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
                       inner join co_cidade          f on (c.sq_cidade          = f.sq_cidade)
                   inner     join pd_unidade_limite  g on (a.sq_unidade         = g.sq_unidade and
                                                           ((p_ano              is null) or (p_ano is not null and g.ano = p_ano))
                                                          )
             where c.sq_pessoa = p_cliente 
               and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));
      Elsif p_restricao = 'VIAGEMUNID' Then
         open p_result for 
            select x.sq_unidade, y.nivel
              from pd_unidade x,
                   (select sq_unidade, nome, level nivel
                      from eo_unidade a
                    start with a.sq_unidade = p_chave
                    connect by prior a.sq_unidade_pai = a.sq_unidade
                   ) y
            where x.sq_unidade = y.sq_unidade
            order by y.nivel;
      ElsIf p_restricao = 'LICITACAOEND' Then
         open p_result for       
            select a.sq_unidade sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, Nvl(d.nome,'Informar') responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade lc_chave, e.cnpj lc_cnpj, e.padrao lc_padrao, 
                   e.licita lc_licita, e.contrata lc_contrata, e.ativo lc_ativo,
                   f.nome nm_cidade, f.co_uf
              from eo_unidade                         a
                   inner      join co_pessoa_endereco b on (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
                     inner    join co_cidade          f on (b.sq_cidade          = f.sq_cidade)
                   left outer join eo_unidade_resp    c on (a.sq_unidade         = c.sq_unidade and 
                                                            c.tipo_respons       = 'T' and c.fim is null)
                   left outer join co_pessoa          d on (c.sq_pessoa          = d.sq_pessoa)
                   left outer join lc_unidade         e on (a.sq_unidade         = e.sq_unidade)
              where b.sq_pessoa            = p_cliente
                and a.externo              = 'N'
                and (p_nome                is null or (p_nome  is not null and acentos(a.nome)  like '%'||acentos(p_nome)||'%'))
                and (p_sigla               is null or (p_sigla is not null and acentos(a.sigla) like '%'||acentos(p_sigla)||'%'))
                and a.ativo                = 'S' 
                and e.ativo                = 'S' 
                and e.contrata             = 'S' 
                and b.sq_pessoa_endereco = p_chave
           order by a.nome;
      ElsIf p_restricao = 'GESTORA' Then
         open p_result for         
            select a.sq_unidade sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, Nvl(d.nome,'Informar') responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade lc_chave, e.cnpj lc_cnpj, e.padrao lc_padrao, 
                   e.licita lc_licita, e.contrata lc_contrata, e.ativo lc_ativo,
                   f.nome nm_cidade, f.co_uf
              from eo_unidade                         a
                   inner      join co_pessoa_endereco b on (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
                     inner    join co_cidade          f on (b.sq_cidade          = f.sq_cidade)
                   left outer join eo_unidade_resp    c on (a.sq_unidade         = c.sq_unidade and 
                                                            c.tipo_respons       = 'T' and c.fim is null)
                   left outer join co_pessoa          d on (c.sq_pessoa          = d.sq_pessoa)
                    left outer join lc_unidade         e on (a.sq_unidade         = e.sq_unidade)
             where b.sq_pessoa            = p_cliente
               and (p_nome                is null or (p_nome  is not null and acentos(a.nome)  like '%'||acentos(p_nome)||'%'))
               and (p_sigla               is null or (p_sigla is not null and acentos(a.sigla) like '%'||acentos(p_sigla)||'%'))
               and a.unidade_gestora      = 'S' 
               and a.ativo                = 'S' 
               and (p_chave is null or (p_chave is not null and a.sq_unidade <> p_chave))
           order by a.nome;
      ElsIf p_restricao = 'PAGADORA' Then
         open p_result for         
            select a.sq_unidade sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, Nvl(d.nome,'Informar') responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade lc_chave, e.cnpj lc_cnpj, e.padrao lc_padrao, 
                   e.licita lc_licita, e.contrata lc_contrata, e.ativo lc_ativo,
                   f.nome nm_cidade, f.co_uf
              from eo_unidade                         a
                   inner      join co_pessoa_endereco b on (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
                     inner    join co_cidade          f on (b.sq_cidade          = f.sq_cidade)
                   left outer join eo_unidade_resp    c on (a.sq_unidade         = c.sq_unidade and 
                                                            c.tipo_respons       = 'T' and c.fim is null)
                   left outer join co_pessoa          d on (c.sq_pessoa          = d.sq_pessoa)
                    left outer join lc_unidade         e on (a.sq_unidade         = e.sq_unidade)
             where b.sq_pessoa            = p_cliente
               and (p_nome                is null or (p_nome  is not null and acentos(a.nome)  like '%'||acentos(p_nome)||'%'))
               and (p_sigla               is null or (p_sigla is not null and acentos(a.sigla) like '%'||acentos(p_sigla)||'%'))
               and a.unidade_pagadora     = 'S' 
               and a.ativo                = 'S' 
               and (p_chave is null or (p_chave is not null and a.sq_unidade <> p_chave))
           order by a.nome;
      ElsIf p_restricao = 'VALORCODIGO' Then
         open p_result for 
            select a.sq_unidade sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, Nvl(d.nome,'Informar') responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade lc_chave, e.cnpj lc_cnpj, e.padrao lc_padrao, 
                   e.licita lc_licita, e.contrata lc_contrata, e.ativo lc_ativo,
                   f.nome nm_cidade, f.co_uf
              from eo_unidade                         a
                   inner      join co_pessoa_endereco b on (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
                     inner    join co_cidade          f on (b.sq_cidade          = f.sq_cidade)
                   left outer join eo_unidade_resp    c on (a.sq_unidade         = c.sq_unidade and 
                                                            c.tipo_respons       = 'T' and c.fim is null)
                   left outer join co_pessoa          d on (c.sq_pessoa          = d.sq_pessoa)
                   left outer join lc_unidade         e on (a.sq_unidade         = e.sq_unidade)
             where b.sq_pessoa            = p_cliente
               and a.externo              = 'N'
               and (p_nome                is null or (p_nome  is not null and acentos(a.nome)  like '%'||acentos(p_nome)||'%'))
               and (p_sigla               is null or (p_sigla is not null and acentos(a.sigla) like '%'||acentos(p_sigla)||'%'))
               and a.informal             = 'N' 
               and a.codigo               = p_chave
            order by a.nome;      
      Else
         open p_result for
            select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, a.ativo, Nvl(d.nome,'Informar') responsavel
              from eo_unidade             a left outer join eo_unidade_resp c on (a.sq_unidade = c.sq_unidade
                                                                              and c.tipo_respons = 'T'
                                                                              and c.fim is null)
                                            left outer join co_pessoa d on (c.sq_pessoa = d.sq_pessoa),                                   
                   co_pessoa_endereco     b
             where a.sq_pessoa_endereco   = b.sq_pessoa_endereco
               and a.sq_unidade_pai       = p_chave
               and b.sq_pessoa            = p_cliente
               and (p_nome                is null or (p_nome  is not null and acentos(a.nome)  like '%'||acentos(p_nome)||'%'))
               and (p_sigla               is null or (p_sigla is not null and acentos(a.sigla) like '%'||acentos(p_sigla)||'%'))
            order by nome;
      End If;
   End If;
end SP_GetUorgList;
/
