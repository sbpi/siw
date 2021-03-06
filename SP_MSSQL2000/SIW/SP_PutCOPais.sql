alter procedure Sp_PutCOPais
   (@operacao                 varchar(1),
    @chave                    int   = null,
    @p_nome                     varchar(60) = null,
    @p_ativo                    varchar(1) = null,
    @p_padrao                   varchar(1) = null,
    @p_ddi                      varchar(10) = null,
    @p_sigla                    varchar(3) = null,
    @p_moeda                    int   = null,
    @p_continente               int   = null
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_pais ( nome, ativo, padrao, ddi, sigla, sq_moeda, continente)
         (select 
                 ltrim(rtrim(@p_nome)),
                 @p_ativo,
                 @p_padrao,                 
                 @p_ddi,
                 @p_sigla,
                 @p_moeda,
                 @p_continente
            
         );
   end  else if @operacao = 'A' Begin
      -- Altera registro
      update co_pais set
         nome                 = ltrim(rtrim(@p_nome)),
         ativo                = @p_ativo,
         padrao               = @p_padrao,
         ddi                  = @p_ddi,
         sigla                = ltrim(rtrim(@p_sigla)),
         sq_moeda             = @p_moeda,
         continente           = @p_continente
      where sq_pais    = @chave;
   end  else if @operacao = 'E' Begin
      -- Exclui registro
      delete co_pais where sq_pais = @chave;
   End 
end 