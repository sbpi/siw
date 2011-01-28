create or replace FUNCTION SP_GetEOAAtuacData
   (p_chave        numeric,
    p_result      REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   --Recupera a lista de áreas de atuação
   open p_result for
      select nome, ativo, sq_area_atuacao
        from eo_area_atuacao 
       where sq_area_atuacao = p_chave;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;