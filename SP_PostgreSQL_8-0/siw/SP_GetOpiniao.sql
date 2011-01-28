create or replace FUNCTION SP_GetOpiniao
   (p_chave     numeric,
    p_cliente   numeric,
    p_nome      varchar,   
    p_sigla     varchar,       
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
    If p_restricao is null Then
      -- Recupera os tipos de opinião do cliente
      open p_result for 
         select a.sq_siw_opiniao chave, a.nome, a.sigla, a.ordem
           from siw_opiniao   a
      where a.cliente     = p_cliente
        and ((p_chave     is null) or (p_chave     is not null and a.sq_siw_opiniao = p_chave))
        and ((p_sigla     is null) or (p_sigla     is not null and a.sigla          = p_sigla))
        and ((p_nome      is null) or (p_nome      is not null and a.nome           = p_nome));

   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;