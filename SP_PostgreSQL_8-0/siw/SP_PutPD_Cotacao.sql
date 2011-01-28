create or replace FUNCTION SP_PutPD_Cotacao
   (p_chave               numeric,
    p_valor               numeric,
    p_observacao          varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
  -- Atualiza o valor estimado para os bilhetes da viagem
  update pd_missao
     set cotacao_valor      = p_valor,
         cotacao_observacao = p_observacao
   where sq_siw_solicitacao = p_chave;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;