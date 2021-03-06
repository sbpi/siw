alter procedure dbo.Sp_PutProjetoAreas
   (@p_operacao            varchar(1),
    @p_chave               int,
    @p_chave_aux           int          =null,
    @p_interesse           varchar(1)   =null,
    @p_influencia          int =null,
    @p_papel               varchar(2000) =null
   ) as
begin
   If @p_operacao = 'I' Begin -- Inclus�o
      -- Insere registro na tabela de �reas envolvidas
      Insert Into pj_projeto_envolv 
         ( sq_unidade,   sq_siw_solicitacao,  interesse_positivo,    influencia,   papel )
      Values
         ( @p_chave_aux, @p_chave,                     @p_interesse,  @p_influencia,  rtrim(ltrim(@p_papel)) );
   End Else If @p_operacao = 'A' Begin -- Altera��o
      -- Atualiza a tabela de �reas envolvidas
      Update pj_projeto_envolv set
          interesse_positivo   = @p_interesse,
          influencia           = @p_influencia,
          papel                = rtrim(ltrim(@p_papel))
      where sq_siw_solicitacao = @p_chave
        and sq_unidade         = @p_chave_aux;
   End Else If @p_operacao = 'E' Begin -- Exclus�o
      -- Remove o registro na tabela de �reas envolvidas
      delete pj_projeto_envolv  
       where sq_siw_solicitacao = @p_chave
         and sq_unidade         = @p_chave_aux;
   End
end