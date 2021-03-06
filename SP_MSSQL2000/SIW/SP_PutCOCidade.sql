
create procedure dbo.SP_PutCOCidade
   (@operacao                 varchar(1),
    @chave                int         = null,
    @p_ddd                      varchar(4)  = null,
    @p_codigo_ibge              varchar(20) = null,
    @p_sq_pais                  int         = null,
    @p_sq_regiao                int         = null,
    @p_co_uf                    varchar(3)  = null,
    @p_nome                     varchar(60) = null,
    @p_capital                  varchar(1)  = null
   ) as
begin
   If @operacao = 'I' Begin
      -- Insere registro
      insert into co_cidade (ddd, codigo_ibge, sq_pais, sq_regiao, co_uf, nome, capital) 
         (select rtrim(ltrim(@p_ddd)),
                 rtrim(ltrim(@p_codigo_ibge)),
                 @p_sq_pais,
                 a.sq_regiao,
                 @p_co_uf,
                 rtrim(ltrim(upper(@p_nome))),
                 @p_capital
            from co_uf a 
           where a.co_uf   = @p_co_uf
             and a.sq_pais = @p_sq_pais
         )
   End Else If @operacao = 'A' Begin
      -- Altera registro
     update co_cidade set 
        ddd         = rtrim(ltrim(@p_ddd)),
        codigo_ibge = rtrim(ltrim(@p_codigo_ibge)),
        sq_pais     = @p_sq_pais,
        sq_regiao   = (select sq_regiao from co_uf where co_uf = @p_co_uf and sq_pais = @p_sq_pais),
        co_uf       = @p_co_uf,
        nome        = rtrim(ltrim(upper(@p_nome))),
        capital     = @p_capital
     where sq_cidade = @chave
     
   End Else If @operacao = 'E' Begin
      -- Exclui registro
      delete co_cidade where sq_cidade = @chave
   End
end
