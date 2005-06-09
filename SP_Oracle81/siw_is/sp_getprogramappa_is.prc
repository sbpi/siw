create or replace procedure SP_GetProgramaPPA_IS
   (p_cliente   in  number,
    p_ano       in  number,
    p_programa  in  varchar2  default null,
    p_restricao in  varchar2  default null,
    p_result    out siw.siw.sys_refcursor) is
begin
   open p_result for 
       select a.cd_programa, a.nome ds_programa, a.cd_orgao, b.nome ds_orgao, b.sigla sg_orgao,
             a.cd_tipo_programa, c.nome ds_tp_programa, k.sq_natureza,k.sq_horizonte,k.nm_gerente_programa,
             k.fn_gerente_programa, k.em_gerente_programa, k.nm_gerente_executivo, k.fn_gerente_executivo,
             k.em_gerente_executivo, k.nm_gerente_adjunto, k.fn_gerente_adjunto, k.em_gerente_adjunto,
             e.nome ds_macro, f.nome ds_opcao_estrat, g.nome ds_estagio, h.nome ds_andamento, 
             i.nome ds_cronograma, j.nome ds_tp_orgao, a.ln_programa, a.valor_estimado, a.valor_ppa,
             k.sq_siw_solicitacao, k.sg_tramite
        from is_sig_programa                            a,
             is_sig_orgao              b,
             is_sig_tipo_programa      c,
             is_sig_macro_objetivo      e,
             is_sig_opcao_estrat        f,
             is_sig_tipo_situacao       g,
             is_sig_tipo_situacao       h,
             is_sig_tipo_situacao       i,
             is_sig_tipo_orgao          j,
             (select d.cd_programa, d.ano, d.cliente, d.sq_natureza,d.sq_horizonte,d.nm_gerente_programa,
                                     d.fn_gerente_programa, d.em_gerente_programa, d.nm_gerente_executivo, d.fn_gerente_executivo,
                                     d.em_gerente_executivo, d.nm_gerente_adjunto, d.fn_gerente_adjunto, d.em_gerente_adjunto,
                                     d1.sq_siw_solicitacao, d2.sigla sg_tramite
                                from is_programa                    d,
                                     siw.siw_solicitacao d1,
                                     siw.siw_tramite     d2
                               where (d.sq_siw_solicitacao = d1.sq_siw_solicitacao)
                                 and (d1.sq_siw_tramite    = d2.sq_siw_tramite and
                                      'CA'                <> Nvl(d2.sigla,'---'))
                             )                          k
       where (a.cd_orgao         = b.cd_orgao    and
              a.ano               = b.ano)
         and (a.cd_tipo_programa = c.cd_tipo_programa)
         and (a.cd_macro         = e.cd_macro (+))  
         and (e.cd_opcao         = f.cd_opcao (+))
         and (a.cd_estagio       = g.cd_tipo_situacao (+))
         and (a.cd_andamento     = h.cd_tipo_situacao (+))
         and (a.cd_cronograma    = i.cd_tipo_situacao (+))
         and (a.cd_tipo_orgao    = j.cd_tipo_orgao)
         and (a.cd_programa     = k.cd_programa (+) and
              a.ano             = k.ano (+)         and
              a.cliente         = k.cliente (+))
         and a.cliente = p_cliente
         and a.ano     = p_ano
         and (p_programa  is null or (p_programa  is not null   and a.cd_programa = p_programa))
         and (p_restricao is null or 
              (p_restricao = 'IDENTIFICACAO' and 
               a.cd_programa not in (select l.cd_programa 
                                      from  is_programa l,
                                            siw.siw_solicitacao m,
                                            siw.siw_tramite     n
                                     where (l.sq_siw_solicitacao = m.sq_siw_solicitacao) 
                                       and (m.sq_siw_tramite     = n.sq_siw_tramite and
                                            'CA'                 <> Nvl(n.sigla,'---'))
                                       and l.cliente = p_cliente 
                                       and l.ano = p_ano
                                    )
               )
              );
end SP_GetProgramaPPA_IS;
/

