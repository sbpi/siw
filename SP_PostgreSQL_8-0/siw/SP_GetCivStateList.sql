CREATE OR REPLACE FUNCTION siw.SP_GetCivStateList
   (p_ativo varchar)
  RETURNS refcursor AS
$BODY$
DECLARE
    p_result    refcursor;
begin
   -- Recupera os dados da tabela de estados civis
   open p_result for
      select sq_estado_civil, nome, sigla, ativo,
             codigo_externo
        from siw.co_estado_civil
       where (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCivStateList
   (p_ativo varchar)OWNER TO siw;
