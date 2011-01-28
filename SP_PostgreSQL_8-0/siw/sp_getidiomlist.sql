create or replace FUNCTION SP_GetIdiomList
   (p_nome       varchar,
    p_ativo      varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os idiomas          existentes
   open p_result for 
      select sq_idioma, nome, padrao, ativo, 
             case padrao when 'S' then 'Sim' else 'Não' end padraodesc, 
             case ativo when 'S' then 'Sim' else 'Não' end ativodesc 
       from co_idioma
       where (p_nome  is null or (p_nome  is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;