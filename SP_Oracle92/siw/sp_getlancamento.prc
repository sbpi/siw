create or replace procedure SP_GetLancamento
   (p_cliente   in number,
    p_restricao in varchar2 default null,
    p_dt_ini    in date     default null,
    p_dt_fim    in date     default null,
    p_pg_ini    in date     default null,
    p_pg_fim    in date     default null,
    p_co_ini    in date     default null,
    p_co_fim    in date     default null,
    p_sq_pessoa in number   default null,
    p_fase      in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os dados para a montagem dos relatórios de contas a pagar, a receber e fluxo de caixa
   open p_result for 
      select b.codigo_interno, a.vencimento, a.sq_siw_solicitacao, a.quitacao, 
             a.aviso_prox_conc, b.fim-a.dias_aviso aviso, 
             case substr(b1.sigla,3,1) when 'D' Then round(b.valor,2) * -1 else round(b.valor,2) end valor, 
             b.descricao, b.conclusao, b.fim,
             to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_conclusao,
             substr(b1.sigla, 3,1) tipo,
             b2.sq_siw_tramite, b2.sigla sg_tramite, b2.nome nm_tramite,
             c.sq_tipo_lancamento, c.nome nm_tipo_lancamento,
             d.sq_siw_solicitacao sq_acordo, d1.codigo_interno cd_acordo, d.objeto,
             e.sq_siw_solicitacao sq_projeto, e1.titulo nm_projeto,
             f.sq_cc, f.nome nm_cc,
             g.sq_pessoa sq_pessoa, g.nome_resumido nm_pessoa_resumido,
             case when b.conclusao is null 
                  then (a.vencimento - trunc(sysdate))
                  else (a.vencimento - trunc(a.quitacao))
             end prazo
        from fn_lancamento                      a 
             inner      join siw_solicitacao    b  on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner    join siw_menu           b1 on (b.sq_menu            = b1.sq_menu and
                                                      (Instr(p_restricao,'FLUXO') > 0 or (Instr(p_restricao,'FLUXO') = 0 and (0 < Instr(b1.sigla, p_restricao)))) and
                                                       b1.sq_pessoa         = p_cliente
                                                      )
               inner    join siw_tramite        b2 on (b.sq_siw_tramite     = b2.sq_siw_tramite and
                                                       'CA'                 <> Nvl(b2.sigla,'-')
                                                      )
             inner      join fn_tipo_lancamento c  on (a.sq_tipo_lancamento = c.sq_tipo_lancamento)
             left       join ac_acordo          d  on (b.sq_solic_pai       = d.sq_siw_solicitacao)
               left     join siw_solicitacao    d1 on (d.sq_siw_solicitacao = d1.sq_siw_solicitacao)
             left       join pj_projeto         e  on (a.sq_solic_vinculo   = e.sq_siw_solicitacao)
               left     join siw_solicitacao    e1 on (e.sq_siw_solicitacao = e1.sq_siw_solicitacao)
             left       join ct_cc              f  on (b.sq_cc              = f.sq_cc)
             inner      join co_pessoa          g  on (a.pessoa             = g.sq_pessoa)
       where ((Instr(p_restricao,'FLUXO') > 0 and (a.vencimento between Add_Months(p_dt_ini,-2) and p_dt_fim)) or
              (Instr(p_restricao,'FLUXO') = 0 and ((p_dt_ini is not null and a.vencimento       between p_dt_ini and p_dt_fim) or
                                                   (p_pg_ini is not null and a.quitacao         between p_pg_ini and p_pg_fim) or
                                                   (p_co_ini is not null and trunc(b.conclusao) between p_co_ini and p_co_fim)
                                                  )
              )
             )
         and (Instr(p_restricao,'FLUXOPR') = 0 or (Instr(p_restricao,'FLUXOPR') > 0 and a.quitacao is null))
         and (Instr(p_restricao,'FLUXORE') = 0 or (Instr(p_restricao,'FLUXORE') > 0 and a.quitacao is not null))
         and (p_sq_pessoa is null or (p_sq_pessoa is not null and a.pessoa     = p_sq_pessoa))
      UNION
      select null codigo_interno, d.vencimento, null sq_siw_solicitacao, null quitacao, 
             'S' aviso_prox_conc, d.vencimento-3 aviso,
             case substr(b1.sigla,3,1) when 'D' Then round(d.valor,2) * -1 else round(d.valor,2) end valor, 
             'Pagamento da parcela '||substr(1000+d.ordem,2,3)||', contrato '||b.codigo_interno||' ('||a.sq_siw_solicitacao||')' descricao, 
             b.conclusao, d.vencimento fim,
             to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_conclusao,
             substr(b1.sigla, 3,1) tipo,
             b2.sq_siw_tramite, b2.sigla sg_tramite, b2.nome nm_tramite,
             null sq_tipo_lancamento, null nm_tipo_lancamento,
             a.sq_siw_solicitacao sq_acordo, b.codigo_interno cd_acordo, a.objeto,
             e.sq_siw_solicitacao sq_projeto, e1.titulo nm_projeto,
             f.sq_cc, f.nome nm_cc,
             g.sq_pessoa sq_pessoa, g.nome_resumido nm_pessoa_resumido,
             case when b.conclusao is null 
                  then (d.vencimento - trunc(sysdate))
                  else (d.vencimento - trunc(h.quitacao))
             end prazo
        from ac_acordo                            a 
             inner        join siw_solicitacao    b  on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner      join siw_menu           b1 on (b.sq_menu            = b1.sq_menu and
                                                        (Instr(p_restricao,'FLUXO') > 0 or (Instr(p_restricao,'FLUXO') = 0 and (substr(b1.sigla,3,1)=substr(p_restricao,3,1)))) and
                                                         b1.sq_pessoa         = p_cliente
                                                        )
               inner      join siw_tramite        b2 on (b.sq_siw_tramite     = b2.sq_siw_tramite and
                                                         'CA'                 <> Nvl(b2.sigla,'-') and
                                                         0                    < InStr(p_fase,Nvl(b2.sigla,'-'))
                                                        )
               left       join pj_projeto         e  on (a.sq_solic_vinculo   = e.sq_siw_solicitacao)
                 left     join siw_solicitacao    e1 on (e.sq_siw_solicitacao = e1.sq_siw_solicitacao)
               left       join ct_cc              f  on (b.sq_cc              = f.sq_cc)
             inner        join co_pessoa          g  on (a.outra_parte        = g.sq_pessoa)
             inner        join ac_acordo_parcela  d  on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
               left       join (select x.sq_siw_solicitacao, x.sq_acordo_parcela, x.quitacao
                                 from fn_lancamento                x
                                      inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                        inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                         nvl(z.sigla,'-')     <> 'CA'
                                                                        )
                               )                      h  on (d.sq_acordo_parcela  = h.sq_acordo_parcela)
       where h.sq_siw_solicitacao is null
         and ((Instr(p_restricao,'FLUXO') > 0 and (d.vencimento between Add_Months(p_dt_ini,-2) and p_dt_fim)) or
              (Instr(p_restricao,'FLUXO') = 0 and (d.vencimento between p_dt_ini and p_dt_fim and
                                                   p_pg_ini     is null and
                                                   p_co_ini     is null
                                                  )
              )
             )
         and (Instr(p_restricao,'FLUXOPR') = 0 or (Instr(p_restricao,'FLUXOPR') > 0 and h.quitacao is null))
         and (Instr(p_restricao,'FLUXORE') = 0 or (Instr(p_restricao,'FLUXORE') > 0 and h.quitacao is not null))
         and (p_sq_pessoa is null or (p_sq_pessoa is not null and a.outra_parte     = p_sq_pessoa))
      order by 2,12;
End SP_GetLancamento;
/
