create or replace procedure sp_putGpPontoMensal
   (p_operacao                 in  varchar2,
    p_contrato                 in  number,        
    p_mes                      in  varchar2,
    p_horas_trabalhadas        in  varchar2,
    p_horas_extras             in  varchar2,
    p_horas_atrasos            in  varchar2,
    p_horas_banco              in  varchar2    
    --p_horas_autorizadas        in  varchar2,        
    --p_ciencia_gestor           in  number,        
    --p_ciencia_data             in  date                
   ) is
begin
    -- Grava as informações de folha de ponto
   If p_operacao = 'I' Then
    -- Adiciona um novo registro
   Insert into gp_folha_ponto_mensal(sq_contrato_colaborador, mes, horas_trabalhadas, 
                                     horas_extras,horas_atrasos,horas_banco)
   values
         (p_contrato, p_mes, p_horas_trabalhadas, p_horas_extras, 
          p_horas_atrasos, p_horas_banco);                                     
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from gp_folha_ponto_diaria
       where sq_contrato_colaborador = p_contrato 
         and to_char(data,'YYYYmm') = p_mes; 
      delete from gp_folha_ponto_mensal
       where sq_contrato_colaborador = p_contrato 
         and mes = p_mes;         
   End If;            
end sp_putGpPontoMensal;
/
