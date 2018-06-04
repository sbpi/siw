create or replace procedure SP_GetLancamentoItem
   (p_sq_documento_item  in number   default null,
    p_sq_lancamento_doc  in number   default null,
    p_chave              in number   default null,
    p_sq_projeto         in number   default null,
    p_restricao          in varchar2,
    p_result             out sys_refcursor) is
begin
   If p_restricao is null Then
      open p_result for 
         select a.sq_documento_item,  a.sq_lancamento_doc, a.descricao, a.sq_projeto_rubrica,
                a.sq_solic_apoio, a.quantidade, a.valor_unitario, a.valor_total, a.ordem, 
                a.data_cotacao, a.valor_cotacao, a.sq_solicitacao_item,
                b.sq_siw_solicitacao, b.sq_tipo_documento, 
                c.nome as nm_rubrica, c.codigo as codigo_rubrica,
                d.entidade as nm_fonte
           from fn_documento_item           a
                left join fn_lancamento_doc b on (a.sq_lancamento_doc  = b.sq_lancamento_doc)
                left join pj_rubrica        c on (a.sq_projeto_rubrica = c.sq_projeto_rubrica)
                left join siw_solic_apoio   d on (a.sq_solic_apoio     = d.sq_solic_apoio)
          where (p_sq_lancamento_doc  is null or (p_sq_lancamento_doc  is not null and a.sq_lancamento_doc  = p_sq_lancamento_doc))
            and (p_sq_documento_item  is null or (p_sq_documento_item  is not null and a.sq_documento_item  = p_sq_documento_item))
            and (p_chave              is null or (p_chave              is not null and b.sq_siw_solicitacao = p_chave));
   Elsif p_restricao = 'RUBRICA' Then
      open p_result for 
         select sum(b.valor_total) as valor_total, coalesce(d.nome,e.nome) as nm_rubrica, coalesce(d.codigo,e.codigo) as codigo_rubrica,
                case coalesce(coalesce(d.codigo,e.codigo),'nulo') when 'nulo' then 'Não informado' else coalesce(d.codigo,e.codigo)||' - '||coalesce(d.nome,e.nome) end as rubrica,
                sum(c.valor) as valor_rubrica, coalesce(d.sq_projeto_rubrica,e.sq_projeto_rubrica) as sq_projeto_rubrica
           from fn_lancamento_doc                  a
                inner   join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                inner   join siw_solicitacao      a2 on (a1.sq_siw_solicitacao = a2.sq_siw_solicitacao)
                  inner join siw_menu             a3 on (a2.sq_menu            = a3.sq_menu)
                left    join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
                  left  join pj_rubrica            e on (b.sq_projeto_rubrica  = e.sq_projeto_rubrica)
                left    join fn_lancamento_rubrica c on (a.sq_lancamento_doc   = c.sq_lancamento_doc)
                  left  join pj_rubrica            d on (c.sq_rubrica_origem   = d.sq_projeto_rubrica)
          where a.sq_siw_solicitacao    = p_chave
            and a3.sigla                <> 'FNDFIXO'
            and a.sq_acordo_nota        is null
            and ((a1.sq_projeto_rubrica is     null) or
                 (a1.sq_projeto_rubrica is not null and b.sq_documento_item is not null)
                )
            and 0 = (select count(*)
                       from fn_lancamento_doc                  a
                            inner   join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                              left  join pj_rubrica            e on (a1.sq_projeto_rubrica = e.sq_projeto_rubrica)
                            left    join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
                      where a.sq_siw_solicitacao = p_chave
                        and a.sq_acordo_nota     is null
                        and b.sq_documento_item  is null
                    )
         group by d.sq_projeto_rubrica, d.codigo, d.nome, e.sq_projeto_rubrica, e.codigo, e.nome
         UNION
         select sum(a.valor) as valor_total, e.nome as nm_rubrica, e.codigo as codigo_rubrica,
                case coalesce(e.codigo,'nulo') when 'nulo' then 'Não informado' else e.codigo||' - '||e.nome end as rubrica,
                sum(a.valor) as valor_rubrica, e.sq_projeto_rubrica as sq_projeto_rubrica
           from fn_lancamento_doc                  a
                inner   join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                inner   join siw_solicitacao      a2 on (a1.sq_siw_solicitacao = a2.sq_siw_solicitacao)
                  inner join siw_menu             a3 on (a2.sq_menu            = a3.sq_menu)
                  left  join pj_rubrica            e on (a1.sq_projeto_rubrica = e.sq_projeto_rubrica)
                left    join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
          where a.sq_siw_solicitacao    = p_chave
            and a3.sigla                <> 'FNDFIXO'
            and a.sq_acordo_nota        is null
            and b.sq_documento_item     is null
         group by e.sq_projeto_rubrica, e.codigo, e.nome
         UNION
         select sum(b.valor_total) as valor_total, coalesce(d.nome,e.nome) as nm_rubrica, coalesce(d.codigo,e.codigo) as codigo_rubrica,
                case coalesce(coalesce(d.codigo,e.codigo),'nulo') when 'nulo' then 'Não informado' else coalesce(d.codigo,e.codigo)||' - '||coalesce(d.nome,e.nome) end as rubrica,
                sum(c.valor) as valor_rubrica, coalesce(d.sq_projeto_rubrica,e.sq_projeto_rubrica) as sq_projeto_rubrica
           from fn_lancamento_doc                  a
                inner   join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                inner   join siw_solicitacao      a2 on (a1.sq_siw_solicitacao = a2.sq_siw_solicitacao)
                  inner join siw_solicitacao      a3 on (a2.sq_solic_pai       = a3.sq_siw_solicitacao)
                  inner join siw_menu             a4 on (a3.sq_menu            = a4.sq_menu)
                left    join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
                  left  join pj_rubrica            e on (b.sq_projeto_rubrica  = e.sq_projeto_rubrica)
                left    join fn_lancamento_rubrica c on (a.sq_lancamento_doc   = c.sq_lancamento_doc)
                  left  join pj_rubrica            d on (c.sq_rubrica_origem   = d.sq_projeto_rubrica)
          where a3.sq_siw_solicitacao   = p_chave
            and a4.sigla                = 'FNDFIXO'
            and a.sq_acordo_nota        is null
            and ((a1.sq_projeto_rubrica is     null) or
                 (a1.sq_projeto_rubrica is not null and b.sq_documento_item is not null)
                )
            and 0 = (select count(*)
                       from fn_lancamento_doc                  a
                            inner   join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                              left  join pj_rubrica            e on (a1.sq_projeto_rubrica = e.sq_projeto_rubrica)
                            left    join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
                      where a.sq_siw_solicitacao = p_chave
                        and a.sq_acordo_nota     is null
                        and b.sq_documento_item  is null
                    )
         group by d.sq_projeto_rubrica, d.codigo, d.nome, e.sq_projeto_rubrica, e.codigo, e.nome
         UNION
         select sum(a.valor) as valor_total, e.nome as nm_rubrica, e.codigo as codigo_rubrica,
                case coalesce(e.codigo,'nulo') when 'nulo' then 'Não informado' else e.codigo||' - '||e.nome end as rubrica,
                sum(a.valor) as valor_rubrica, e.sq_projeto_rubrica as sq_projeto_rubrica
           from fn_lancamento_doc                  a
                inner   join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                inner   join siw_solicitacao      a2 on (a1.sq_siw_solicitacao = a2.sq_siw_solicitacao)
                  inner join siw_solicitacao      a3 on (a2.sq_solic_pai       = a3.sq_siw_solicitacao)
                  inner join siw_menu             a4 on (a3.sq_menu            = a4.sq_menu)
                  left  join pj_rubrica            e on (a1.sq_projeto_rubrica = e.sq_projeto_rubrica)
                left    join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
          where a3.sq_siw_solicitacao   = p_chave
            and a4.sigla                = 'FNDFIXO'
            and a.sq_acordo_nota        is null
            and b.sq_documento_item     is null
         group by e.sq_projeto_rubrica, e.codigo, e.nome;
   Elsif p_restricao = 'FONTERUB' Then
      open p_result for 
         select sum(a.fn_valor) as valor_total, 
                a.sq_fonte, a.nm_fonte, 
                a.sq_projeto_rubrica, b.codigo codigo_rubrica, a.nm_rubrica
           from vw_projeto_financeiro a
                left join pj_rubrica  b on a.sq_projeto_rubrica = b.sq_projeto_rubrica
          where a.sq_financeiro = p_chave
         group by a.sq_fonte, a.nm_fonte, a.sq_projeto_rubrica, b.codigo, a.nm_rubrica
         order by a.nm_fonte, codigo;
   Elsif p_restricao = 'FONTE' Then
      open p_result for 
         select e.sq_solic_apoio, e.entidade nm_fonte, sum(b.valor_total) as valor_total, sum(e.valor) as valor_apoio
           from fn_lancamento_doc                  a
                inner   join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                inner   join siw_solicitacao      a2 on (a1.sq_siw_solicitacao = a2.sq_siw_solicitacao)
                  inner join siw_menu             a3 on (a2.sq_menu            = a3.sq_menu)
                left    join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
                  left  join siw_solic_apoio       e on (b.sq_solic_apoio      = e.sq_solic_apoio)
          where a.sq_siw_solicitacao = p_chave
            and a3.sigla             <> 'FNDFIXO'
            and a.sq_acordo_nota     is null
            and ((a1.sq_solic_apoio  is     null) or
                 (a1.sq_solic_apoio  is not null and b.sq_documento_item is not null)
                )
            and 0 = (select count(*)
                       from fn_lancamento_doc                a
                            inner join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                            left  join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
                      where a.sq_siw_solicitacao = p_chave
                        and a.sq_acordo_nota     is null
                        and b.sq_documento_item  is null
                    )
         group by e.sq_solic_apoio, e.entidade
         UNION
         select e.sq_solic_apoio, e.entidade nm_fonte, sum(a.valor) as valor_total, sum(e.valor) as valor_apoio
           from fn_lancamento_doc                  a
                inner   join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                inner   join siw_solicitacao      a2 on (a1.sq_siw_solicitacao = a2.sq_siw_solicitacao)
                  inner join siw_menu             a3 on (a2.sq_menu            = a3.sq_menu)
                left    join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
                left    join siw_solic_apoio       e on (a1.sq_solic_apoio     = e.sq_solic_apoio)
          where a.sq_siw_solicitacao    = p_chave
            and a3.sigla                <> 'FNDFIXO'
            and a.sq_acordo_nota        is null
            and b.sq_documento_item     is null
         group by e.sq_solic_apoio, e.entidade
         UNION
         select e.sq_solic_apoio, e.entidade nm_fonte, sum(b.valor_total) as valor_total, sum(e.valor) as valor_apoio
           from fn_lancamento_doc                  a
                inner   join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                inner   join siw_solicitacao      a2 on (a1.sq_siw_solicitacao = a2.sq_siw_solicitacao)
                  inner join siw_menu             a3 on (a2.sq_menu            = a3.sq_menu)
                  inner join siw_solicitacao      a4 on (a2.sq_solic_pai       = a4.sq_siw_solicitacao)
                left    join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
                  left  join siw_solic_apoio       e on (b.sq_solic_apoio      = e.sq_solic_apoio)
          where a4.sq_siw_solicitacao = p_chave
            and a3.sigla              = 'FNDFIXO'
            and a.sq_acordo_nota      is null
            and ((a1.sq_solic_apoio   is     null) or
                 (a1.sq_solic_apoio   is not null and b.sq_documento_item is not null)
                )
            and 0 = (select count(*)
                       from fn_lancamento_doc                a
                            inner join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                            left  join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
                      where a.sq_siw_solicitacao = p_chave
                        and a.sq_acordo_nota     is null
                        and b.sq_documento_item  is null
                    )
         group by e.sq_solic_apoio, e.entidade
         UNION
         select e.sq_solic_apoio, e.entidade nm_fonte, sum(a.valor) as valor_total, sum(e.valor) as valor_apoio
           from fn_lancamento_doc                  a
                inner   join fn_lancamento        a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                inner   join siw_solicitacao      a2 on (a1.sq_siw_solicitacao = a2.sq_siw_solicitacao)
                  inner join siw_menu             a3 on (a2.sq_menu            = a3.sq_menu)
                  inner join siw_solicitacao      a4 on (a2.sq_solic_pai       = a4.sq_siw_solicitacao)
                left    join fn_documento_item     b on (a.sq_lancamento_doc   = b.sq_lancamento_doc)
                left    join siw_solic_apoio       e on (a1.sq_solic_apoio     = e.sq_solic_apoio)
          where a4.sq_siw_solicitacao   = p_chave
            and a3.sigla                = 'FNDFIXO'
            and a.sq_acordo_nota        is null
            and b.sq_documento_item     is null
         group by e.sq_solic_apoio, e.entidade;
   End If;
End SP_GetLancamentoItem;
/
