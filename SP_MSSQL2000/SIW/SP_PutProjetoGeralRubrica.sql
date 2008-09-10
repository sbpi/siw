alter procedure dbo.SP_PutProjetoRubrica
   (@p_operacao             varchar(1),
    @p_chave                int,
    @p_chave_aux            int    = null,
    @p_sq_cc                int, 
    @p_codigo               varchar(100)  = null,
    @p_nome                 varchar(100)  = null,
    @p_descricao            varchar(2000)  = null,
    @p_ativo                varchar(1),
    @p_aplicacao_financeira varchar(1),
    @p_copia                int    = null
   ) as
   Declare @w_chave   numeric(18);
begin
   If @p_operacao = 'I' Begin -- Inclusão
      -- Recupera o valor da próxima chave
      Set @w_chave = @@Identity      
      -- Insere registro na tabela de recursos
      Insert Into pj_rubrica
         ( sq_projeto_rubrica, sq_siw_solicitacao,       sq_cc,        codigo  , nome  , descricao  , ativo, aplicacao_financeira)
      Values 
         ( @w_chave,            @p_chave,                @p_sq_cc ,      @p_codigo, @p_nome, @p_descricao, @p_ativo, @p_aplicacao_financeira);
         
      -- Se for cópia, herda o cronograma desembolso
      If @p_copia is not null Begin
         insert into pj_rubrica_cronograma
           (sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real)
         (select @w_chave, inicio, fim, valor_previsto, valor_real
            from pj_rubrica_cronograma a
           where a.sq_projeto_rubrica = @p_copia
         );
      End
   End Else If @p_operacao = 'A' Begin -- Alteração
      -- Atualiza a tabela de recursos
      Update pj_rubrica set
          sq_cc                = @p_sq_cc,
          codigo               = @p_codigo,
          nome                 = @p_nome,
          descricao            = @p_descricao,
          ativo                = @p_ativo,
          aplicacao_financeira = @p_aplicacao_financeira                       
      where sq_siw_solicitacao = @p_chave
        and sq_projeto_rubrica = @p_chave_aux;
   End Else If @p_operacao = 'E' Begin -- Exclusão
      -- Remove o registro na tabela de recursos
      delete pj_rubrica 
       where sq_siw_solicitacao  = @p_chave
         and sq_projeto_rubrica  = @p_chave_aux;
   End
end
