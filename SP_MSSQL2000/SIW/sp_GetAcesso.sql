create procedure dbo.sp_GetAcesso
  (@p_solicitacao int,
   @p_usuario     int,
   @p_tramite     int = null
  ) as
begin
  Declare @Result  int;
  Set @Result = 0;

  select dbo.acesso(@p_solicitacao,@p_usuario,@p_tramite);
end
