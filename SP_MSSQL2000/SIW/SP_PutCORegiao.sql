alter procedure Sp_PutCORegiao
   (@operacao                 varchar(1),
    @chave                    int =  null,
    @p_sq_pais                  int =  null,
    @p_nome                     varchar(20),
    @p_sigla                    varchar(2), 
    @p_ordem                    int =  null  
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_regiao ( sq_pais, nome, sigla, ordem) 
         (select 
                 @p_sq_pais,
                 rtrim(ltrim(@p_nome)),
                 rtrim(ltrim(upper(@p_sigla))),
                 @p_ordem
           
         );
   end else if @operacao = 'A' Begin
      -- Altera registro
      update co_regiao set
         sq_pais              = @p_sq_pais,
         nome                 = rtrim(ltrim(@p_nome)),
         sigla                = rtrim(ltrim(upper(@p_sigla))),
         ordem                = @p_ordem
      where sq_regiao    = @chave;
    end else if @operacao = 'E' Begin
      -- Exclui registro
      delete co_regiao where sq_regiao = @chave;
   End 
end 