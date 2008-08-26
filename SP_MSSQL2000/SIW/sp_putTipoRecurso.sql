alter procedure dbo.sp_putTipoRecurso
   (@p_operacao   varchar(1),
    @p_cliente    int           =null,
    @p_chave      int           =null,
    @p_chave_pai  int           =null,
    @p_nome       varchar(60)   =null,
    @p_sigla      varchar(15)   =null,
    @p_gestora    int           =null,
    @p_descricao  varchar(2000) =null,
    @p_ativo      varchar(1)    =null
   ) as
begin
   If @p_operacao in ('I','C') begin
      -- Insere registro
      insert into eo_tipo_recurso
        (cliente,   sq_tipo_pai, nome,   sigla,          unidade_gestora,  descricao,   ativo)
      values
        (@p_cliente, @p_chave_pai, @p_nome, upper(@p_sigla), @p_gestora,        @p_descricao, @p_ativo);
   end else if @p_operacao = 'A' begin
      -- Altera registro
      update eo_tipo_recurso
         set sq_tipo_pai     = @p_chave_pai,
             nome            = @p_nome,
             sigla           = upper(@p_sigla),
             unidade_gestora = @p_gestora,
             descricao       = @p_descricao
       where sq_tipo_recurso = @p_chave;
   end else if @p_operacao = 'E' begin
      -- Exclui registro
      delete eo_tipo_recurso where sq_tipo_recurso = @p_chave;
   end else if @p_operacao = 'T' begin
      -- Ativa registro
      update eo_tipo_recurso set ativo = 'S' where sq_tipo_recurso = @p_chave;
   end else if @p_operacao = 'D' begin
      -- Desativa registro
      update eo_tipo_recurso set ativo = 'N' where sq_tipo_recurso = @p_chave;
   End
end