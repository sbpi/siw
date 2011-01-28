create or replace FUNCTION SP_GetLancamentoLog
   (p_chave     numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   open p_result for 
      select a.cadastrador, a.destinatario
        from fn_lancamento_log            a
       where a.sq_siw_solicitacao = p_chave;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;