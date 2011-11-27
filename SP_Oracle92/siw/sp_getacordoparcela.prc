create or replace procedure SP_GetAcordoParcela
   (p_chave       in number   default null,
    p_chave_aux   in number   default null,
    p_restricao   in varchar2 default null,
    p_outra_parte in varchar2 default null,
    p_dt_ini      in date     default null,
    p_dt_fim      in date     default null,
    p_usuario     in number   default null,
    p_fase        in varchar2 default null,
    p_menu        in number   default null,
    p_aditivo     in number default null,
    p_result      out sys_refcursor) is
begin
   -- Recupera os dados de uma parcela ou as parcelas de um acordo
   -- dependendo dos parâmetros informados
   If p_restricao is null or p_restricao = 'PERIODO' or p_restricao = 'PARCELA' Then
      open p_result for 
         select a.sq_acordo_parcela, a.sq_siw_solicitacao, a.ordem, a.emissao, a.vencimento, 
                a.documento_interno, a.documento_externo, a.observacao, a.valor, a.inicio, a.fim,
                a.sq_acordo_aditivo, a.valor_inicial, a.valor_excedente, a.valor_reajuste,
                b.sq_siw_solicitacao as sq_lancamento, b.codigo_interno as cd_lancamento, b.quitacao,
                b.vencimento as dt_lancamento, b.valor as vl_lancamento, b.sg_tramite as fn_tramite,
                b.referencia_inicio, b.referencia_fim,
                coalesce(b1.qtd,0) as qt_financeiro,
                c.prorrogacao, c.acrescimo, c.supressao, c.revisao
           from ac_acordo_parcela                  a
                left     join (select x.sq_acordo_parcela, x.sq_siw_solicitacao, x.quitacao, y.valor,
                                      y.codigo_interno, x.vencimento, x.referencia_inicio, x.referencia_fim,
                                      z.sigla as sg_tramite
                                 from fn_lancamento                x
                                      inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                        inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                         nvl(z.sigla,'-')     <> 'CA'
                                                                        )
                              )                    b on (a.sq_acordo_parcela = b.sq_acordo_parcela)
                left     join (select x.sq_acordo_parcela, count(*) as qtd
                                 from fn_lancamento                x
                                      inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                        inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                         nvl(z.sigla,'-')     <> 'CA'
                                                                        )
                               group by x.sq_acordo_parcela
                              )                   b1 on (a.sq_acordo_parcela = b1.sq_acordo_parcela)
                left     join ac_acordo_aditivo    c on (a.sq_acordo_aditivo = c.sq_acordo_aditivo and
                                                         a.sq_siw_solicitacao = c.sq_siw_solicitacao
                                                        )
                inner    join ac_acordo            d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
          where (p_chave             is null or (p_chave             is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux         is null or (p_chave_aux         is not null and a.sq_acordo_parcela  = p_chave_aux))
            and (p_aditivo           is null or (p_aditivo           is not null and c.sq_acordo_aditivo  = p_aditivo))
            and ((p_restricao <> 'PERIODO'    and p_restricao <> 'PARCELA' and p_restricao is not null) or
                  ((p_restricao = 'PERIODO'   and (a.inicio between p_dt_ini and p_dt_fim) or 
                                                  (a.fim    between p_dt_ini and p_dt_fim) or
                                                  (p_dt_ini between a.inicio and a.fim) or
                                                  (p_dt_fim between a.inicio and a.fim)
                   ) or
                   (p_restricao is null       and (p_dt_ini is null or (p_dt_ini is not null and b.vencimento between p_dt_ini and p_dt_fim))) or
                   (p_restricao = 'PARCELA'   and a.sq_acordo_aditivo is null)
                  )
                 );
   Elsif p_restricao = 'NOTA' or p_restricao = 'VALIDA' Then
      open p_result for 
         select a.sq_acordo_parcela, a.sq_siw_solicitacao, a.ordem, a.emissao, a.vencimento, a.quitacao,
                a.documento_interno, a.documento_externo, a.observacao, a.valor, a.inicio, a.fim,
                a.sq_acordo_aditivo, a.valor_inicial, a.valor_excedente, a.valor_reajuste,
                c.prorrogacao, c.acrescimo, c.supressao, c.revisao
           from ac_acordo_parcela                  a
                left     join ac_acordo_aditivo    c on (a.sq_acordo_aditivo = c.sq_acordo_aditivo and
                                                         a.sq_siw_solicitacao = c.sq_siw_solicitacao
                                                        )
                inner    join ac_acordo            d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
          where (p_chave             is null or (p_chave             is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux         is null or (p_chave_aux         is not null and a.sq_acordo_parcela  = p_chave_aux))
            and (p_restricao         = 'VALIDA' or
                 (p_restricao        <> 'VALIDA' and
                  ((p_aditivo is null     and coalesce(a.inicio,a.vencimento) between d.inicio and d.fim) or
                   (p_aditivo is not null and c.sq_acordo_aditivo = p_aditivo)
                  )
                 )
                );
   Elsif p_restricao = 'RESFIN' Then
      open p_result for 
        select a.codigo_interno, a.sq_siw_solicitacao, c.inicio, c.fim, d.sq_acordo_aditivo, d.codigo, d.ini_aditivo, d.fim_aditivo, d.valor valor_previsto, e.liquidado valor_liquidado, e.pago as valor_pago
          from siw_solicitacao        a
               inner join siw_tramite b on (a.sq_siw_tramite     = b.sq_siw_tramite)
               inner join ac_acordo   c on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
               inner join (select k.sq_siw_solicitacao, l.sq_acordo_aditivo, l.codigo, l.inicio as ini_aditivo, l.fim as fim_aditivo, nvl(sum(nvl(k.valor,0)),0) as valor
                             from ac_acordo_parcela                  k
                                  inner       join ac_acordo        k1 on (k.sq_siw_solicitacao  = k1.sq_siw_solicitacao)
                                    inner     join siw_solicitacao  k2 on (k1.sq_siw_solicitacao = k2.sq_siw_solicitacao)
                                  left        join ac_acordo_aditivo l on (k.sq_acordo_aditivo   = l.sq_acordo_aditivo and l.prorrogacao = 'S')
                           group by k.sq_siw_solicitacao, l.sq_acordo_aditivo, l.codigo, l.inicio, l.fim
                          )           d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
               left  join (select k.sq_siw_solicitacao, l.sq_acordo_aditivo, 
                                  nvl(sum(nvl(m1.valor,0) + nvl(p1.valor,0)),0) liquidado, 
                                  nvl(sum(case when m1.conclusao is not null then nvl(m1.valor,0) else 0 end + case when p1.conclusao is not null then nvl(p1.valor,0) else 0 end),0) pago
                             from ac_acordo_parcela                  k
                                  inner       join ac_acordo        k1 on (k.sq_siw_solicitacao  = k1.sq_siw_solicitacao)
                                    inner     join siw_solicitacao  k2 on (k1.sq_siw_solicitacao = k2.sq_siw_solicitacao)
                                  left        join ac_acordo_aditivo l on (k.sq_acordo_aditivo   = l.sq_acordo_aditivo and l.prorrogacao = 'S')
                                  inner       join fn_lancamento     m on (k.sq_acordo_parcela   = m.sq_acordo_parcela)
                                    inner     join siw_solicitacao  m1 on (m.sq_siw_solicitacao  = m1.sq_siw_solicitacao)
                                      inner   join siw_tramite      m2 on (m1.sq_siw_tramite     = m2.sq_siw_tramite and m2.sigla <> 'CA')
                                    left      join fn_lancamento_doc n on (m.sq_siw_solicitacao  = n.sq_siw_solicitacao)
                                      left    join fn_imposto_doc    o on (n.sq_lancamento_doc   = o.sq_lancamento_doc)
                                        left  join fn_lancamento     p on (o.solic_imposto       = p.sq_siw_solicitacao)
                                        left  join siw_solicitacao  p1 on (p.sq_siw_solicitacao  = p1.sq_siw_solicitacao)
                           group by k.sq_siw_solicitacao, l.sq_acordo_aditivo
                          ) e on (a.sq_siw_solicitacao = e.sq_siw_solicitacao and coalesce(d.sq_acordo_aditivo,0) = coalesce(e.sq_acordo_aditivo,0))
          where (p_chave     is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
            and (p_aditivo   is null or (p_aditivo   is not null and d.sq_acordo_aditivo  = p_aditivo))
            and (p_dt_ini    is null or (p_dt_ini    is not null and (a.inicio      between p_dt_ini      and p_dt_fim or 
                                                                      a.fim         between p_dt_ini      and p_dt_fim or
                                                                      p_dt_ini      between a.inicio      and a.fim or
                                                                      p_dt_fim      between a.inicio      and a.fim or
                                                                      d.ini_aditivo between p_dt_ini      and p_dt_fim or 
                                                                      d.fim_aditivo between p_dt_ini      and p_dt_fim or
                                                                      p_dt_ini      between d.ini_aditivo and d.fim_aditivo or
                                                                      p_dt_fim      between d.ini_aditivo and d.fim_aditivo
                                                                     )
                                        )
                )
        order by a.sq_siw_solicitacao, d.sq_acordo_aditivo;
   Elsif p_restricao = 'RELJUR' Then
      open p_result for 
         select a.sq_acordo_parcela, a.sq_siw_solicitacao, a.ordem, a.emissao, a.vencimento, a.quitacao,
                a.documento_interno, a.documento_externo, a.observacao, a.valor, a.inicio, a.fim,
                a.sq_acordo_aditivo,
                c.prorrogacao, c.acrescimo, c.supressao, c.revisao,
                e.data as dt_nota, e.abrange_inicial, e.abrange_acrescimo, e.abrange_reajuste,
                f.sq_siw_solicitacao as sq_lancamento, f.codigo_interno as cd_lancamento, 
                f.vencimento as dt_lancamento, f.valor as vl_lancamento, f.sg_tramite as fn_tramite,
                f.processo, f.referencia_inicio, f.referencia_fim,
                f.sq_acordo_nota, f.valor_inicial, f.valor_excedente, f.valor_reajuste
           from ac_acordo_parcela                   a
                left      join ac_acordo_aditivo    c on (a.sq_acordo_aditivo = c.sq_acordo_aditivo and
                                                          a.sq_siw_solicitacao = c.sq_siw_solicitacao
                                                         )
                left      join ac_parcela_nota      d on (a.sq_acordo_parcela  = d.sq_acordo_parcela)
                  left    join ac_acordo_nota       e on (d.sq_acordo_nota     = e.sq_acordo_nota)
                    left  join (select w.sq_acordo_nota, w.valor_inicial, w.valor_excedente, w.valor_reajuste,
                                       x.sq_acordo_parcela, x.sq_siw_solicitacao, y.valor,
                                       y.codigo_interno, x.vencimento, 
                                       z.sigla as sg_tramite, x.processo, x.referencia_inicio, x.referencia_fim
                                  from fn_lancamento_doc              w
                                       inner     join fn_lancamento   x on (w.sq_siw_solicitacao = x.sq_siw_solicitacao and
                                                                            x.sq_acordo_parcela  is not null
                                                                           )
                                         inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                           inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                           nvl(z.sigla,'-')     <> 'CA'
                                                                             )
                                 where w.sq_acordo_nota is not null
                               )                    f on (a.sq_acordo_parcela  = f.sq_acordo_parcela and 
                                                          e.sq_acordo_nota     = f.sq_acordo_nota
                                                         )
          where (p_chave     is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux is null or (p_chave_aux is not null and a.sq_acordo_parcela  = p_chave_aux))
            and (p_aditivo   is null or (p_aditivo   is not null and c.sq_acordo_aditivo  = p_aditivo))
            and (p_dt_ini    is null or (p_dt_ini    is not null and (a.inicio between p_dt_ini and p_dt_fim) or (a.fim between p_dt_ini and p_dt_fim)));
   Elsif p_restricao = 'CADASTRO' Then
      open p_result for 
        select a.sq_siw_solicitacao, a.sq_solic_pai, a.solicitante, a.sq_unidade, a.sq_cc, 
               a.codigo_interno cd_acordo, b.sq_forma_pagamento, b.sq_tipo_pessoa, b.outra_parte,
               b.objeto,
               coalesce(b1.existe,0) as notas_acordo,
               c.sq_acordo_parcela, c.ordem, c.vencimento, c.valor, c.quitacao, c.inicio, c.fim,
               coalesce(c1.existe,0) as notas_parcela,
               g.nome_resumido,
               f.sq_tipo_lancamento,
               case when h.sq_pessoa is null then i.cnpj else h.cpf end as cnpjcpf
          from siw_solicitacao                          a
               inner            join ac_acordo          b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                   left outer   join (select x.sq_siw_solicitacao, count(*) as existe
                                        from ac_acordo_nota x
                                      group by x.sq_siw_solicitacao
                                     )                  b1 on (b.sq_siw_solicitacao = b1.sq_siw_solicitacao)
                 inner          join co_pessoa          g  on (b.outra_parte        = g.sq_pessoa)
                   left outer   join co_pessoa_fisica   h  on (g.sq_pessoa          = h.sq_pessoa)
                   left outer   join co_pessoa_juridica i  on (g.sq_pessoa          = i.sq_pessoa)
                 inner          join siw_tramite        j  on (a.sq_siw_tramite     = j.sq_siw_tramite and
                                                               (p_fase              is null or (p_fase is not null and (0 <> InStr(p_fase,Nvl(j.sigla,'-')))))
                                                              )
                 inner          join ac_acordo_parcela  c  on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                   left outer   join (select x.sq_acordo_parcela, count(*) as existe
                                        from ac_parcela_nota x
                                      group by x.sq_acordo_parcela
                                     )                  c1 on (c.sq_acordo_parcela  = c1.sq_acordo_parcela)
                   left outer   join (select x.sq_acordo_parcela, count(*) as existe
                                        from fn_lancamento                x
                                             inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                                'CA'                 <> Nvl(z.sigla,'CA')
                                                                               )
                                      group by x.sq_acordo_parcela
                                     )                  d  on (c.sq_acordo_parcela  = d.sq_acordo_parcela)
                 left outer     join (select w.sq_siw_solicitacao, max(w.sq_acordo_parcela) as sq_acordo_parcela, max(x.sq_siw_solicitacao) as sq_lancamento
                                        from ac_acordo_parcela            w
                                             inner join fn_lancamento     x on (w.sq_acordo_parcela = x.sq_acordo_parcela)
                                             inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                                'CA'                 <> Nvl(z.sigla,'CA')
                                                                               )
                                      group by w.sq_siw_solicitacao
                                     )                  e on (b.sq_siw_solicitacao = e.sq_siw_solicitacao)
                   left outer   join (select x.sq_acordo_parcela, x.sq_siw_solicitacao, x.sq_tipo_lancamento
                                        from fn_lancamento                x
                                             inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                                'CA'                 <> Nvl(z.sigla,'CA')
                                                                               )
                                     )                  f on (e.sq_lancamento      = f.sq_siw_solicitacao)
         where (b.financeiro_unico = 'N' or (b.financeiro_unico = 'S' and coalesce(d.existe,0) = 0))
           and (p_menu        is null or (p_menu        is not null and a.sq_menu            = p_menu))
           and (p_chave       is null or (p_chave       is not null and a.sq_siw_solicitacao = p_chave))
           and (p_chave_aux   is null or (p_chave_aux   is not null and c.sq_acordo_parcela  = p_chave_aux))
           and (p_outra_parte is null or (p_outra_parte is not null and (acentos(g.nome,null) like '%'||acentos(p_outra_parte,null)||'%' or acentos(g.nome_resumido,null) like '%'||acentos(p_outra_parte,null)||'%')))
           and (p_dt_ini      is null or (p_dt_ini      is not null and c.vencimento         between p_dt_ini and p_dt_fim));
   End If;
End SP_GetAcordoParcela;
/
