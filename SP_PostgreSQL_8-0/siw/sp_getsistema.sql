create or replace FUNCTION SP_GetSistema
   (p_chave      numeric,
    p_cliente    numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de índic
   open p_result for 
      select a.sq_sistema chave, a.cliente, a.nome, a.sigla, a.descricao
        from dc_sistema a
       where cliente = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_sistema = p_chave));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;