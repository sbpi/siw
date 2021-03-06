alter  procedure Sp_PutCOBanco
   (@operacao                   varchar(1),
    @chave                      int = null,
    @p_nome                     varchar(60),
    @p_codigo                   varchar(30),
    @p_padrao                   varchar(1),
    @p_ativo                    varchar(1),
    @p_exige                    varchar(1)   
   ) as
begin
   If @operacao = 'I' begin
      -- Insere registro
      insert into co_banco ( nome, codigo, padrao, ativo, exige_operacao)
         (select 
                 ltrim(rtrim(upper(@p_nome))),
                 ltrim(rtrim(@p_codigo)),                 
                 @p_padrao,
                 @p_ativo,
                 @p_exige
            
         );
   end else if @operacao = 'A' begin
      -- Altera registro
      update co_banco   set 
         nome                 = ltrim(rtrim(upper(@p_nome))),
         codigo               = ltrim(rtrim(@p_codigo)),
         padrao               = @p_padrao,
         ativo                = @p_ativo,
         exige_operacao       = @p_exige
      where sq_banco    = @chave;
   end else if @operacao = 'E' begin
      -- Exclui registro
      delete co_banco where sq_banco = @chave;
   End 
end 