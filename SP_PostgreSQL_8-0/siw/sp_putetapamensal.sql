create or replace FUNCTION SP_PutEtapaMensal
   (p_operacao            varchar,
    p_chave               numeric,
    p_quantitativo        numeric,
    p_referencia          date
   ) RETURNS VOID AS $$
DECLARE
BEGIN   
   if p_operacao = 'E' Then
      -- Apaga todos os registros para que seja feia a atualização
      DELETE FROM pj_etapa_mensal where sq_projeto_etapa = p_chave;
   Else
      -- Insere registro na tabela de meses da etapa
      Insert Into pj_etapa_mensal
         ( sq_projeto_etapa, referencia,   execucao_fisica, execucao_financeira)
         Values
         ( p_chave,          last_day(p_referencia), p_quantitativo,  0);
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;