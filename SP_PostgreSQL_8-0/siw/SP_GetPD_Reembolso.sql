create or replace FUNCTION SP_GetPD_Reembolso
   (p_chave     numeric,
    p_chave_aux numeric,
    p_moeda     numeric,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os reembolsos de viagens
      open p_result for
         select a.sq_pdreembolso as chave, a.sq_siw_solicitacao, a.sq_moeda, a.valor_solicitado, a.justificativa, a.valor_autorizado, a.observacao,
                f.codigo as cd_moeda, f.sigla as sg_moeda, f.nome as nm_moeda
           from pd_reembolso          a
                inner   join co_moeda f on (a.sq_moeda  = f.sq_moeda)
          where (p_chave         is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux     is null or (p_chave_aux is not null and a.sq_pdreembolso     = p_chave_aux))
            and (p_moeda         is null or (p_moeda     is not null and f.sq_moeda           = p_moeda));
   End If;         
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;