create or replace procedure SP_GetCountryData
   (p_sq_pais in  number,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os dados do país
   open p_result for 
      select a.*,
             b.codigo as cd_moeda, b.nome as nm_moeda, b.sigla as sg_moeda, b.simbolo as sb_moeda
        from co_pais             a 
             left  join co_moeda b on (a.sq_moeda = b.sq_moeda)
       where a.sq_pais = p_sq_pais;
end SP_GetCountryData;
/
