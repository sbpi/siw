create or replace FUNCTION SP_PutProjetoInfo
   (p_chave                     numeric,
    p_descricao                 varchar,
    p_justificativa             varchar,    
    p_problema                  varchar,
    p_ds_acao                   varchar,
    p_publico_alvo              varchar,
    p_estrategia                varchar,
    p_indicadores               varchar,
    p_objetivo                  varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Altera os registro
      update siw_solicitacao set
        descricao = trim(p_descricao),
        justificativa = trim(p_justificativa)
        where sq_siw_solicitacao = p_chave;
      
      update or_acao set
        problema     = trim(p_problema),
        descricao    = trim(p_ds_acao),
        publico_alvo = trim(p_publico_alvo),
        estrategia   = trim(p_estrategia),
        indicadores  = trim(p_indicadores),
        objetivo     = trim(p_objetivo)
        where sq_siw_solicitacao = p_chave;
   END; $$ LANGUAGE 'PLPGSQL' VOLATILE;