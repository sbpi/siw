create procedure dbo.SP_PutContasCronograma
   (@p_operacao             varchar(1),
    @p_chave                int  = null,
    @p_siw_solicitacao      int,
    @p_prestacao_contas     int, 
    @p_inicio               datetime,
    @p_fim                  datetime,
    @p_limite               datetime
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into siw_contas_cronograma
          (sq_siw_solicitacao, sq_prestacao_contas, inicio,   fim,   limite)
        values
          (@p_siw_solicitacao,  @p_prestacao_contas,  @p_inicio, @p_fim, @p_limite);

   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update siw_contas_cronograma
         set inicio           = @p_inicio,
             fim              = @p_fim,
             limite           = @p_limite
       where sq_contas_cronograma = @p_chave;
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete siw_contas_registro   where sq_contas_cronograma = @p_chave;
      delete siw_contas_cronograma where sq_contas_cronograma = @p_chave;
   End Else If @p_operacao = 'F' Begin
      -- Exclui registro
      delete siw_contas_registro   where sq_contas_cronograma in (select x.sq_contas_cronograma
                                                                    from siw_contas_cronograma x
                                                                   where x.sq_siw_solicitacao = @p_siw_solicitacao);
      delete siw_contas_cronograma where sq_siw_solicitacao = @p_siw_solicitacao;
   End
end
