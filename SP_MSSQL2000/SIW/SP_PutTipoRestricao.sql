alter procedure sp_PutTipoRestricao
   (@p_operacao varchar(1),
    @p_chave    int       = null,
    @p_cliente  int  ,
    @p_nome     varchar(30),
    @p_codigo   varchar(30) = null,
    @p_ativo    varchar(1) 
   ) as
begin
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into siw_tipo_restricao (cliente, nome, codigo_externo, ativo)
      (select @p_cliente, @p_nome,  @p_codigo, @p_ativo);
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update siw_tipo_restricao
         set 
             cliente        = @p_cliente,
             nome           = @p_nome,
             codigo_externo = @p_codigo,
             ativo          = @p_ativo
       where sq_tipo_restricao = @p_chave;
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete siw_tipo_restricao
       where sq_tipo_restricao = @p_chave;
   End
end