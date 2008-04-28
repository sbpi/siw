CREATE OR REPLACE FUNCTION siw.SP_GetDeficGrpList
   (p_nome      varchar,
    p_ativo     varchar)

  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os grupos de deficiências existentes
   open p_result for
      select sq_grupo_defic, nome, ativo,
             case ativo when 'S' then 'Sim' else 'Não' end as ativodesc
        from siw.co_grupo_defic
       where (p_nome  is null or (p_nome  is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo))
      order by nome;
      return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetDeficGrpList
   (p_nome      varchar,
    p_ativo     varchar) OWNER TO siw;
