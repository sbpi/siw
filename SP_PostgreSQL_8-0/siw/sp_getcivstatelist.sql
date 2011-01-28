create or replace FUNCTION SP_GetCivStateList
   (p_ativo      varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados da tabela de estados civis
   open p_result for 
      select sq_estado_civil, nome, sigla, ativo, 
             codigo_externo 
        from co_estado_civil
       where (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;