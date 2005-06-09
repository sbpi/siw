create or replace procedure SP_GetLancamentoLog
   (p_chave     in number,
    p_result    out sys_refcursor) is
begin
   open p_result for 
      select a.cadastrador, a.destinatario
        from fn_lancamento_log            a
       where a.sq_siw_solicitacao = p_chave;
End SP_GetLancamentoLog;
/

