create or replace FUNCTION SP_GetUnitTypeData
   (p_chave         numeric,
    p_result       REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   --Recupera os dados do tipo da unidade
   open p_result for
      select nome, sq_tipo_unidade, ativo
        from eo_tipo_unidade 
       where sq_tipo_unidade = p_chave;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;