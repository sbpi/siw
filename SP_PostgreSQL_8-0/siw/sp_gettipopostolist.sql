create or replace FUNCTION SP_GetTipoPostoList
   (p_cliente    numeric,
    p_chave      numeric,
    p_ativo      varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de postos existentes
   open p_result for 
      select a.sq_eo_tipo_posto, a.nome, a.padrao, 
             a.ativo, a.sigla, a.descricao
        from eo_tipo_posto a
       where a.cliente = p_cliente
       and (p_chave is null or (p_chave is not null and a.sq_eo_tipo_posto = p_chave))
       and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo))
     order by a.padrao, a.ativo, a.nome;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;