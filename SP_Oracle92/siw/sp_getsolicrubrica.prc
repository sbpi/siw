create or replace procedure SP_GetSolicRubrica
   (p_chave     in number,
    p_chave_aux in number    default null,
    p_ativo     in varchar2  default null,
    p_restricao in varchar2  default null,
    p_result    out sys_refcursor
   ) is
begin
   open p_result for 
      select a.sq_projeto_rubrica, a.sq_cc, a.codigo, a.nome, a.descricao, a.ativo,
             a.valor_inicial, a.entrada_prevista, a.entrada_real, a.saida_prevista, a.saida_real
        from pj_rubrica            a
       where (p_chave     is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
         and (p_chave_aux is null or (p_chave_aux is not null and a.sq_projeto_rubrica = p_chave_aux))
         and (p_ativo     is null or (p_ativo     is not null and a.ativo              = p_ativo));
End SP_GetSolicRubrica;
/
