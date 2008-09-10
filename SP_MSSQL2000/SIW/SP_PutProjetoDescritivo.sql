create procedure SP_PutProjetoDescritivo
   (@p_chave               int            = null,
    @p_objetivo_superior   varchar(2000)  = null,
    @p_descricao           varchar(2000)  = null,
    @p_exclusoes           varchar(2000)  = null,
    @p_premissas           varchar(2000)  = null,
    @p_restricoes          varchar(2000)  = null,
    @p_justificativa       varchar(2000)  = null
   ) as
begin
   -- Altera os registro
   Update siw_solicitacao set
      descricao         = ltrim(rtrim(@p_descricao)),
      justificativa     = ltrim(rtrim(@p_justificativa))
   where sq_siw_solicitacao = @p_chave;

   -- Atualiza a tabela de projetos
   Update pj_projeto set
      objetivo_superior = ltrim(rtrim(@p_objetivo_superior)),
      exclusoes         = ltrim(rtrim(@p_exclusoes)),
      premissas         = ltrim(rtrim(@p_premissas)),
      restricoes        = ltrim(rtrim(@p_restricoes))
   where sq_siw_solicitacao = @p_chave;
end