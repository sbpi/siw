create or replace procedure SP_GetSolicAreas
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
begin
   -- Recupera as demandas que o usuário pode ver
   open p_result for 
     select a.*, b.nome, b.informal, b.vinculada, b.adm_central
       from gd_demanda_envolv   a,
            eo_unidade          b
      where a.sq_unidade         = b.sq_unidade
         and a.sq_siw_solicitacao = p_chave
         and (p_chave_aux is null or (p_chave_aux is not null and a.sq_unidade = p_chave_aux))
     UNION
     select a.*, b.nome, b.informal, b.vinculada, b.adm_central
       from pj_projeto_envolv   a,
            eo_unidade          b
      where a.sq_unidade         = b.sq_unidade
         and a.sq_siw_solicitacao = p_chave
         and (p_chave_aux is null or (p_chave_aux is not null and a.sq_unidade = p_chave_aux));
end SP_GetSolicAreas;
/
