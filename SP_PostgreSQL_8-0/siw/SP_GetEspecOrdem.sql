CREATE OR REPLACE FUNCTION siw.SP_GetEspecOrdem
   (p_chave  numeric)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera as etapas acima da informada
   open p_result for 
      select sq_especificacao_despesa, especificacao_pai, nome, codigo
        from siw.ct_especificacao_despesa;
     /* start with sq_especificacao_despesa = p_chave
      connect by prior especificacao_pai = sq_especificacao_despesa; */
      return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.Sp_GetColuna
   (p_cliente      numeric,
    p_chave        numeric, 
    p_sq_tabela    numeric,
    p_sq_dado_tipo varchar,
    p_sq_sistema   numeric, 
    p_sq_usuario   numeric, 
    p_nome         varchar,
    p_esq_tab      numeric) OWNER TO siw;
