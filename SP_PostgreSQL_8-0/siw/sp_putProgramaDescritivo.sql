create or replace FUNCTION SP_PutProjetoDescritivo
   (p_chave                numeric,
    p_objetivo_superior   varchar,
    p_descricao           varchar,
    p_exclusoes           varchar,
    p_premissas           varchar,
    p_restricoes          varchar,
    p_justificativa       varchar  
   ) RETURNS VOID AS $$  
DECLARE
BEGIN
   -- Altera os registro
   Update siw_solicitacao set
      descricao         = trim(p_descricao),
      justificativa     = trim(p_justificativa)
   where sq_siw_solicitacao = p_chave;
 
   -- Atualiza a tabela de projetos
   Update pj_projeto set
      objetivo_superior = trim(p_objetivo_superior),
      exclusoes         = trim(p_exclusoes),
      premissas         = trim(p_premissas),
      restricoes        = trim(p_restricoes)
   where sq_siw_solicitacao = p_chave;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;