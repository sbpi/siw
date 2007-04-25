create or replace procedure SP_GetAcordoNota
   (p_cliente           in number   default null,
    p_chave             in number   default null,
    p_chave_aux         in number   default null,
    p_sq_tipo_documento in number   default null,
    p_sq_acordo_aditivo in varchar2 default null,
    p_numero            in varchar2 default null,
    p_data              in date     default null,
    p_restricao         in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera as notas do contrato
   If p_restricao is null Then
      open p_result for     
         select a.sq_acordo_nota, a.sq_siw_solicitacao, a.sq_tipo_documento, a.sq_acordo_outra_parte, 
                a.sq_acordo_aditivo, a.numero, a.data, a.valor, a.sq_lcfonte_recurso, 
                a.sq_especificacao_despesa, a.observacao, a.abrange_inicial, a.abrange_acrescimo,
                a.abrange_reajuste,
                case a.abrange_inicial   when 'S' then 'IN' else null end as sg_inicial,
                case a.abrange_acrescimo when 'S' then 'EX' else null end as sg_acrescimo,
                case a.abrange_reajuste  when 'S' then 'RJ' else null end as sg_reajuste,
                to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data,
                c.nome nm_tipo_documento, c.sigla sg_tipo_documento,
                f.nome_resumido nm_outra_parte,
                e.codigo cd_aditivo, e.sq_cc cc_aditivo,
                g.sq_cc cc_acordo,
                coalesce(h.vl_liquidado,0) as vl_liquidado,
                coalesce(h.vl_pago,0) as vl_pago
           from ac_acordo_nota                     a  
                inner   join ac_acordo             b on (a.sq_siw_solicitacao    = b.sq_siw_solicitacao)
                  inner join siw_solicitacao       g on (b.sq_siw_solicitacao    = g.sq_siw_solicitacao)
                inner   join fn_tipo_documento     c on (a.sq_tipo_documento     = c.sq_tipo_documento)
                left    join ac_acordo_outra_parte d on (a.sq_acordo_outra_parte = d.sq_acordo_outra_parte)
                  left  join co_pessoa             f on (d.outra_parte           = f.sq_pessoa)
                left    join ac_acordo_aditivo     e on (a.sq_acordo_aditivo     = e.sq_acordo_aditivo)
                left    join (select distinct x.sq_acordo_nota, z.valor as vl_liquidado,
                                     case when y.quitacao is null then 0 else z.valor end as vl_pago
                                 from fn_lancamento_doc            x
                                      inner   join fn_lancamento   y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and
                                                                         y.sq_acordo_parcela  is not null
                                                                        )
                                        inner join siw_solicitacao z on (y.sq_siw_solicitacao = z.sq_siw_solicitacao)
                                where x.sq_acordo_nota is not null
                              )                    h on (a.sq_acordo_nota     = h.sq_acordo_nota)
          where b.cliente = p_cliente
            and ((p_chave             is null) or (p_chave             is not null and a.sq_acordo_nota     = p_chave))
            and ((p_chave_aux         is null) or (p_chave_aux         is not null and a.sq_siw_solicitacao = p_chave_aux))
            and ((p_sq_tipo_documento is null) or (p_sq_tipo_documento is not null and a.sq_tipo_documento  = p_sq_tipo_documento))
            and ((p_sq_acordo_aditivo is null) or (p_sq_acordo_aditivo is not null and a.sq_acordo_aditivo  = p_sq_acordo_aditivo))
            and ((p_numero            is null) or (p_numero            is not null and a.numero             = p_numero))
            and ((p_data              is null) or (p_data              is not null and a.data = p_data));
   Elsif p_restricao = 'LANCAMENTO' Then
      open p_result for
         select c.sq_siw_solicitacao
           from ac_acordo_nota a
                inner join ac_parcela_nota b on (a.sq_acordo_nota    = b.sq_acordo_nota)
                inner join fn_lancamento   c on (b.sq_acordo_parcela = c.sq_acordo_parcela)
          where ((p_chave is null) or (p_chave  is not null and a.sq_acordo_nota  = p_chave));
   Elsif p_restricao = 'PARCELA' Then
      open p_result for
         select a.sq_acordo_parcela, a.sq_acordo_nota,
                b.numero, b.abrange_inicial, b.abrange_acrescimo, b.abrange_reajuste, b.data,
                to_char(b.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data,
                c.valor_inicial inicial_parc, c.valor_excedente excedente_parc, 
                c.valor_reajuste reajuste_parc,
                d.valor_inicial inicial_lanc, d.valor_excedente excedente_lanc, 
                d.valor_reajuste reajuste_lanc
           from ac_parcela_nota                a
                inner   join ac_acordo_nota    b on (a.sq_acordo_nota    = b.sq_acordo_nota)
                inner   join ac_acordo_parcela c on (a.sq_acordo_parcela = c.sq_acordo_parcela)
                  left  join fn_lancamento_doc d on (b.sq_acordo_nota    = d.sq_acordo_nota)
          where ((p_chave     is null) or (p_chave     is not null and a.sq_acordo_nota    = p_chave))
            and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_acordo_parcela = p_chave_aux));
   End If;
end SP_GetAcordoNota;
/
