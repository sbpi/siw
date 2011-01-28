create or replace FUNCTION SP_GetKnowArea
   (p_chave     numeric,
    p_nome      varchar,
    p_tipo      varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os dados de áreas do conhecimento
   open p_result for 
      select sq_area_conhecimento, sq_area_conhecimento_pai, nome, codigo_cnpq, 
             especializacao, hierarquia, tecnico, requisito, ativo 
        from co_area_conhecimento a
       where ((p_chave        is null) or (p_chave is not null and a.sq_area_conhecimento = p_chave))
         and ((p_nome         is null) or (p_nome is not null and acentos(a.nome,2) like '%'||acentos(p_nome,2)||'%'))
         and a.especializacao = p_tipo;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;