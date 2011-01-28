create or replace FUNCTION sp_GetNatureza_PE
   (p_chave    numeric,
    p_cliente  numeric,
    p_nome     varchar,
    p_ativo    varchar,
    p_result  REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de arquivos
   open p_result for 
      select a.sq_penatureza chave, a.cliente, a.nome, a.ativo, 
             case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
        from pe_natureza a
       where ((p_chave   is null) or (p_chave   is not null and a.sq_penatureza = p_chave))
         and ((p_cliente is null) or (p_cliente is not null and a.cliente     = p_cliente))
         and ((p_nome    is null) or (p_nome    is not null and upper(a.nome) like '%'||upper(p_nome)||'%'))
         and ((p_ativo   is null) or (p_ativo   is not null and a.ativo       = p_ativo));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;