create or replace FUNCTION SP_GetSolicObjetivo
   (p_chave     numeric,
    p_chave_aux numeric,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   if p_restricao is null then
      -- Recupera os objetivos estratégicos aos quais a solicitação está ligada
      open p_result for
        select c.sq_peobjetivo, c.cliente, c.sq_plano, c.nome, c.sigla, c.descricao, c.ativo
          from siw_solicitacao_objetivo   a
               inner join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner join pe_objetivo     c on (a.sq_peobjetivo      = c.sq_peobjetivo)
         where a.sq_siw_solicitacao = p_chave
            and (p_chave_aux is null or (p_chave_aux is not null and a.sq_plano = p_chave_aux));
   end if;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;