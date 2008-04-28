CREATE OR REPLACE FUNCTION siw.SP_GetFeriado
   (p_cliente             numeric,
    p_cidade              numeric,
    p_chave               numeric,
    p_data                date,
    p_nome                varchar,
    p_tipo                varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os feriados a partir dos parâmetros informados
 /*  open p_result for
     select null sq_feriado, null nome, null tipo, null sq_cidade
        from dual
       where 1 = 0;*/
       return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetFeriado
   (p_cliente             numeric,
    p_cidade              numeric,
    p_chave               numeric,
    p_data                date,
    p_nome                varchar,
    p_tipo                varchar) OWNER TO siw;
