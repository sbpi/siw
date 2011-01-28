create or replace FUNCTION SP_GetTTPrefixo
   (p_chave    numeric,
    p_prefixo  varchar,
    p_uf       varchar,
    p_result  REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de índice
   open p_result for 
        select a.sq_prefixo chave, a.prefixo, a.localidade, a.sigla, a.uf, a.ddd, a.controle, a.degrau
        from tt_prefixos a
        where ((p_chave   is null) or (p_chave   is not null and a.sq_prefixo = p_chave))
         and  ((p_prefixo is null) or (p_prefixo is not null and upper(a.prefixo) like '%'||upper(p_prefixo)||'%'))
         and  ((p_uf      is null) or (p_uf      is not null and upper(a.uf)      like '%'||upper(p_uf)||'%'));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;