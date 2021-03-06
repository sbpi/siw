SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO




create procedure dbo. AC_CriaParametro 
	(@p_cliente 	   int, 
	 @p_codigo_interno varchar(50) output) as
Begin
  Declare @w_existe       int
  Declare @w_sequencial   int        , 
	  @w_ano_corrente int        , 
	  @w_prefixo      varchar(10), 
	  @w_sufixo 	  varchar(10)

  -- Verifica se existe um registro criado para o cliente.
  select @w_existe = count(*) from ac_parametro where cliente = @p_cliente
  If @w_existe = 0 Begin
     insert into ac_parametro 
            (cliente,    sequencial, ano_corrente,     prefixo, sufixo) 
     values (@p_cliente, 1,          year(getdate()),  'AC-',   null)
  End
  
  -- Recupera e bloqueia o sequencial a ser usado no acordo
  select @w_sequencial = sequencial, @w_ano_corrente = ano_corrente,
         @w_prefixo    = prefixo,    @w_sufixo       = sufixo
    from ac_parametro
   where cliente = @p_cliente

  -- Verifica se há necessidade de reinicializar o sequencial em função da troca do ano
  If year(getdate()) <> @w_ano_corrente Begin
     Set @w_ano_corrente = year(getdate())
     Set @w_sequencial   = 1

     Update ac_parametro Set
         ano_corrente = @w_ano_corrente,
         sequencial   = @w_sequencial
     Where cliente    = @p_cliente
  End
  
  Update ac_parametro set 
     sequencial = sequencial + 1
  Where cliente = @p_cliente
  
  --  Retorna o sequencial a ser usado no acordo
  Set @p_codigo_interno = IsNull(@w_prefixo,'')+
                          Cast(@w_sequencial as varchar)+'/'+
                          Cast(@w_ano_corrente as varchar)+
                          IsNull(@w_sufixo,'')
end




GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

