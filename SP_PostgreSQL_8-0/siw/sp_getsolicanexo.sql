create or replace FUNCTION SP_GetSolicAnexo
   (p_chave     numeric,
    p_chave_aux numeric,
    p_cliente   numeric,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as demandas que o usuário pode ver
   open p_result for 
      select a.sq_siw_solicitacao chave,
             b.sq_siw_arquivo chave_aux, b.cliente, b.nome, b.descricao, 
             b.inclusao, b.tamanho, b.tipo, b.caminho, b.nome_original
        from siw_solic_arquivo      a
             inner join siw_arquivo b on (a.sq_siw_arquivo = b.sq_siw_arquivo)
       where a.sq_siw_solicitacao = p_chave
         and b.cliente            = p_cliente
         and ((p_chave_aux        is null) or (p_chave_aux is not null and b.sq_siw_arquivo = p_chave_aux));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;