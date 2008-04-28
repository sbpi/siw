CREATE OR REPLACE FUNCTION siw.SP_GetFinacAcaoPPA
   (p_chave              numeric,
    p_cliente            numeric,
    p_sq_acao_ppa        numeric)

  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   open p_result for
      select b.nome, b.codigo as cd_ppa, b.aprovado, b.empenhado, b.liquidado, b.saldo, b.liquidar,
             a.sq_acao_ppa, c.codigo as cd_ppa_pai, a.observacao
        from siw.or_acao_financ a
             left outer join siw.or_acao_ppa b on a.sq_acao_ppa     = b.sq_acao_ppa
             left outer join siw.or_acao_ppa c on b.sq_acao_ppa_pai = c.sq_acao_ppa
       where a.sq_siw_solicitacao = p_chave
         and (p_sq_acao_ppa is null or (p_sq_acao_ppa is not null and a.sq_acao_ppa = p_sq_acao_ppa));
         return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetFinacAcaoPPA
   (p_chave              numeric,
    p_cliente            numeric,
    p_sq_acao_ppa        numeric) OWNER TO siw;
