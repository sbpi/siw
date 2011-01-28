create or replace FUNCTION SP_GetEtniaList
   (p_nome       varchar,
    p_ativo      varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as etnias existentes
   open p_result for 
      select codigo_siape, sq_etnia, nome, ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end descativo 
        from co_etnia
       where (p_nome  is null or (p_nome  is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;