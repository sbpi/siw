CREATE OR REPLACE FUNCTION siw.SP_GetColunaColuna
   (p_chave           numeric,
    p_sq_coluna_pai   numeric,
    p_sq_coluna_filha numeric)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os tipos de Tabela
   open p_result for
   select a.sq_relacionamento as chave,
          b.nome as nm_coluna_pai, b.descricao as ds_coluna_pai,
          c.nome as nm_coluna_filha, c.descricao as ds_coluna_filha,
          d.nome as nm_dado_tipo,
          e.nome as nm_relacionamento
   from siw.dc_relac_cols                a
        inner join siw.dc_coluna         b on (a.coluna_pai        = b.sq_coluna)
        inner join siw.dc_coluna         c on (a.coluna_filha      = c.sq_coluna)
            inner join siw.dc_dado_tipo  d on (c.sq_dado_tipo      = d.sq_dado_tipo)
        inner join siw.dc_relacionamento e on (a.sq_relacionamento = e.sq_relacionamento)
   where ((p_chave           is null) or (p_chave           is not null and b.sq_coluna  = p_chave))
     and ((p_sq_coluna_pai   is null) or (p_sq_coluna_pai   is not null and b.sq_coluna = p_sq_coluna_pai))
     or  ((p_sq_coluna_filha is null) or (p_sq_coluna_filha is not null and c.sq_coluna = p_sq_coluna_filha));
     return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetColunaColuna
   (p_chave           numeric,
    p_sq_coluna_pai   numeric,
    p_sq_coluna_filha numeric) OWNER TO siw;
