create or replace procedure SP_GetLancamentoDoc
   (p_chave      in  number   default null,
    p_chave_aux  in  number   default null,
    p_benef      in  number   default null,
    p_tipo_doc   in  number   default null,
    p_numero     in  varchar2 default null,
    p_data       in  date     default null,
    p_valor      in  number   default null,
    p_restricao  in  varchar2,
    p_result     out sys_refcursor) is
begin
   -- Recupera os dados de um documento ou os documentos de um lançamento financeiro
   -- dependendo dos parâmetros informados
   If p_restricao is null or p_restricao = 'NOTA' or p_restricao = 'DOCS' Then
      open p_result for 
         select a.sq_lancamento_doc, a.sq_siw_solicitacao, a.sq_tipo_documento, a.numero, 
                a.data,              a.serie,              a.valor,             a.patrimonio,
                a.calcula_tributo,   a.calcula_retencao,
                a2.codigo_interno,
                a23.ordem as or_tramite,                   a23.sigla as sg_tramite,
                case d.abrange_inicial   when 'S' then a.valor_inicial else 0 end as valor_inicial, 
                case d.abrange_acrescimo when 'S' then a.valor_excedente else 0 end as valor_excedente, 
                case d.abrange_reajuste  when 'S' then a.valor_reajuste else 0 end as valor_reajuste,
                case a.patrimonio when 'S' then 'Sim' else 'Não' end nm_patrimonio,
                b.nome nm_tipo_documento, b.sigla sg_tipo_documento, b.detalha_item,
                c.total_item,
                d.sq_acordo_nota,
                d.numero numero_nota, d.valor valor_nota, d.abrange_inicial, d.abrange_acrescimo,
                d.abrange_reajuste, d.data data_nota, d.valor_cancelamento, d.data_cancelamento,
                e.sigla sg_nota,
                f.valor_inicial as parcela_ini, f.valor_excedente as parcela_exc, f.valor_reajuste as parcela_rea,
                coalesce(g.acrescimo,0) as acrescimo, 
                coalesce(h.deducao,0) as deducao
           from fn_lancamento_doc                a
                inner    join fn_lancamento      a1 on (a.sq_siw_solicitacao  = a1.sq_siw_solicitacao)
                inner    join siw_solicitacao    a2 on (a.sq_siw_solicitacao  = a2.sq_siw_solicitacao)
                  inner  join siw_tramite       a23 on (a2.sq_siw_tramite     = a23.sq_siw_tramite)
                inner    join fn_tipo_documento  b  on (a.sq_tipo_documento   = b.sq_tipo_documento)
                left     join (select x.sq_lancamento_doc, sum(x.valor_total) total_item
                               from fn_documento_item x
                              group by x.sq_lancamento_doc
                             )                   c on (a.sq_lancamento_doc  = c.sq_lancamento_doc)
                left     join ac_acordo_nota     d on (a.sq_acordo_nota     = d.sq_acordo_nota)
                  left   join fn_tipo_documento  e on (d.sq_tipo_documento  = e.sq_tipo_documento)
                  left   join (select x.sq_acordo_nota, x.sq_acordo_parcela, 
                                      case z.abrange_inicial   when 'S' then y.valor_inicial else 0 end as valor_inicial, 
                                      case z.abrange_acrescimo when 'S' then y.valor_excedente else 0 end as valor_excedente, 
                                      case z.abrange_reajuste  when 'S' then y.valor_reajuste else 0 end as valor_reajuste
                                 from ac_parcela_nota               x
                                      inner join ac_acordo_parcela  y on (x.sq_acordo_parcela  = y.sq_acordo_parcela)
                                      inner join ac_acordo_nota     z on (x.sq_acordo_nota     = z.sq_acordo_nota)
                              )                  f on (d.sq_acordo_nota     = f.sq_acordo_nota and
                                                       f.sq_acordo_parcela  = a1.sq_acordo_parcela
                                                      )
                left     join (select x.sq_lancamento_doc, sum(x.valor) acrescimo
                                 from fn_documento_valores         x
                                      inner join fn_lancamento_doc y on (x.sq_lancamento_doc = y.sq_lancamento_doc)
                                      inner join fn_valores        z on (x.sq_valores        = z.sq_valores)
                                where z.tipo = 'A' -- Acréscimos
                                  and (p_chave     is null or (p_chave     is not null and y.sq_siw_solicitacao = p_chave))
                                  and (p_chave_aux is null or (p_chave_aux is not null and y.sq_lancamento_doc  = p_chave_aux))
                              group by x.sq_lancamento_doc
                             )                   g on (a.sq_lancamento_doc  = g.sq_lancamento_doc)
                left     join (select x.sq_lancamento_doc, sum(x.valor) deducao
                                 from fn_documento_valores         x
                                      inner join fn_lancamento_doc y on (x.sq_lancamento_doc = y.sq_lancamento_doc)
                                      inner join fn_valores        z on (x.sq_valores        = z.sq_valores)
                                where z.tipo = 'S' -- Deduções
                                  and (p_chave     is null or (p_chave     is not null and y.sq_siw_solicitacao = p_chave))
                                  and (p_chave_aux is null or (p_chave_aux is not null and y.sq_lancamento_doc  = p_chave_aux))
                              group by x.sq_lancamento_doc
                             )                   h on (a.sq_lancamento_doc  = h.sq_lancamento_doc)
          where (p_chave     is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux is null or (p_chave_aux is not null and a.sq_lancamento_doc  = p_chave_aux))
            and (p_benef     is null or (p_benef     is not null and a1.pessoa            = p_benef))
            and (p_tipo_doc  is null or (p_tipo_doc  is not null and b.sq_tipo_documento  = p_tipo_doc))
            and (p_numero    is null or (p_numero    is not null and a.numero             = p_numero))
            and (p_data      is null or (p_data      is not null and a.data               = p_data))
            and (p_valor     is null or (p_valor     is not null and a.valor              = p_valor))
            and (p_restricao is null or ((p_restricao <> 'DOCS' and p_restricao <> 'NOTA')    or
                                         ((p_restricao = 'DOCS' and a.sq_acordo_nota is null) or
                                          (p_restricao = 'NOTA' and a.sq_acordo_nota is not null)
                                         )
                                        )
                );
   End If;
End SP_GetLancamentoDoc;
/
