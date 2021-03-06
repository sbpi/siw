SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO


create procedure dbo.SP_PutAgree
   (@p_operacao                 varchar(1),
    @p_chave                    int           = null,
    @p_cliente                  int           = null,
    @p_sq_tipo_acordo           int           = null,
    @p_sq_cc                    int           = null,
    @p_inicio                   datetime      = null,
    @p_fim                      datetime      = null,
    @p_valor_inicial            int           = null,
    @p_codigo_externo           varchar(60)   = null,
    @p_objeto                   varchar(2000) = null,
    @p_observacao               varchar(1000) = null,
    @p_dia_vencimento           int           = null,
    @p_chave_nova               int           output,
    @p_codigo_interno           varchar(50)   output
   ) as
begin

   Declare @w_chave  int
   Declare @w_codigo varchar(50)

   If @p_operacao = 'I' Begin
   
      -- Insere registro
      insert into ac_acordo (
              fim,              dia_vencimento,    codigo_externo,           observacao,
              cliente,          sq_tipo_acordo,    sq_cc,                    inicio,
              valor_inicial,    objeto)
      values( @p_fim,           @p_dia_vencimento, rtrim(@p_codigo_externo), rtrim(@p_observacao),
              @p_cliente,       @p_sq_tipo_acordo, @p_sq_cc,                 @p_inicio,
              @p_valor_inicial, rtrim(@p_objeto)
      )
      
      -- Pega o valor da chave primária
      Set @w_chave = @@IDENTITY
      
      -- Recupera o código interno  do acordo, gerado por trigger
      exec AC_CriaParametro @p_cliente, @w_codigo output
      Set @p_codigo_interno = @w_codigo

      -- Atualiza o código interno no registro incluído
      update ac_acordo set codigo_interno = @p_codigo_interno where sq_acordo = @w_chave
      
   End Else If @p_operacao = 'A' Begin
      -- Altera registro
      update ac_acordo set
         fim             = @p_fim,
         codigo_externo  = rtrim(@p_codigo_externo),
         dia_vencimento  = @p_dia_vencimento,
         observacao      = rtrim(@p_observacao),
         sq_tipo_acordo  = @p_sq_tipo_acordo,
         sq_cc           = @p_sq_cc,
         inicio          = @p_inicio,
         objeto          = rtrim(@p_objeto)
      where sq_acordo    = @p_chave
   End Else If @p_operacao = 'E' Begin
      -- Exclui registro
      delete ac_acordo where sq_acordo = @p_chave
   End
   
   -- Devolve a chave
   If @p_chave is not null 
      Set @p_chave_nova = @p_chave
   Else 
      Set @p_chave_nova = @w_chave
end


GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

