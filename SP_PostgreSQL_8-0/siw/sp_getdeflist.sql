create or replace FUNCTION SP_GetDefList
   (p_nome       varchar,
    p_ativo      varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as deficiências existentes
   open p_result for 
      select a.sq_deficiencia, a.nome, a.ativo, 
             case a.ativo when 'S' then 'Sim' else 'Não' end ativodesc,
             a.codigo, Nvl(a.descricao,'-') descricao, b.nome sq_grupo_defic
        from co_deficiencia a, co_grupo_defic b  
      where a.sq_grupo_defic = b.sq_grupo_defic
        and (p_nome  is null or (p_nome  is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
        and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;