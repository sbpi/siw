create or replace FUNCTION SP_GetSolicGR
   (p_menu      numeric,
    p_pessoa    numeric,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If Substr(p_restricao,1,4) = 'GRDM' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select a.sq_unidade, a.nome, a.sigla,
                (select count(*) qt_solic from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and sq_menu         = p_menu) qt_solic,
                (select sum(valor) from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and sq_menu         = p_menu) vl_previsto,
                (select count(*) from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and conclusao is not null
                    and sq_menu         = p_menu)  qt_conc,
                (select count(*) from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and valor      between 0 and 100
                    and sq_menu         = p_menu) vl_faixa1,
                (select count(*) from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and valor      between 101 and 200
                    and sq_menu         = p_menu) vl_faixa2,
                (select count(*) from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and valor      > 201
                    and sq_menu         = p_menu) vl_faixa3
           from eo_unidade a
          where a.sq_pessoa = p_pessoa;
   Elsif Substr(p_restricao,1,4) = 'GRPR' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select a.sq_unidade, a.nome, a.sigla,
                (select count(*) qt_solic from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and sq_menu         = p_menu) qt_solic,
                (select sum(valor) from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and sq_menu         = p_menu) vl_previsto,
                (select count(*) from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and conclusao is not null
                    and sq_menu         = p_menu)  qt_conc,
                (select count(*) from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and valor      between 0 and 100
                    and sq_menu         = p_menu) vl_faixa1,
                (select count(*) from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and valor      between 101 and 200
                    and sq_menu         = p_menu) vl_faixa2,
                (select count(*) from siw_solicitacao
                  where sq_unidade = a.sq_unidade
                    and valor      > 201
                    and sq_menu         = p_menu) vl_faixa3
           from eo_unidade a
          where a.sq_pessoa = p_pessoa;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;