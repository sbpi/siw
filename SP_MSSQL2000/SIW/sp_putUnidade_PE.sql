alter procedure dbo.sp_putUnidade_PE
   (@p_operacao     varchar(1),
    @p_cliente      int           =null,
    @p_chave        int           =null,
    @p_descricao    varchar(2000) =null,
    @p_planejamento varchar(1)    =null,
    @p_execucao     varchar(1)    =null,
    @p_recursos     varchar(1)    =null,
    @p_ativo        varchar(1)    =null
   ) as
begin
   If @p_operacao = 'I' begin
      -- Insere registro
      insert into pe_unidade (sq_unidade, cliente, descricao, planejamento, execucao, gestao_recursos, ativo) 
      values (@p_chave, @p_cliente, @p_descricao, @p_planejamento, @p_execucao, @p_recursos, @p_ativo);
   end else if @p_operacao = 'A' begin
      update pe_unidade
         set descricao       = @p_descricao,
             planejamento    = @p_planejamento,
             execucao        = @p_execucao,
             gestao_recursos = @p_recursos,
             ativo           = @p_ativo
       where sq_unidade = @p_chave;
             
   end else if @p_operacao = 'E' begin
      -- Exclui registro
      delete pe_unidade where sq_unidade = @p_chave;
   End
end
