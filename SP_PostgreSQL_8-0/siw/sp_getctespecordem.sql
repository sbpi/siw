create or replace FUNCTION SP_GetEspecOrdem
   (p_chave    numeric,
    p_result  REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as etapas acima da informada
   open p_result for 
      select sq_especificacao_despesa, especificacao_pai, nome, codigo
        from ct_especificacao_despesa
      start with sq_especificacao_despesa = p_chave
      connect by prior especificacao_pai = sq_especificacao_despesa; 
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;