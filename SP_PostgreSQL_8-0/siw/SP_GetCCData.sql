create or replace function SP_GetCCData
   (p_sqcc      numeric,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera os dados do centro de ccusto informado
   open p_result for 
      select a.sq_cc_pai, a.nome, a.sigla, a.descricao, a.ativo, a.receita, a.regular
        from ct_cc a
       where sq_cc = p_sqcc;
   return p_result;
end; $$ language 'plpgsql' volatile;

