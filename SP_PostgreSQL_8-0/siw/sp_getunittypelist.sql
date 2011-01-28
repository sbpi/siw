create or replace FUNCTION SP_GetUnitTypeList
   (p_sq_pessoa    numeric,
    p_nome         varchar,
    p_ativo        varchar,
    p_result      REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   --Recupera a lista dos tipos de unidade
   open p_result for
      select sq_tipo_unidade, nome, ativo
        from eo_tipo_unidade
       where sq_pessoa = p_sq_pessoa
         and (p_nome  is null or (p_nome  is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;