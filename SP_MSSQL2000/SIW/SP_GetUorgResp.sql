alter procedure dbo.SP_GetUorgResp 
    (
     @p_sq_unidade int
    ) as
begin
   -- Recupera os responsáveis titular e substituto da unidade selecionada
     select a.sq_unidade, a.sq_unidade_pai, 
            case when b.sq_pessoa is null then '---' else c.nome+' (desde '+convert(varchar(8),b.inicio,3)+')' end titular1,
            case when d.sq_pessoa is null then '---' else e.nome+' (desde '+convert(varchar(8),d.inicio,3)+')' end substituto1,
            b.sq_pessoa titular2,    b.inicio inicio_titular, 
            d.sq_pessoa substituto2, d.inicio inicio_substituto
      from eo_unidade a
           left outer join eo_unidade_resp b on (a.sq_unidade = b.sq_unidade and b.tipo_respons = 'T' and b.fim is null)
           left outer join co_pessoa       c on (b.sq_pessoa  = c.sq_pessoa)
           left outer join eo_unidade_resp d on (a.sq_unidade = d.sq_unidade and d.tipo_respons = 'S' and d.fim is null)
           left outer join co_pessoa       e on (d.sq_pessoa  = e.sq_pessoa)
     where a.sq_unidade  = @p_sq_unidade
end