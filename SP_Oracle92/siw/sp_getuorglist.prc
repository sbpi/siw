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
      p_restricao = 'MOD_PA'       or p_restricao = 'MOD_PA_PAI'       or p_restricao = 'EXTERNO' or
      p_restricao = 'MOD_CL_PAI'   or p_restricao = 'MOD_PA_PROT'
   Then
      -- Recupera as unidades organizacionais do cliente
      open p_result for 
         select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                a.codigo, a.sq_unidade_pai, a.ordem, coalesce(d.nome,'Informar') as responsavel, a.ativo,
                a.externo,
                a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                b.sq_pessoa_endereco, b.logradouro,
                e.sq_unidade as lc_chave, e.cnpj as lc_cnpj, e.padrao as lc_padrao, 
                e.licita as lc_licita, e.contrata as lc_contrata, e.ativo as lc_ativo,
                f.nome as nm_cidade, f.co_uf
           from eo_unidade                         a
                inner      join co_pessoa_endereco b on (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
                  inner    join co_cidade          f on (b.sq_cidade          = f.sq_cidade)
                left outer join eo_unidade_resp    c on (a.sq_unidade         = c.sq_unidade and 
                                                         c.tipo_respons       = 'T' and c.fim is null)
                left outer join co_pessoa          d on (c.sq_pessoa          = d.sq_pessoa)
                left outer join lc_unidade         e on (a.sq_unidade         = e.sq_unidade)
                left outer join pe_unidade         g on (a.sq_unidade         = g.sq_unidade)
                left outer join pa_unidade         h on (a.sq_unidade         = h.sq_unidade)
                left outer join cl_unidade         i on (a.sq_unidade         = i.sq_unidade)
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
                                                    (p_restricao = 'MOD_CL_PAI'   and i.sq_unidade is not null and i.sq_unidade_pai is null) or 
                                                    (p_restricao = 'MOD_PA_PROT'  and h.sq_unidade is not null and h.autua_processo = 'S') or 
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
                   a.codigo, a.sq_unidade_pai, a.ordem, a.ativo, coalesce(d.nome,'Informar') as responsavel,
                   coalesce(e.qtd,0) as qtd_resp,
                   coalesce(f.qtd,0) as qtd_local
              from eo_unidade                        a
                   left   outer join eo_unidade_resp c on (a.sq_unidade = c.sq_unidade
                                                           and c.tipo_respons = 'T'
                                                           and c.fim is null
                                                          )
                     left outer join co_pessoa       d on (c.sq_pessoa = d.sq_pessoa)
                   left   outer join (select sq_unidade, count(sq_unidade_resp) as qtd
                                        from eo_unidade_resp x
                                       where x.fim is null
                                      group by sq_unidade
                                     )               e on (a.sq_unidade = e.sq_unidade)
                   left   outer join (select sq_unidade, count(sq_localizacao) as qtd
                                        from eo_localizacao x
                                       where ativo = 'S'
                                      group by sq_unidade
                                     )               f on (a.sq_unidade = f.sq_unidade),
                   co_pessoa_endereco                b
             where a.sq_pessoa_endereco   = b.sq_pessoa_endereco
               and a.sq_unidade_pai       is null
               and b.sq_pessoa            = p_cliente
               and (p_nome                is null or (p_nome  is not null and acentos(a.nome)  like '%'||acentos(p_nome)||'%'))
               and (p_sigla               is null or (p_sigla is not null and acentos(a.sigla) like '%'||acentos(p_sigla)||'%'))
            order by nome;
      Elsif p_restricao = 'VIAGEM' Then
         open p_result for 
            select a.sq_unidade, a.ativo, 
                   case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                   b.nome, b.sigla,
                   c.sq_pessoa_endereco, c.logradouro,
                   f.nome nm_cidade, f.co_uf
              from pd_unidade                        a
                   inner     join eo_unidade         b on (a.sq_unidade         = b.sq_unidade)
                     inner   join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
                       inner join co_cidade          f on (c.sq_cidade          = f.sq_cidade)
             where c.sq_pessoa = p_cliente 
               and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));
      Elsif p_restricao = 'CLCP' or p_restricao = 'CLLC' Then
         open p_result for 
            select a.sq_unidade, a.ativo, 
                   case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                   b.nome, b.sigla,
                   c.sq_pessoa_endereco, c.logradouro,
                   f.nome nm_cidade, f.co_uf
              from cl_unidade                        a
                   inner     join eo_unidade         b on (a.sq_unidade         = b.sq_unidade)
                     inner   join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
                       inner join co_cidade          f on (c.sq_cidade          = f.sq_cidade)
             where c.sq_pessoa = p_cliente 
               and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave))
               and ((p_restricao <> 'CLCP' and p_restricao <> 'CLLC') or
                    ((p_restricao = 'CLCP' and a.solicita_compra = 'S') or
                     (p_restricao = 'CLLC' and a.realiza_compra  = 'S')
                    )
                   );
      Elsif p_restricao = 'PDUNIDLIM' Then
         open p_result for 
            select a.sq_unidade, a.ativo, 
                   case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                   b.nome, b.sigla,
                   c.sq_pessoa_endereco, c.logradouro,
                   f.nome as nm_cidade, f.co_uf,
                   g.ano, coalesce(g.limite_passagem,0) as limite_passagem, coalesce(g.limite_diaria,0) as limite_diaria
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
                   (select sq_unidade, nome, level as nivel
                      from eo_unidade a
                    start with a.sq_unidade = p_chave
                    connect by prior a.sq_unidade_pai = a.sq_unidade
                   ) y
            where x.sq_unidade = y.sq_unidade
            order by y.nivel;
      Elsif p_restricao = 'CLUNID' Then
         open p_result for 
            select x.sq_unidade, x.realiza_compra, x.solicita_compra, x.registra_pesquisa, x.registra_contrato, x.registra_judicial, 
                   x.controla_banco_ata, x.controla_banco_preco, x.codifica_item, x.codificacao_restrita, x.unidade_padrao, x.ativo, 
                   y.nivel
              from cl_unidade x,
                   (select sq_unidade, nome, level as nivel
                      from eo_unidade a
                    start with a.sq_unidade = p_chave
                    connect by prior a.sq_unidade_pai = a.sq_unidade
                   ) y
            where x.sq_unidade = y.sq_unidade
            order by y.nivel;
      Elsif p_restricao = 'PAUNID' Then
         open p_result for 
            select x.sq_unidade, x.cliente, x.registra_documento, x.autua_processo, x.prefixo, x.numero_documento, x.numero_tramite, 
                   x.numero_transferencia, x.numero_eliminacao, x.arquivo_setorial, x.ativo, x.sq_unidade_pai,
                   y.nivel
              from pa_unidade x,
                   (select sq_unidade, nome, level as nivel
                      from eo_unidade a
                    start with a.sq_unidade = p_chave
                    connect by prior a.sq_unidade_pai = a.sq_unidade
                   ) y
            where x.sq_unidade = y.sq_unidade
            order by y.nivel;
      ElsIf p_restricao = 'LICITACAOEND' Then
         open p_result for       
            select a.sq_unidade as sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, coalesce(d.nome,'Informar') as responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade as lc_chave, e.cnpj as lc_cnpj, e.padrao as lc_padrao, 
                   e.licita as lc_licita, e.contrata as lc_contrata, e.ativo as lc_ativo,
                   f.nome as nm_cidade, f.co_uf
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
            select a.sq_unidade as sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, coalesce(d.nome,'Informar') as responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade as lc_chave, e.cnpj as lc_cnpj, e.padrao as lc_padrao, 
                   e.licita as lc_licita, e.contrata as lc_contrata, e.ativo as lc_ativo,
                   f.nome as nm_cidade, f.co_uf
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
            select a.sq_unidade as sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, coalesce(d.nome,'Informar') as responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade as lc_chave, e.cnpj as lc_cnpj, e.padrao as lc_padrao, 
                   e.licita as lc_licita, e.contrata as lc_contrata, e.ativo as lc_ativo,
                   f.nome as nm_cidade, f.co_uf
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
            select a.sq_unidade as sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, coalesce(d.nome,'Informar') as responsavel, a.ativo,
                   a.sq_unidade_gestora, a.sq_unid_pagadora, a.unidade_gestora, a.unidade_pagadora,
                   b.sq_pessoa_endereco, b.logradouro,
                   e.sq_unidade as lc_chave, e.cnpj as lc_cnpj, e.padrao as lc_padrao, 
                   e.licita as lc_licita, e.contrata as lc_contrata, e.ativo as lc_ativo,
                   f.nome as nm_cidade, f.co_uf
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
      Elsif substr(p_restricao,1,5) = 'RELAT'Then
        open p_result for 
            select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, a.ativo, a.externo,
                   case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                   case a.externo when 'S' then 'Sim' else 'Não' end as nm_externo,
                   c.inicio as ini_titular, c1.inicio as ini_substituto,
                   d.sq_pessoa as sq_titular, d.nome as titular,
                   d1.sq_pessoa as sq_substituto,d1.nome as substituto,
                   b.logradouro||' ('||b1.nome||'-'||b1.co_uf||')' as endereco,
                   e.nome as nm_tipo_unidade,
                   f.nome as nm_area_atuacao,
                   g.nome as nm_unidade_pai, g.sigla as sg_unidade_pai
              from eo_unidade                      a
                   left    join eo_unidade_resp    c  on (a.sq_unidade = c.sq_unidade
                                                          and c.tipo_respons = 'T'
                                                          and c.fim is null
                                                         )
                     left  join co_pessoa          d  on (c.sq_pessoa = d.sq_pessoa)
                   left    join eo_unidade_resp    c1 on (a.sq_unidade = c1.sq_unidade
                                                          and c1.tipo_respons = 'S'
                                                          and c1.fim is null
                                                         )
                     left  join co_pessoa          d1 on (c1.sq_pessoa = d1.sq_pessoa)
                   inner   join co_pessoa_endereco b  on (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
                     inner join co_cidade          b1 on (b.sq_cidade          = b1.sq_cidade)
                   inner   join eo_tipo_unidade    e  on (a.sq_tipo_unidade    = e.sq_tipo_unidade)
                   inner   join eo_area_atuacao    f  on (a.sq_area_atuacao    = f.sq_area_atuacao)
                   left    join eo_unidade         g  on (a.sq_unidade_pai     = g.sq_unidade)
             where b.sq_pessoa = p_cliente
                   and (p_ano   is null or (p_ano   is not null and a.sq_pessoa_endereco = p_ano))
                   and (p_chave is null or 
                        (p_chave is not null and ((p_restricao <> 'RELATSUB' and a.sq_unidade        = p_chave) or
                                                  (p_restricao = 'RELATSUB' and
                                                   a.sq_unidade in (select sq_unidade
                                                                      from eo_unidade
                                                                    connect by prior sq_unidade = sq_unidade_pai
                                                                    start with sq_unidade = p_chave
                                                                   )
                                                  )
                                                 )
                        )
                       );
      Else
         open p_result for
            select a.sq_unidade,a.sq_unidade_pai, a.nome, a.sigla, a.informal, a.adm_central, a.vinculada, 
                   a.codigo, a.sq_unidade_pai, a.ordem, a.ativo, coalesce(d.nome,'Informar') as responsavel,
                   coalesce(e.qtd,0) as qtd_resp,
                   coalesce(f.qtd,0) as qtd_local
              from eo_unidade                        a
                   left   outer join eo_unidade_resp c on (a.sq_unidade = c.sq_unidade
                                                           and c.tipo_respons = 'T'
                                                           and c.fim is null
                                                          )
                     left outer join co_pessoa       d on (c.sq_pessoa = d.sq_pessoa)
                   left   outer join (select sq_unidade, count(sq_unidade_resp) as qtd
                                        from eo_unidade_resp x
                                       where x.fim is null
                                      group by sq_unidade
                                     )               e on (a.sq_unidade = e.sq_unidade)
                   left   outer join (select sq_unidade, count(sq_localizacao) as qtd
                                        from eo_localizacao x
                                       where ativo = 'S'
                                      group by sq_unidade
                                     )               f on (a.sq_unidade = f.sq_unidade),
                   co_pessoa_endereco                b
             where a.sq_pessoa_endereco   = b.sq_pessoa_endereco
               and a.sq_unidade_pai       = p_chave
               and b.sq_pessoa            = p_cliente
               and (p_nome                is null or (p_nome  is not null and acentos(a.nome)  like '%'||acentos(p_nome)||'%'))
               and (p_sigla               is null or (p_sigla is not null and acentos(a.sigla) like '%'||acentos(p_sigla)||'%'))
            order by a.nome;
      End If;
   End If;
end SP_GetUorgList;
/
