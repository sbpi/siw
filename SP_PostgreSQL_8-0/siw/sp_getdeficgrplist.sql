create or replace FUNCTION SP_GetDeficGrpList
   (p_nome            varchar,
    p_codigo_externo  varchar,
    p_ativo           varchar,
    p_result         REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os grupos de deficiências existentes
   open p_result for 
      select sq_grupo_defic, nome, codigo_externo, ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end ativodesc 
        from co_grupo_defic
       where (p_nome            is null or (p_nome            is not null and acentos(nome)           like '%'||acentos(p_nome)||'%'))
         and (p_codigo_externo  is null or (p_codigo_externo  is not null and acentos(codigo_externo) = acentos(p_codigo_externo)))
         and (p_ativo           is null or (p_ativo           is not null and ativo = p_ativo))
      order by nome; 
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;