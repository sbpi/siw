create or replace procedure SP_GetSolicRubrica
   (p_chave                in number,
    p_chave_aux            in number    default null,
    p_ativo                in varchar2  default null,
    p_sq_rubrica_destino   in number    default null,
    p_aplicacao_financeira in varchar2  default null,
    p_restricao            in varchar2  default null,
    p_result    out        siw.sys_refcursor
   ) is
begin
   open p_result for 
      select a.sq_projeto_rubrica, a.sq_cc, a.codigo, a.nome, a.descricao, a.ativo,
             a.valor_inicial, a.entrada_prevista, a.entrada_real, a.saida_prevista, a.saida_real,
             decode(a.ativo,'S','Sim','Não') nm_ativo,
             decode(a.aplicacao_financeira,'S','Sim','Não') nm_aplicacao_financeira,
             b.nome nm_cc, a.aplicacao_financeira
        from pj_rubrica       a,
             ct_cc b          
       where (a.sq_cc = b.sq_cc) 
         and (p_chave                is null or (p_chave                is not null and a.sq_siw_solicitacao   = p_chave))
         and (p_chave_aux            is null or (p_chave_aux            is not null and a.sq_projeto_rubrica   = p_chave_aux))
         and (p_ativo                is null or (p_ativo                is not null and a.ativo                = p_ativo))
         and (p_sq_rubrica_destino   is null or (p_sq_rubrica_destino   is not null and a.sq_projeto_rubrica   <> p_sq_rubrica_destino))
         and (p_aplicacao_financeira is null or (p_aplicacao_financeira is not null and a.aplicacao_financeira = p_aplicacao_financeira));
End SP_GetSolicRubrica;
/
