alter procedure dbo.SP_GetCCData
   (@p_chave     int) as
begin
   -- Recupera os dados do centro de ccusto informado
      select a.sq_cc_pai, a.nome, a.sigla, a.descricao, a.ativo, a.receita, a.regular
        from ct_cc a
       where sq_cc = @p_chave
end

