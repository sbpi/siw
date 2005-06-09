create or replace procedure SP_GetEtapaMensal
   (p_chave     in number   default null,
    p_result    out sys_refcursor) is
begin
   open p_result for
      select a.referencia, a.execucao_fisica, a.execucao_financeira
        from pj_etapa_mensal a 
       where a.sq_projeto_etapa = p_chave;
End SP_GetEtapaMensal;
/

