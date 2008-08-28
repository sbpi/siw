alter procedure dbo.sp_GetGestor
  (@p_solicitacao int,
   @p_usuario     int
  ) as
begin
  Declare @Result                   varchar(10);
  Set @Result = 'N';

 select dbo.gestor(@p_solicitacao,@p_usuario);
end
