CREATE OR REPLACE FUNCTION siw.SP_GetEtapaAnexo
   (p_chave     numeric,
    p_chave_aux numeric,
    p_cliente   numeric)

  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os anexos de uma etapa
   open p_result for 
      select a.sq_projeto_etapa as chave,
             b.sq_siw_arquivo as chave_aux, b.cliente, b.nome, b.descricao, 
             b.inclusao, b.tamanho, b.tipo, b.caminho
        from siw.pj_projeto_etapa_arq   a
             inner join siw.siw_arquivo b on (a.sq_siw_arquivo = b.sq_siw_arquivo)
       where a.sq_projeto_etapa   = p_chave
         and b.cliente            = p_cliente
         and ((p_chave_aux        is null) or (p_chave_aux is not null and b.sq_siw_arquivo = p_chave_aux));
         return p_result;
End 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetEtapaAnexo
   (p_chave     numeric,
    p_chave_aux numeric,
    p_cliente   numeric) OWNER TO siw;
