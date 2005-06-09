create or replace procedure SP_GetCCData
   (p_sqcc      in  number,
    p_result    out sys_refcursor
   ) is
begin
   -- Recupera os dados do centro de ccusto informado
   open p_result for 
      select a.sq_cc_pai, a.nome, a.sigla, a.descricao, a.ativo, a.receita, a.regular
        from ct_cc a
       where sq_cc = p_sqcc;
end SP_GetCCData;
/

