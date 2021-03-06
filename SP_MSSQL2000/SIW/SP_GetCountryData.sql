alter procedure dbo.SP_GetCountryData (@p_sq_pais int) as
begin
   -- Recupera os dados do país
	select a.*,
           b.codigo as cd_moeda, b.nome as nm_moeda, b.sigla as sg_moeda, b.simbolo as sb_moeda
     from co_pais             a 
          left  join co_moeda b on (a.sq_moeda = b.sq_moeda)
    where a.sq_pais = @p_sq_pais;
end