create or replace procedure SP_GetProgramaPPA_IS
   (p_cliente   in  number,
    p_ano       in  number,
    p_programa  in  varchar2  default null,
    p_restricao in  varchar2  default null,
    p_result    out sys_refcursor) is
begin
   open p_result for 
       select a.cd_programa, a.nome ds_programa, a.cd_orgao, b.nome ds_orgao, b.sigla sg_orgao,
             a.cd_tipo_programa, c.nome ds_tp_programa, k.sq_natureza,k.sq_horizonte,k.nm_gerente_programa,
             k.fn_gerente_programa, k.em_gerente_programa, k.nm_gerente_executivo, k.fn_gerente_executivo,
             k.em_gerente_executivo, k.nm_gerente_adjunto, k.fn_gerente_adjunto, k.em_gerente_adjunto,
             e.nome ds_macro, f.nome ds_opcao_estrat, g.nome ds_estagio, h.nome ds_andamento, 
             i.nome ds_cronograma, j.nome ds_tp_orgao, a.ln_programa, a.valor_estimado, a.valor_ppa,
             k.sq_siw_solicitacao, k.sg_tramite
        from is_sig_programa                            a
             inner      join  is_sig_orgao              b  on (a.cd_orgao         = b.cd_orgao    and
                                                               a.ano               = b.ano)
             inner      join  is_sig_tipo_programa      c  on (a.cd_tipo_programa = c.cd_tipo_programa) 
             left outer join is_sig_macro_objetivo      e  on (a.cd_macro         = e.cd_macro)  
             left outer join is_sig_opcao_estrat        f  on (e.cd_opcao         = f.cd_opcao)   
             left outer join is_sig_tipo_situacao       g  on (a.cd_estagio       = g.cd_tipo_situacao)
             left outer join is_sig_tipo_situacao       h  on (a.cd_andamento     = h.cd_tipo_situacao)
             left outer join is_sig_tipo_situacao       i  on (a.cd_cronograma    = i.cd_tipo_situacao)                                                           
             inner      join is_sig_tipo_orgao          j  on (a.cd_tipo_orgao    = j.cd_tipo_orgao)
             left outer join (select d.cd_programa, d.ano, d.cliente, d.sq_natureza,d.sq_horizonte,d.nm_gerente_programa,
                                     d.fn_gerente_programa, d.em_gerente_programa, d.nm_gerente_executivo, d.fn_gerente_executivo,
                                     d.em_gerente_executivo, d.nm_gerente_adjunto, d.fn_gerente_adjunto, d.em_gerente_adjunto,
                                     d1.sq_siw_solicitacao, d2.sigla sg_tramite
                                from is_programa                    d
                                     inner join siw.siw_solicitacao d1 on (d.sq_siw_solicitacao = d1.sq_siw_solicitacao)
                                     inner join siw.siw_tramite     d2 on (d1.sq_siw_tramite    = d2.sq_siw_tramite and
                                                                           'CA'                <> Nvl(d2.sigla,'---'))
                             )                          k    on (a.cd_programa     = k.cd_programa and
                                                                 a.ano             = k.ano         and
                                                                 a.cliente         = k.cliente)    
       where a.cliente = p_cliente
         and a.ano     = p_ano
         and (p_programa  is null or (p_programa  is not null   and a.cd_programa = p_programa))
         and (p_restricao is null or 
              (p_restricao = 'IDENTIFICACAO' and 
               a.cd_programa not in (select l.cd_programa 
                                      from  is_programa l 
                                            inner   join siw.siw_solicitacao m on (l.sq_siw_solicitacao = m.sq_siw_solicitacao)
                                              inner join siw.siw_tramite     n on (m.sq_siw_tramite     = n.sq_siw_tramite and
                                                                                   'CA'                 <> Nvl(n.sigla,'---'))
                                     where l.cliente = p_cliente 
                                       and l.ano = p_ano
                                    )
               )
              );
end SP_GetProgramaPPA_IS;
/

