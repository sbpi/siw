CREATE OR REPLACE FUNCTION siw.SP_GetDefList
   (p_nome      varchar,
    p_ativo     varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera as deficiências existentes
   open p_result for
      select a.sq_deficiencia, a.nome, a.ativo,
             case a.ativo when 'S' then 'Sim' else 'Não' end as ativodesc,
             a.codigo, Nvl(a.descricao,'-') as descricao, b.nome as sq_grupo_defic
        from siw.co_deficiencia a, siw.co_grupo_defic b
      where a.sq_grupo_defic = b.sq_grupo_defic
        and (p_nome  is null or (p_nome  is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
        and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));
        return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetDefList
   (p_nome      varchar,
    p_ativo     varchar) OWNER TO siw;
