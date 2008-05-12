create or replace procedure SP_GetAcordoAditivo
   (p_cliente     in number   default null,
    p_aditivo     in number   default null,
    p_contrato    in number   default null,
    p_protocolo   in number   default null,
    p_codigo      in varchar2 default null,
    p_inicio      in date     default null,
    p_fim         in date     default null,
    p_prorrogacao in varchar2 default null,
    p_revisao     in varchar2 default null,
    p_acrescimo   in varchar2 default null,
    p_supressao   in varchar2 default null,
    p_restricao   in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os aditivos do contrato
   If p_restricao is null Then
      open p_result for
         select a.sq_acordo_aditivo, a.sq_siw_solicitacao, a.protocolo, a.codigo, a.objeto, 
                a.inicio, a.fim, a.duracao, a.documento_origem, a.documento_data, a.variacao_valor,
                a.prorrogacao, a.revisao, a.acrescimo, a.supressao, a.observacao, 
                a.valor_reajuste, a.parcela_reajustada, a.sq_cc, a.valor_inicial, a.parcela_inicial, 
                a.valor_acrescimo, a.parcela_acrescida, a.valor_aditivo, a.parcela_aditivo,
                case a.prorrogacao when 'S' then 'Sim' else 'Não' end nm_prorrogacao,
                case a.revisao     when 'S' then 'Sim' else 'Não' end nm_revisao,
                case a.acrescimo
                     when 'S' then 'Acréscimo'
                     else case a.supressao
                               when 'S'
                               then 'Supressão'
                               else 'Não se aplica'
                          end 
                end nm_tipo,
                b.cliente,
                c.prazo_indeterm,
                d.qtd_parcela,
                AC_RetornaValorAditivo(a.sq_siw_solicitacao, a.sq_acordo_aditivo) as vl_parcela
           from ac_acordo_aditivo              a
                inner   join ac_acordo         b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                  inner join ac_tipo_acordo    c on (b.sq_tipo_acordo     = c.sq_tipo_acordo)
                left    join (select count(x.sq_acordo_parcela) qtd_parcela, x.sq_acordo_aditivo
                                from ac_acordo_parcela x
                               group by x.sq_acordo_aditivo
                             )                 d on (a.sq_acordo_aditivo = d.sq_acordo_aditivo)
          where b.cliente       = p_cliente
            and ((p_aditivo     is null) or (p_aditivo      is not null and a.sq_acordo_aditivo  = p_aditivo))      
            and ((p_contrato    is null) or (p_contrato     is not null and a.sq_siw_solicitacao = p_contrato))
            and ((p_protocolo   is null) or (p_protocolo    is not null and a.protocolo          = p_protocolo))
            and ((p_codigo      is null) or (p_codigo       is not null and a.codigo like '%'||p_codigo||'%'))
            and ((p_inicio      is null) or (p_inicio       is not null and ((a.inicio between p_inicio and p_fim) or
                                                                             (a.fim    between p_inicio and p_fim) or
                                                                             (p_inicio between a.inicio and b.fim) or
                                                                             (p_fim    between a.inicio and b.fim)
                                                                            )
                                            )
                )
            and ((p_prorrogacao is null) or (p_prorrogacao  is not null and a.prorrogacao  = p_prorrogacao))
            and ((p_revisao     is null) or (p_revisao      is not null and a.revisao      = p_revisao))
            and ((p_acrescimo   is null) or (p_acrescimo    is not null and (a.acrescimo    = p_acrescimo or a.supressao = p_acrescimo)))
            and ((p_supressao   is null) or (p_supressao    is not null and a.supressao    = p_supressao));
   Elsif p_restricao = 'EXISTE' Then
      open p_result for
         select count(a.sq_acordo_aditivo) existe
           from ac_acordo_aditivo            a
          where (a.acrescimo = 'S' or a.supressao = 'S')
            and ((p_aditivo     is null) or (p_aditivo      is not null and a.sq_acordo_aditivo  <> p_aditivo))      
            and ((p_contrato    is null) or (p_contrato     is not null and a.sq_siw_solicitacao = p_contrato))
            and ((p_prorrogacao is null) or (p_prorrogacao  is not null and a.prorrogacao        = p_prorrogacao))
            and ((p_revisao     is null) or (p_revisao      is not null and a.revisao            = p_revisao))
            and ((p_inicio      is null) or (p_inicio       is not null and ((a.inicio between p_inicio and p_fim) or
                                                                             (a.fim    between p_inicio and p_fim) or
                                                                             (p_inicio between a.inicio and a.fim) or
                                                                             (p_fim    between a.inicio and a.fim)
                                                                            )
                                            )
                );
   Elsif substr(p_restricao,1,10) = 'LANCAMENTO' Then
      open p_result for
         select c.sq_siw_solicitacao, d.sq_menu
           from ac_acordo_aditivo                  a
                inner       join ac_acordo_parcela b on (a.sq_acordo_aditivo  = b.sq_acordo_aditivo)
                  inner     join fn_lancamento     c on (b.sq_acordo_parcela  = c.sq_acordo_parcela)
                    inner   join siw_solicitacao   d on (c.sq_siw_solicitacao = d.sq_siw_solicitacao)
                      inner join siw_tramite       e on (d.sq_siw_tramite     = e.sq_siw_tramite)
          where ((substr(p_restricao,11,1)='E' and coalesce(e.sigla,'-') <> 'CA')         or 
                 (substr(p_restricao,11,1) in ('I','A') and coalesce(e.sigla,'-') = 'AT') or
                 (substr(p_restricao,11,1)='F' and coalesce(e.sigla,'-') = 'CA')
                )
            and ((p_aditivo     is null) or (p_aditivo      is not null and a.sq_acordo_aditivo = p_aditivo))
            and ((p_contrato    is null) or (p_contrato     is not null and a.sq_siw_solicitacao = p_contrato))
            and ((p_inicio      is null) or (p_inicio       is not null and ((c.vencimento between p_inicio and p_fim)
                                                                            )
                                            )
                )
       UNION
         select c.sq_siw_solicitacao, d.sq_menu
           from ac_acordo                          a
                inner       join ac_acordo_parcela b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                  inner     join fn_lancamento     c on (b.sq_acordo_parcela  = c.sq_acordo_parcela)
                    inner   join siw_solicitacao   d on (c.sq_siw_solicitacao = d.sq_siw_solicitacao)
                      inner join siw_tramite       e on (d.sq_siw_tramite     = e.sq_siw_tramite)
          where substr(p_restricao,11,1)<>'E'
            and ((substr(p_restricao,11,1) in ('I','A') and coalesce(e.sigla,'-') = 'AT') or
                 (substr(p_restricao,11,1)='F' and coalesce(e.sigla,'-') = 'CA')
                )
            and ((p_contrato    is null) or (p_contrato     is not null and a.sq_siw_solicitacao = p_contrato))
            and ((p_inicio      is null) or (p_inicio       is not null and ((c.vencimento between p_inicio and p_fim)
                                                                            )
                                            )
                );
   Elsif p_restricao = 'PARCELAS' Then
      open p_result for
         select a.sq_acordo_aditivo,d.qtd_parcela, a.prorrogacao, a.acrescimo, a.supressao, a.valor_aditivo
           from ac_acordo_aditivo              a
                inner   join ac_acordo         b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                  inner join ac_tipo_acordo    c on (b.sq_tipo_acordo     = c.sq_tipo_acordo)
                left    join (select count(x.sq_acordo_parcela) qtd_parcela, x.sq_acordo_aditivo
                                from ac_acordo_parcela x
                               group by x.sq_acordo_aditivo
                             )                 d on (a.sq_acordo_aditivo = d.sq_acordo_aditivo)
          where ((p_contrato   is null) or (p_contrato    is not null and a.sq_siw_solicitacao = p_contrato));
   End If;
end SP_GetAcordoAditivo;
/
