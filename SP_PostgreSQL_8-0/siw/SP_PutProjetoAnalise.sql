create or replace FUNCTION SP_PutProjetoAnalise
   (p_chave           numeric,
    p_analise1        varchar,
    p_analise2        varchar,
    p_analise3        varchar,
    p_analise4        varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Altera registro
   update pj_projeto set
          analise1        = p_analise1,
          analise2        = p_analise2,
          analise3        = p_analise3,
          analise4        = p_analise4
    where sq_siw_solicitacao = p_chave;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;