create or replace FUNCTION sp_putGpPontoDiario
   (p_operacao                  varchar,
    p_contrato                  numeric,
    p_data                      date,
    p_primeira_entrada          varchar,
    p_primeira_saida            varchar,
    p_segunda_entrada           varchar,    
    p_segunda_saida             varchar,
    p_horas_trabalhadas         varchar,
    p_saldo_diario              varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
    -- Grava as informações de folha de ponto
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gp_folha_ponto_diaria(sq_contrato_colaborador,data,primeira_entrada,
                                        primeira_saida,segunda_entrada, segunda_saida,
                                        horas_trabalhadas,saldo_diario)
      (select 
          p_contrato, p_data, p_primeira_entrada, p_primeira_saida, p_segunda_entrada,
          p_segunda_saida, p_horas_trabalhadas, p_saldo_diario
        
        where 0 = (select count(*) from gp_folha_ponto_diaria where sq_contrato_colaborador = p_contrato and data = p_data)
      );
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;