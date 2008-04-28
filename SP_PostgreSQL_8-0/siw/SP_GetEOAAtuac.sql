CREATE OR REPLACE FUNCTION siw.SP_GetEOAAtuac
   (p_sq_pessoa   numeric,
    p_nome        varchar,
    p_ativo       varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   --Recupera a lista de áreas de atuação
   open p_result for
      select sq_area_atuacao, nome, ativo
        from siw.eo_area_atuacao
       where sq_pessoa = p_sq_pessoa
         and (p_nome  is null or (p_nome  is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
         return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetEOAAtuac
   (p_sq_pessoa   numeric,
    p_nome        varchar,
    p_ativo       varchar) OWNER TO siw;
