create or replace FUNCTION SP_GetIndiceCols
   (p_sq_indice  numeric,
    p_sq_coluna  numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de índice
   open p_result for 
     select a.ordem, a.ordenacao,
            b.sq_indice chave, b.nome nm_indice, b.descricao ds_indice,
            d.nome nm_indice_tipo,
            e.sq_sistema, e.sq_tabela, e.nome nm_tabela,
            f.nome nm_usuario,
            IndiceCols(b.sq_indice) colunas
       from dc_indice_cols                a
            inner     join dc_indice      b on (a.sq_indice      = b.sq_indice)
              inner   join dc_indice_tipo d on (b.sq_indice_tipo = d.sq_indice_tipo)
            inner     join dc_coluna      c on (a.sq_coluna      = c.sq_coluna)
              inner   join dc_tabela      e on (c.sq_tabela      = e.sq_tabela)
                inner join dc_usuario     f on (e.sq_usuario     = f.sq_usuario)
       where ((p_sq_indice is null) or (p_sq_indice is not null and a.sq_indice = p_sq_indice))
        and  ((p_sq_coluna is null) or (p_sq_coluna is not null and a.sq_coluna = p_sq_coluna));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;