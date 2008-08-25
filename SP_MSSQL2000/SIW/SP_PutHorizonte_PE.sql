alter procedure dbo.sp_PutHorizonte_PE
   (@p_operacao varchar(1)       ,
    @p_chave    int         =null,
    @p_cliente  int         =null,
    @p_nome     varchar(30)      ,
    @p_ativo    varchar(1)  =null
   ) as
begin
   If @p_operacao = 'I' begin
      -- Insere registro
      insert into pe_horizonte (cliente, nome, ativo)
      (select @p_cliente, @p_nome,  @p_ativo);
   end else if @p_operacao = 'A' begin
      -- Altera registro
      update pe_horizonte
         set 
             cliente      = @p_cliente,
             nome         = @p_nome,
             ativo        = @p_ativo
       where sq_pehorizonte = @p_chave;
   end else if @p_operacao = 'E' begin
      -- Exclui registro
      delete pe_horizonte
       where sq_pehorizonte = @p_chave;
   End
end