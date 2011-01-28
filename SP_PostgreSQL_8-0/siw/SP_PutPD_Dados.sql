create or replace FUNCTION SP_PutPD_Dados
   (p_chave               numeric,
    p_fim_semana          varchar,
    p_complemento_qtd      numeric,
    p_complemento_base     numeric,
    p_complemento_valor    numeric   
   ) RETURNS VOID AS $$
DECLARE
BEGIN
  -- Atualiza a indicação se deve ser paga diária no fim de semana para viagens nacionais
  update pd_missao
     set diaria_fim_semana = p_fim_semana,
         complemento_qtd   = p_complemento_qtd,
         complemento_base  = p_complemento_base,
         complemento_valor = p_complemento_valor
   where sq_siw_solicitacao = p_chave;

  -- Recalcula as diárias da solicitação
  sp_calculadiarias(p_chave, null, 'S'); END; $$ LANGUAGE 'PLPGSQL' VOLATILE;