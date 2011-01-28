create or replace FUNCTION sp_putProgramaQualit
   (p_chave                     numeric,
    p_descricao                 varchar,
    p_justificativa             varchar,    
    p_publico_alvo              varchar,
    p_estrategia                varchar,
    p_observacao               varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Altera os registro
   update siw_solicitacao set
      descricao     = trim(p_descricao),
      justificativa = trim(p_justificativa),
      observacao    = trim(p_observacao)
   where sq_siw_solicitacao = p_chave;

   update pe_programa set
      publico_alvo  = trim(p_publico_alvo),
      estrategia    = trim(p_estrategia)
   where sq_siw_solicitacao = p_chave;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;