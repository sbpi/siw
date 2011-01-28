create or replace FUNCTION SP_GetFinacAcaoPPA
   (p_chave               numeric,
    p_cliente             numeric,
    p_sq_acao_ppa         numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   open p_result for 
      select b.nome, b.codigo cd_ppa, b.aprovado, b.empenhado, b.liquidado, b.saldo, b.liquidar, 
             a.sq_acao_ppa, c.codigo cd_ppa_pai, a.observacao
        from or_acao_financ a
             left outer join or_acao_ppa b on a.sq_acao_ppa     = b.sq_acao_ppa
             left outer join or_acao_ppa c on b.sq_acao_ppa_pai = c.sq_acao_ppa
       where a.sq_siw_solicitacao = p_chave
         and (p_sq_acao_ppa is null or (p_sq_acao_ppa is not null and a.sq_acao_ppa = p_sq_acao_ppa));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;