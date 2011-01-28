create or replace FUNCTION SP_GetTipoSP
   (p_chave      numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de índic
   open p_result for 
      select a.sq_sp_tipo chave, a.nome, a.descricao
        from dc_sp_tipo a
       where ((p_chave is null) or (p_chave is not null and a.sq_sp_tipo = p_chave));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;