alter procedure dbo.Sp_PutDataEspecial
   (@p_operacao      varchar(1),
    @p_chave         int         = null,
    @p_cliente       int         = null,
    @p_sq_pais       int         = null,
    @p_co_uf         varchar(3)  = null,
    @p_sq_cidade     int         = null,
    @p_tipo          varchar(1)  = null,
    @p_data_especial varchar(10) = null,
    @p_nome          varchar(60) = null,
    @p_abrangencia   varchar(1)  = null,
    @p_expediente    varchar(1)  = null,    
    @p_ativo         varchar(1)  = null
   ) as
begin
   -- Grava uma modalidade de contratação
   If @p_operacao = 'I' Begin
      -- Insere registro
      insert into eo_data_especial
        (cliente, sq_pais, co_uf, sq_cidade, tipo, data_especial, nome, abrangencia, expediente, ativo)
      values
        (@p_cliente, @p_sq_pais, @p_co_uf, @p_sq_cidade, @p_tipo, @p_data_especial, rtrim(ltrim(@p_nome)), @p_abrangencia, @p_expediente, @p_ativo);
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update eo_data_especial
         set sq_pais       = @p_sq_pais,
             co_uf         = @p_co_uf,
             sq_cidade     = @p_sq_cidade,
             tipo          = @p_tipo,
             data_especial = @p_data_especial,
             nome          = rtrim(ltrim(@p_nome)),
             abrangencia   = @p_abrangencia,
             expediente    = @p_expediente,
             ativo = @p_ativo
       where sq_data_especial = @p_chave;
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete eo_data_especial where sq_data_especial = @p_chave;
   End
end
