create or replace FUNCTION SP_GetCountryData
   (p_sq_pais  numeric,
    p_result     REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados do país
   open p_result for 
      select a.*,
             b.codigo as cd_moeda, b.nome as nm_moeda, b.sigla as sg_moeda, b.simbolo as sb_moeda
        from co_pais             a 
             left  join co_moeda b on (a.sq_moeda = b.sq_moeda)
       where a.sq_pais = p_sq_pais;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;