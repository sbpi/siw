create or replace FUNCTION SP_GetSolicAreas
   (p_chave     numeric,
    p_chave_aux numeric,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   if p_restricao = 'PACOTE' then
      open p_result for
       select a.sq_unidade, a.sq_siw_solicitacao, a.papel, a.interesse_positivo, a.influencia,
              case interesse_positivo when 'S' then '+' else '-' end as nm_interesse,
              case influencia when 0 then 'Alta' when 1 then 'Média' when 2 then 'Baixa' else '---' end as nm_influencia,
              b.nome, b.informal, b.vinculada, b.adm_central,b.sigla
         from pj_projeto_envolv                a
              inner join eo_unidade            b on (a.sq_unidade = b.sq_unidade)
              inner join siw_etapa_interessado c on (a.sq_unidade = c.sq_unidade)
        where a.sq_siw_solicitacao = p_chave
          and (p_chave_aux is null or (p_chave_aux is not null and c.sq_projeto_etapa = p_chave_aux));

   else
     -- Recupera as demandas que o usuário pode ver
     open p_result for
       select a.sq_unidade, a.sq_siw_solicitacao, a.papel, null as interesse_positivo, null as influencia,
              null as nm_interesse, null as nm_influencia,
              b.nome, b.informal, b.vinculada, b.adm_central
         from gd_demanda_envolv   a,
              eo_unidade          b
        where a.sq_unidade         = b.sq_unidade
           and a.sq_siw_solicitacao = p_chave
           and (p_chave_aux is null or (p_chave_aux is not null and a.sq_unidade = p_chave_aux))
       UNION
       select a.sq_unidade, a.sq_siw_solicitacao, a.papel, a.interesse_positivo, a.influencia,
              case interesse_positivo when 'S' then '+' else '-' end as nm_interesse,
              case influencia when 0 then 'Alta' when 1 then 'Média' when 2 then 'Baixa' else '---' end as nm_influencia,
              b.nome, b.informal, b.vinculada, b.adm_central
         from pj_projeto_envolv   a,
              eo_unidade          b
        where a.sq_unidade         = b.sq_unidade
           and a.sq_siw_solicitacao = p_chave
           and (p_chave_aux is null or (p_chave_aux is not null and a.sq_unidade = p_chave_aux));
    end if;     
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;