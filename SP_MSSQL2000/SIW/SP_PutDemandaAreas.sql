alter procedure dbo.SP_PutDemandaAreas
   (@p_operacao           varchar(1),
    @p_chave              int            = null,
    @p_chave_aux          int            = null,
    @p_papel              varchar(2000)  = null
   ) as
begin
   If @p_operacao = 'I' Begin -- Inclusão
      -- Insere registro na tabela de áreas envolvidas
      Insert Into gd_demanda_envolv 
         ( sq_unidade,  sq_siw_solicitacao, papel )
      Values
         ( @p_chave_aux, @p_chave,            rtrim(@p_papel) )
   End Else If @p_operacao = 'A' Begin -- Alteração
      -- Atualiza a tabela de áreas envolvidas
      Update gd_demanda_envolv set
          papel            = rtrim(@p_papel)
      where sq_siw_solicitacao = @p_chave
        and sq_unidade         = @p_chave_aux
   End Else If @p_operacao = 'E' Begin -- Exclusão
      -- Remove o registro na tabela de áreas envolvidas
      delete gd_demanda_envolv  
       where sq_siw_solicitacao = @p_chave
         and sq_unidade         = @p_chave_aux
   End
end