create or replace FUNCTION SP_GetEtapaAnexo
   (p_chave     numeric,
    p_chave_aux numeric,
    p_cliente   numeric,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os anexos de uma etapa
   open p_result for 
      select a.sq_projeto_etapa as chave,
             b.sq_siw_arquivo as chave_aux, b.cliente, b.nome, b.descricao, 
             b.inclusao, b.tamanho, b.tipo, b.caminho
        from pj_projeto_etapa_arq   a
             inner join siw_arquivo b on (a.sq_siw_arquivo = b.sq_siw_arquivo)
       where a.sq_projeto_etapa   = p_chave
         and b.cliente            = p_cliente
         and ((p_chave_aux        is null) or (p_chave_aux is not null and b.sq_siw_arquivo = p_chave_aux));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;