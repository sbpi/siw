

alter procedure dbo.SP_GetDefGrpData
   (@p_chave int) as
begin
   -- Recupera os dados do grupo de deficiência
      select * from co_grupo_defic where sq_grupo_defic = @p_chave;
end
