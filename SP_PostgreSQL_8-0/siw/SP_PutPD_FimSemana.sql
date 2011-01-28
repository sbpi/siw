create or replace FUNCTION SP_PutPD_FimSemana
   (p_chave               numeric,
    p_fim_semana          varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
  -- Atualiza a indicação se deve ser paga diária no fim de semana para viagens nacionais
  update pd_missao
     set diaria_fim_semana = p_fim_semana
   where sq_siw_solicitacao = p_chave;

  -- Recalcula as diárias da solicitação
  sp_calculadiarias(p_chave, null, 'S'); END; $$ LANGUAGE 'PLPGSQL' VOLATILE;