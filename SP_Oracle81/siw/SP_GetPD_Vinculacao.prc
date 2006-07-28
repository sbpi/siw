create or replace procedure SP_GetPD_Vinculacao
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as atividades ligadas a viagens
      open p_result for 
         select b.sq_siw_solicitacao, b.inicio, b.fim, 
                b1.nome as nm_tramite, b1.sigla as sg_tramite,
                c.inicio_real, c.fim_real, 
                c.assunto,
                e.sq_solic_missao,
                f.concluida, f.aviso_prox_conc,
                b.fim-f.dias_aviso as aviso,
                g.sq_siw_solicitacao sq_projeto, g.titulo nm_projeto
           from siw_solicitacao b,
                siw_tramite     b1,
                gd_demanda      c,
                pd_missao_solic e,
                gd_demanda      f,
                pj_projeto      g
          where (b.sq_siw_tramite     = b1.sq_siw_tramite and 
                 Nvl(b1.sigla,'-')    <> 'CA'
                )
            and (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
            and (b.sq_siw_solicitacao = e.sq_siw_solicitacao)
            and (b.sq_siw_solicitacao = f.sq_siw_solicitacao)
            and (b.sq_solic_pai       = g.sq_siw_solicitacao (+))
            and (p_chave     is null or (p_chave     is not null and e.sq_solic_missao = p_chave))
            and (p_chave_aux is null or (p_chave_aux is not null and b.sq_siw_solicitacao = p_chave_aux));
   End If;         
End SP_GetPD_Vinculacao;
/
