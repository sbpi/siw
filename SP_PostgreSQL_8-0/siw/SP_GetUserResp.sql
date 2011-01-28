create or replace FUNCTION SP_GetUserResp
   (p_chave       numeric,
    p_restricao   varchar,
    p_result     REFCURSOR
    ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera as unidade que a pessoa é titular ou substituta.
      open p_result for      
      select a.sq_unidade, a.sq_pessoa, a.tipo_respons, a.inicio, a.fim,
             case a.tipo_respons when 'T' then 'Titular' else 'Substituto' end as nm_tipo_respons,
             b.nome, b.sigla
        from eo_unidade_resp       a
             inner join eo_unidade b on (a.sq_unidade = b.sq_unidade)
       where a.sq_pessoa = p_chave
         and a.fim is null;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;