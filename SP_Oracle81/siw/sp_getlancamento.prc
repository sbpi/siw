create or replace procedure SP_GetLancamento
   (p_cliente   in number,
    p_restricao in varchar2 default null,
    p_dt_ini    in date     default null,
    p_dt_fim    in date     default null,
    p_sq_pessoa in number   default null,
    p_fase      in varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os dados para a montagem dos relatórios de contas a pagar, a receber e fluxo de caixa
   open p_result for
      select a.codigo_interno, a.vencimento, a.sq_siw_solicitacao, a.quitacao,
             a.aviso_prox_conc, b.fim-a.dias_aviso aviso,
             decode(substr(b1.sigla,3,1),'D',round(b.valor,2) * -1,round(b.valor,2)) valor,
             b.descricao, b.conclusao, b.fim,
             substr(b1.sigla, 3,1) tipo,
             b2.sq_siw_tramite, b2.sigla sg_tramite, b2.nome nm_tramite,
             c.sq_tipo_lancamento, c.nome nm_tipo_lancamento,
             d.sq_siw_solicitacao sq_acordo, d.codigo_interno cd_acordo, d.objeto,
             e.sq_siw_solicitacao sq_projeto, e.titulo nm_projeto,
             f.sq_cc, f.nome nm_cc,
             g.sq_pessoa sq_pessoa, g.nome_resumido nm_pessoa_resumido,
             decode(b.conclusao,null,(vencimento - trunc(sysdate)),(vencimento - trunc(a.quitacao))) prazo
        from fn_lancamento      a,
             siw_solicitacao    b,
             siw_menu           b1,
             siw_tramite        b2,
             fn_tipo_lancamento c,
             ac_acordo          d,
             pj_projeto         e,
             ct_cc              f,
             co_pessoa          g
       where (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
         and (b.sq_menu            = b1.sq_menu and
              (Instr(p_restricao,'FLUXO') > 0 or (Instr(p_restricao,'FLUXO') = 0 and (0 < Instr(b1.sigla, p_restricao)))) and
               b1.sq_pessoa         = p_cliente
              )
         and (b.sq_siw_tramite     = b2.sq_siw_tramite and
              'CA'                 <> Nvl(b2.sigla,'-')
             )
         and (a.sq_tipo_lancamento = c.sq_tipo_lancamento)
         and (b.sq_solic_pai       = d.sq_siw_solicitacao (+))
         and (b.sq_solic_pai       = e.sq_siw_solicitacao (+))
         and (b.sq_cc              = f.sq_cc (+))
         and (a.pessoa             = g.sq_pessoa)
         and ((Instr(p_restricao,'FLUXO') > 0 and (a.vencimento between Add_Months(p_dt_ini,-2) and p_dt_fim)) or
              (Instr(p_restricao,'FLUXO') = 0 and (a.vencimento between p_dt_ini and p_dt_fim))
             )
         and (Instr(p_restricao,'FLUXOPR') = 0 or (Instr(p_restricao,'FLUXOPR') > 0 and a.quitacao is null))
         and (Instr(p_restricao,'FLUXORE') = 0 or (Instr(p_restricao,'FLUXORE') > 0 and a.quitacao is not null))
         and (p_sq_pessoa is null or (p_sq_pessoa is not null and a.pessoa     = p_sq_pessoa))
      UNION
      select null codigo_interno, d.vencimento, null sq_siw_solicitacao, null quitacao,
             'S' aviso_prox_conc, d.vencimento-3 aviso,
             decode(substr(b1.sigla,3,1),'D',round(d.valor,2) * -1,round(d.valor,2)) valor,
             'Pagamento da parcela '||substr(1000+d.ordem,2,3)||', contrato '||a.codigo_interno||' ('||a.sq_siw_solicitacao||')' descricao, b.conclusao, d.vencimento fim,
             substr(b1.sigla, 3,1) tipo,
             b2.sq_siw_tramite, b2.sigla sg_tramite, b2.nome nm_tramite,
             null sq_tipo_lancamento, null nm_tipo_lancamento,
             a.sq_siw_solicitacao sq_acordo, a.codigo_interno cd_acordo, a.objeto,
             e.sq_siw_solicitacao sq_projeto, e.titulo nm_projeto,
             f.sq_cc, f.nome nm_cc,
             g.sq_pessoa sq_pessoa, g.nome_resumido nm_pessoa_resumido,
             decode(b.conclusao,null,(vencimento - trunc(sysdate)),(vencimento - trunc(h.quitacao))) prazo
        from ac_acordo                            a,
             siw_solicitacao    b,
               siw_menu           b1,
               siw_tramite        b2,
               pj_projeto         e,
               ct_cc              f,
             co_pessoa          g,
             ac_acordo_parcela  d,
               (select x.sq_siw_solicitacao, x.sq_acordo_parcela, x.quitacao
                                 from fn_lancamento                x,
                                      siw_solicitacao y,
                                        siw_tramite     z
                                 where (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                   and (y.sq_siw_tramite     = z.sq_siw_tramite and
                                        nvl(z.sigla,'-')     <> 'CA'
                                        )
                               )                      h
       where (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
         and (b.sq_menu            = b1.sq_menu and
             (Instr(p_restricao,'FLUXO') > 0 or (Instr(p_restricao,'FLUXO') = 0 and (substr(b1.sigla,3,1)=substr(p_restricao,3,1)))) and
              b1.sq_pessoa         = p_cliente
             )
         and (b.sq_siw_tramite     = b2.sq_siw_tramite and
              'CA'                 <> Nvl(b2.sigla,'-') and
              0                    < InStr(p_fase,Nvl(b2.sigla,'-'))
             )
         and (b.sq_solic_pai       = e.sq_siw_solicitacao (+))
         and (b.sq_cc              = f.sq_cc (+))
         and (a.outra_parte        = g.sq_pessoa)
         and (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
         and (d.sq_acordo_parcela  = h.sq_acordo_parcela (+))
         and h.sq_siw_solicitacao is null
         and ((Instr(p_restricao,'FLUXO') > 0 and (d.vencimento between Add_Months(p_dt_ini,-2) and p_dt_fim)) or
              (Instr(p_restricao,'FLUXO') = 0 and (d.vencimento between p_dt_ini and p_dt_fim))
             )
         and (Instr(p_restricao,'FLUXOPR') = 0 or (Instr(p_restricao,'FLUXOPR') > 0 and h.quitacao is null))
         and (Instr(p_restricao,'FLUXORE') = 0 or (Instr(p_restricao,'FLUXORE') > 0 and h.quitacao is not null))
         and (p_sq_pessoa is null or (p_sq_pessoa is not null and a.outra_parte     = p_sq_pessoa))
      order by 2,11;
End SP_GetLancamento;
/
