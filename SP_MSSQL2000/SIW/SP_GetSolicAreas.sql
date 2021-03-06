create procedure dbo.SP_GetSolicAreas
   (@p_chave     int,
    @p_chave_aux int=null,
    @p_restricao varchar(50)) as
begin
     select a.sq_unidade, a.sq_siw_solicitacao, a.papel, null as interesse_positivo, null as influencia,
            null as nm_interesse, null as nm_influencia,
            b.nome, b.informal, b.vinculada, b.adm_central
       from gd_demanda_envolv   a,
            eo_unidade          b
      where a.sq_unidade         = b.sq_unidade
         and a.sq_siw_solicitacao = @p_chave
         and (@p_chave_aux is null or (@p_chave_aux is not null and a.sq_unidade = @p_chave_aux))
     UNION
     select a.sq_unidade, a.sq_siw_solicitacao, a.papel, a.interesse_positivo, a.influencia,
            case interesse_positivo when 'S' then '+' else '-' end as nm_interesse,
            case influencia when 0 then 'Alta' when 1 then 'Média' when 2 then 'Baixa' else '---' end as nm_influencia,
            b.nome, b.informal, b.vinculada, b.adm_central
       from pj_projeto_envolv   a,
            eo_unidade          b
      where a.sq_unidade         = b.sq_unidade
         and a.sq_siw_solicitacao = @p_chave
         and (@p_chave_aux is null or (@p_chave_aux is not null and a.sq_unidade = @p_chave_aux));
end
