alter procedure dbo.sp_PutNatureza_PE
   (@p_operacao varchar(1),
    @p_chave    int         = null,
    @p_cliente  int         = null,
    @p_nome     varchar(30) = null,
    @p_ativo    varchar(1)  = null
   ) as
begin
   If @p_operacao = 'I' begin
      -- Insere registro
      insert into pe_natureza (cliente, nome, ativo)
      (select @p_cliente, @p_nome,  @p_ativo);
   end else if @p_operacao = 'A' begin
      -- Altera registro
      update pe_natureza
         set cliente     = @p_cliente,
             nome        = @p_nome,
             ativo       = @p_ativo
       where sq_penatureza = @p_chave;
   end else if @p_operacao = 'E' begin
      -- Exclui registro
      delete pe_natureza
       where sq_penatureza = @p_chave;
   End
end