alter FUNCTION dbo.sp_fCountProjetoEtapa 
(
	@p_chave int,
    @p_direcao varchar(4)
)Returns int AS
BEGIN
	-- Declare the return variable here
	DECLARE @Result int;
    select @result = count(*) from dbo.sp_fgetprojetoetapa(@p_chave, @p_direcao)

	RETURN @result

END