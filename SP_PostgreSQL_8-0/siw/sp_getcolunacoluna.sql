create or replace FUNCTION SP_GetColunaColuna
   (p_chave            numeric,
    p_sq_coluna_pai    numeric,
    p_sq_coluna_filha  numeric,
    p_result          REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de Tabela
   open p_result for 
   select a.sq_relacionamento chave,
          b.nome nm_coluna_pai, b.descricao ds_coluna_pai,
          c.nome nm_coluna_filha, c.descricao ds_coluna_filha,
          d.nome nm_dado_tipo,
          e.nome nm_relacionamento
   from dc_relac_cols                a
        inner join dc_coluna         b on (a.coluna_pai        = b.sq_coluna)
        inner join dc_coluna         c on (a.coluna_filha      = c.sq_coluna)
            inner join dc_dado_tipo  d on (c.sq_dado_tipo      = d.sq_dado_tipo)
        inner join dc_relacionamento e on (a.sq_relacionamento = e.sq_relacionamento)
   where ((p_chave           is null) or (p_chave           is not null and b.sq_coluna  = p_chave))
     and ((p_sq_coluna_pai   is null) or (p_sq_coluna_pai   is not null and b.sq_coluna = p_sq_coluna_pai))
     or  ((p_sq_coluna_filha is null) or (p_sq_coluna_filha is not null and c.sq_coluna = p_sq_coluna_filha));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;