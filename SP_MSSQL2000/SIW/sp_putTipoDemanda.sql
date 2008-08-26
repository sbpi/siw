create procedure dbo.sp_PutTipoDemanda
   (@p_operacao         varchar(1),
    @p_chave            int          =null,
    @p_cliente          int          =null,
    @p_nome             varchar(60)  =null,
    @p_sigla            varchar(20)  =null,
    @p_descricao        varchar(500) =null,
    @p_unidade          int          =null,
    @p_reuniao          varchar(1)   =null,
    @p_ativo            varchar(1)   =null
   ) as
begin
   If @p_operacao = 'I' begin
      -- Insere registro
      insert into gd_demanda_tipo (cliente, nome, sigla, descricao, sq_unidade, reuniao, ativo)
      (select @p_cliente, @p_nome, upper(@p_sigla), @p_descricao, @p_unidade, @p_reuniao, @p_ativo);
   end else if @p_operacao = 'A' begin
      -- Altera registro
      update gd_demanda_tipo
         set cliente          = @p_cliente,
             nome             = @p_nome,
             sigla            = upper(@p_sigla),
             descricao        = @p_descricao,
             sq_unidade       = @p_unidade,
             reuniao          = @p_reuniao,
             ativo            = @p_ativo
       where sq_demanda_tipo = @p_chave;
   end else if @p_operacao = 'E' begin
      -- Exclui registro
      delete gd_demanda_tipo
       where sq_demanda_tipo = @p_chave;
   End
end
