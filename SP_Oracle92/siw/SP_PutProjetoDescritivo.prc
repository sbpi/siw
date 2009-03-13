create or replace procedure SP_PutProjetoDescritivo
   (p_chave                 in  number   default null,
    p_instancia_articulacao in varchar2  default null,
		p_composicao_instancia  in varchar2  default null,
	  p_estudos               in varchar2  default null,
    p_objetivo_superior     in varchar2  default null,
    p_descricao             in varchar2  default null,
    p_exclusoes             in varchar2  default null,
    p_premissas             in varchar2  default null,
    p_restricoes            in varchar2  default null,
    p_justificativa         in varchar2  default null
   ) is
begin
   -- Altera os registro
   Update siw_solicitacao set
      descricao         = trim(p_descricao),
      justificativa     = trim(p_justificativa)
   where sq_siw_solicitacao = p_chave;

   -- Atualiza a tabela de projetos
   Update pj_projeto set
      instancia_articulacao = trim(p_instancia_articulacao),  
	  	composicao_instancia  = trim(p_composicao_instancia),
  	  estudos               = trim(p_estudos),
      objetivo_superior     = trim(p_objetivo_superior),
      exclusoes             = trim(p_exclusoes),
      premissas             = trim(p_premissas),
      restricoes            = trim(p_restricoes)
   where sq_siw_solicitacao = p_chave;
end SP_PutProjetoDescritivo;
/
