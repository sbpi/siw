alter procedure dbo.SP_GetModData (@p_sq_modulo int) as
begin
   --Recupera os dados de um módulo
      select * from siw_modulo where sq_modulo = @p_sq_modulo
end