create or replace FUNCTION SP_GetAcordoNota
   (p_cliente           numeric,
    p_chave             numeric,
    p_chave_aux         numeric,
    p_sq_tipo_documento numeric,
    p_sq_acordo_aditivo varchar,
    p_numero            varchar,
    p_dt_ini            date,
    p_dt_fim            date,
    p_restricao         varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as notas do contrato
   If p_restricao is null Then
      open p_result for     
         select a.sq_acordo_nota, a.sq_siw_solicitacao, a.sq_tipo_documento, a.sq_acordo_outra_parte, 
                a.sq_acordo_aditivo, a.numero, a.data, a.valor, a.sq_lcfonte_recurso, 
                a.sq_especificacao_despesa, a.observacao, a.abrange_inicial, a.abrange_acrescimo,
                a.abrange_reajuste, a.data_cancelamento, a.valor_cancelamento,
                case a.abrange_inicial   when 'S' then 'IN' else null end as sg_inicial,
                case a.abrange_acrescimo when 'S' then 'EX' else null end as sg_acrescimo,
                case a.abrange_reajuste  when 'S' then 'RJ' else null end as sg_reajuste,
                c.nome nm_tipo_documento, c.sigla sg_tipo_documento, c.detalha_item,
                f.nome_resumido nm_outra_parte,
                e.codigo cd_aditivo, e.sq_cc cc_aditivo,
                g.sq_cc cc_acordo,
                case a.abrange_inicial||a.abrange_acrescimo||a.abrange_reajuste
                     when 'SSS' then h.vl_inicial + h.vl_excedente + h.vl_reajuste
                     when 'SNS' then h.vl_inicial + h.vl_reajuste
                     when 'SSN' then h.vl_inicial + h.vl_excedente
                     when 'SNN' then h.vl_inicial
                     when 'NSS' then h.vl_excedente + h.vl_reajuste
                     when 'NNS' then h.vl_reajuste
                     when 'NSN' then h.vl_excedente
                end as vl_liquidado,
                case when h.quitacao is null
                     then 0
                     else case a.abrange_inicial||a.abrange_acrescimo||a.abrange_reajuste
                               when 'SSS' then h.vl_inicial + h.vl_excedente + h.vl_reajuste
                               when 'SNS' then h.vl_inicial + h.vl_reajuste
                               when 'SSN' then h.vl_inicial + h.vl_excedente
                               when 'SNN' then h.vl_inicial
                               when 'NSS' then h.vl_excedente + h.vl_reajuste
                               when 'NNS' then h.vl_reajuste
                               when 'NSN' then h.vl_excedente
                          end
                end as vl_pago,
                i.vl_cancelamento
           from ac_acordo_nota                     a  
                inner   join ac_acordo             b on (a.sq_siw_solicitacao    = b.sq_siw_solicitacao)
                  inner join siw_solicitacao       g on (b.sq_siw_solicitacao    = g.sq_siw_solicitacao)
                inner   join fn_tipo_documento     c on (a.sq_tipo_documento     = c.sq_tipo_documento)
                left    join ac_acordo_outra_parte d on (a.sq_acordo_outra_parte = d.sq_acordo_outra_parte)
                  left  join co_pessoa             f on (d.outra_parte           = f.sq_pessoa)
                left    join ac_acordo_aditivo     e on (a.sq_acordo_aditivo     = e.sq_acordo_aditivo)
                left    join (select x.sq_acordo_nota,y.quitacao, 
                                     sum(x.valor_inicial) as vl_inicial, 
                                     sum(x.valor_reajuste) as vl_reajuste,
                                     sum(x.valor_excedente) as vl_excedente
                                 from fn_lancamento_doc            x
                                      inner   join fn_lancamento   y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and
                                                                         y.sq_acordo_parcela  is not null
                                                                        )
                                        inner join siw_solicitacao z on (y.sq_siw_solicitacao = z.sq_siw_solicitacao)
                                where x.sq_acordo_nota is not null
                               group by x.sq_acordo_nota, y.quitacao
                              )                    h on (a.sq_acordo_nota     = h.sq_acordo_nota)
                left    join (select x.sq_acordo_nota, sum(x.valor) vl_cancelamento
                                from ac_nota_cancelamento x
                               group by x.sq_acordo_nota
                              )                    i on (a.sq_acordo_nota     = i.sq_acordo_nota)
          where b.cliente = p_cliente
            and ((p_chave             is null) or (p_chave             is not null and a.sq_acordo_nota     = p_chave))
            and ((p_chave_aux         is null) or (p_chave_aux         is not null and a.sq_siw_solicitacao = p_chave_aux))
            and ((p_sq_tipo_documento is null) or (p_sq_tipo_documento is not null and a.sq_tipo_documento  = p_sq_tipo_documento))
            and ((p_sq_acordo_aditivo is null) or (p_sq_acordo_aditivo is not null and a.sq_acordo_aditivo  = p_sq_acordo_aditivo))
            and ((p_numero            is null) or (p_numero            is not null and a.numero             = p_numero))
            and ((p_dt_ini            is null) or (p_dt_ini            is not null and a.data between p_dt_ini and p_dt_fim));
   Elsif p_restricao = 'LANCAMENTO' Then
      open p_result for
         select c.sq_siw_solicitacao
           from ac_acordo_nota                   a
                inner     join ac_parcela_nota   b on (a.sq_acordo_nota    = b.sq_acordo_nota)
                inner     join fn_lancamento     c on (b.sq_acordo_parcela = c.sq_acordo_parcela)
                  inner   join siw_solicitacao   d on (c.sq_siw_solicitacao = d.sq_siw_solicitacao)
                    inner join siw_tramite       e on (d.sq_siw_tramite     = e.sq_siw_tramite and
                                                       'CA'                 <> coalesce(e.sigla,'-')
                                                      )
          where ((p_chave is null) or (p_chave  is not null and a.sq_acordo_nota  = p_chave));
   Elsif p_restricao = 'PARCELA' Then
      open p_result for
         select a.sq_acordo_parcela, a.sq_acordo_nota,
                b.numero, b.abrange_inicial, b.abrange_acrescimo, b.abrange_reajuste, b.data,
                c.valor_inicial inicial_parc, c.valor_excedente excedente_parc, 
                c.valor_reajuste reajuste_parc,
                d.valor_inicial inicial_lanc, d.valor_excedente excedente_lanc, 
                d.valor_reajuste reajuste_lanc
           from ac_parcela_nota                a
                inner   join ac_acordo_nota    b on (a.sq_acordo_nota    = b.sq_acordo_nota)
                inner   join ac_acordo_parcela c on (a.sq_acordo_parcela = c.sq_acordo_parcela)
                  left  join fn_lancamento_doc d on (b.sq_acordo_nota    = d.sq_acordo_nota)
          where a.sq_acordo_nota    = p_chave
            and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_acordo_parcela = p_chave_aux));
   Elsif p_restricao = 'PARCELAS' Then
      open p_result for
         select a.sq_acordo_parcela, a.sq_acordo_nota,
                b.numero, b.abrange_inicial, b.abrange_acrescimo, b.abrange_reajuste, b.data,
                b.sq_tipo_documento,
                b.valor,
                c.valor_inicial inicial_parc, c.valor_excedente excedente_parc, 
                c.valor_reajuste reajuste_parc,
                (case b.abrange_inicial   when 'S' Then c.valor_inicial   else 0 end + 
                 case b.abrange_acrescimo when 'S' Then c.valor_excedente else 0 end +
                 case b.abrange_reajuste  when 'S' Then c.valor_reajuste  else 0 end) as valor_total
           from ac_parcela_nota                a
                inner   join ac_acordo_nota    b on (a.sq_acordo_nota    = b.sq_acordo_nota)
                inner   join ac_acordo_parcela c on (a.sq_acordo_parcela = c.sq_acordo_parcela)
          where ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_acordo_parcela = p_chave_aux));
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;