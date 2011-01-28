create or replace FUNCTION SP_GetFoneTypeList
   (p_tipo_pessoa varchar,
    p_nome        varchar,
    p_ativo       varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera o tipos de telefones existentes
   open p_result for 
      select a.sq_tipo_telefone, a.nome, a.padrao, 
             case a.padrao when 'S' then 'Sim' else 'Não' end as padraodesc, 
             a.ativo, 
             case a.ativo when 'S' then 'Sim' else 'Não' end as ativodesc, b.nome as nm_tipo_pessoa
        from co_tipo_telefone a, co_tipo_pessoa b
      where a.sq_tipo_pessoa = b.sq_tipo_pessoa
        and (p_tipo_pessoa is null or (p_tipo_pessoa is not null and b.nome = p_tipo_pessoa))
        and (p_nome        is null or (p_nome        is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
        and (p_ativo       is null or (p_ativo       is not null and a.ativo = p_ativo));      
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;