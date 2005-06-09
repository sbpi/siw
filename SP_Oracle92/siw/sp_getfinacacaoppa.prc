create or replace procedure SP_GetFinacAcaoPPA
   (p_chave              in  number,
    p_cliente            in  number,
    p_sq_acao_ppa        in  number default null,
    p_result    out sys_refcursor) is
begin
   open p_result for 
      select b.nome, b.codigo cd_ppa, b.aprovado, b.empenhado, b.liquidado, b.saldo, b.liquidar, 
             a.sq_acao_ppa, c.codigo cd_ppa_pai, a.observacao
        from or_acao_financ a
             left outer join or_acao_ppa b on a.sq_acao_ppa     = b.sq_acao_ppa
             left outer join or_acao_ppa c on b.sq_acao_ppa_pai = c.sq_acao_ppa
       where a.sq_siw_solicitacao = p_chave
         and (p_sq_acao_ppa is null or (p_sq_acao_ppa is not null and a.sq_acao_ppa = p_sq_acao_ppa));
end SP_GetFinacAcaoPPA;
/

