create or replace FUNCTION SP_PutProjetoDescritivo
   (p_chave                  numeric,
    p_instancia_articulacao varchar,
		p_composicao_instancia  varchar,
	  p_estudos               varchar,
    p_objetivo_superior     varchar,
    p_descricao             varchar,
    p_exclusoes             varchar,
    p_premissas             varchar,
    p_restricoes            varchar,
    p_justificativa         varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Altera os registro
   Update siw_solicitacao set
      descricao         = p_descricao,
      justificativa     = p_justificativa
   where sq_siw_solicitacao = p_chave;

   -- Atualiza a tabela de projetos
   Update pj_projeto set
      instancia_articulacao = p_instancia_articulacao,  
	  	composicao_instancia  = p_composicao_instancia,
  	  estudos               = p_estudos,
      objetivo_superior     = p_objetivo_superior,
      exclusoes             = p_exclusoes,
      premissas             = p_premissas,
      restricoes            = p_restricoes
   where sq_siw_solicitacao = p_chave;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;