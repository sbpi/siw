create or replace FUNCTION SP_GetPD_Vinculacao
   (p_chave     numeric,
    p_chave_aux numeric,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera as atividades ligadas a viagens
      open p_result for 
         select b.sq_siw_solicitacao, b.inicio, b.fim, 
                b1.nome as nm_tramite, b1.sigla as sg_tramite,
                b2.sigla,
                c.inicio_real, c.fim_real, 
                c.assunto,
                e.sq_solic_missao,
                f.concluida, f.aviso_prox_conc,
                trunc(b.fim)-cast(f.dias_aviso as integer) as aviso,
                g.sq_siw_solicitacao sq_projeto, g1.titulo nm_projeto
           from siw_solicitacao             b
                inner  join siw_tramite     b1 on (b.sq_siw_tramite     = b1.sq_siw_tramite and 
                                                   b1.sigla             <> 'CA'
                                                  )
                inner  join siw_menu        b2 on (b.sq_menu            = b2.sq_menu)
                inner  join gd_demanda      c  on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                inner  join pd_missao_solic e  on (b.sq_siw_solicitacao = e.sq_siw_solicitacao)
                inner  join gd_demanda      f  on (b.sq_siw_solicitacao = f.sq_siw_solicitacao)
                left   join pj_projeto      g  on (b.sq_solic_pai       = g.sq_siw_solicitacao)
                  left join siw_solicitacao g1 on (g.sq_siw_solicitacao = g1.sq_siw_solicitacao)
          where (p_chave     is null or (p_chave     is not null and e.sq_solic_missao = p_chave))
            and (p_chave_aux is null or (p_chave_aux is not null and b.sq_siw_solicitacao = p_chave_aux));
   End If;         

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;