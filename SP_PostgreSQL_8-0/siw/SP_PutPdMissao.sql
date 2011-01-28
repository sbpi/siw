create or replace FUNCTION SP_PutPdMissao
   (p_operacao              varchar,
    p_chave                 numeric,
    p_valor_alimentacao     numeric,
    p_valor_transporte      numeric, 
    p_valor_adcional        numeric,
    p_desconto_alimentacao  numeric,
    p_desconto_transporte   numeric,
    p_pta                   varchar,
    p_emissao_bilhete       date,
    p_valor_passagem        numeric,    
    p_restricao             varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Atualiza os valores financeiros em PD_MISSAO
      Update pd_missao
         set valor_alimentacao    = p_valor_alimentacao,
             valor_transporte     = p_valor_transporte,
             valor_adicional      = p_valor_adcional,
             desconto_alimentacao = p_desconto_alimentacao,
             desconto_transporte  = p_desconto_transporte
       where sq_siw_solicitacao = p_chave;
   Elsif p_restricao = 'INFPASS' Then
      -- Atualiza os dados das passagens
      Update pd_missao
         set pta                  = p_pta,
             emissao_bilhete      = p_emissao_bilhete,
             valor_passagem       = p_valor_passagem
       where sq_siw_solicitacao = p_chave;          
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;