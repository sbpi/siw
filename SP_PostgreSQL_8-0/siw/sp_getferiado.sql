create or replace FUNCTION SP_GetFeriado
   (p_cliente              numeric,
    p_cidade               numeric,
    p_chave                numeric,
    p_data                 date,
    p_nome                 varchar,
    p_tipo                 varchar,
    p_result      REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os feriados a partir dos parâmetros informados
   open p_result for 
      select null sq_feriado, null nome, null tipo, null sq_cidade
       
       where 1 = 0;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;