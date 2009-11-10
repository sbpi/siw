create or replace procedure sp_putGpPontoDiario
   (p_operacao                 in  varchar2,
    p_contrato                 in  number,
    p_data                     in  date,
    p_primeira_entrada         in  varchar2,
    p_primeira_saida           in  varchar2 default null,
    p_segunda_entrada          in  varchar2 default null,    
    p_segunda_saida            in  varchar2 default null,
    p_horas_trabalhadas        in  varchar2 default null,
    p_saldo_diario             in  varchar2 default null
   ) is
begin
    -- Grava as informações de folha de ponto
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gp_folha_ponto_diaria(sq_contrato_colaborador,data,primeira_entrada,
                                        primeira_saida,segunda_entrada, segunda_saida,
                                        horas_trabalhadas,saldo_diario)
      values
         (p_contrato, p_data, p_primeira_entrada, p_primeira_saida, p_segunda_entrada,
          p_segunda_saida, p_horas_trabalhadas, p_saldo_diario);
   End If;
end sp_putGpPontoDiario;
/
