create or replace procedure sp_putGpPontoMensal
   (p_operacao                 in  varchar2,
    p_contrato                 in  number,        
    p_mes                      in  varchar2,
    p_horas_trabalhadas        in  varchar2,
    p_horas_extras             in  varchar2,
    p_horas_atrasos            in  varchar2,
    p_horas_banco              in  varchar2,
    p_gestor                   in  number
   ) is
   w_existe number(18);
begin
    -- Grava as informações de folha de ponto
   If p_operacao = 'I' Then
      -- Verifica se o registro já existe
      select count(*) into w_existe
        from gp_folha_ponto_mensal
      where sq_contrato_colaborador = p_contrato 
        and mes                     = p_mes;
         
      If w_existe = 0 Then
         -- Adiciona um novo registro
         Insert into gp_folha_ponto_mensal
                (sq_contrato_colaborador, mes,   horas_trabalhadas,   horas_extras,   horas_atrasos,   horas_banco)
         values (p_contrato,              p_mes, p_horas_trabalhadas, p_horas_extras, p_horas_atrasos, p_horas_banco);                                     
      Else
        update gp_folha_ponto_mensal
           set horas_trabalhadas = p_horas_trabalhadas,
               horas_extras      = p_horas_extras,
               horas_atrasos     = p_horas_atrasos,
               horas_banco       = p_horas_banco
         where sq_contrato_colaborador = p_contrato
           and mes                     = p_mes;
      End If;
   Elsif p_operacao = 'E' Then
      -- Exclui folha de ponto diária
      delete from gp_folha_ponto_diaria
       where sq_contrato_colaborador = p_contrato 
         and to_char(data,'YYYYmm')  = p_mes
         and horas_autorizadas       is null; 

      -- Exclui folha de ponto mensal
      delete from gp_folha_ponto_mensal
       where sq_contrato_colaborador = p_contrato 
         and mes                     = p_mes
         and ciencia_gestor          is null;         
   Elsif p_operacao = 'T' Then
      -- Aprovação da folha de ponto mensal
      update gp_folha_ponto_mensal
         set ciencia_gestor    = p_gestor,
             ciencia_data      = sysdate,
             horas_autorizadas = horas_banco
       where sq_contrato_colaborador = p_contrato
         and mes                     = p_mes;

      -- Aprovação da folha de ponto diária
      update gp_folha_ponto_diaria
         set horas_autorizadas = saldo_diario
       where sq_contrato_colaborador = p_contrato
         and to_char(data,'YYYYmm')  = p_mes
         and data                    < trunc(sysdate);
   Elsif p_operacao = 'D' Then
      -- Aprovação da folha de ponto mensal
      update gp_folha_ponto_mensal
         set ciencia_gestor    = null,
             ciencia_data      = null,
             horas_autorizadas = null
       where sq_contrato_colaborador = p_contrato
         and mes                     = p_mes;

      -- Aprovação da folha de ponto diária
      update gp_folha_ponto_diaria
         set horas_autorizadas = null
       where sq_contrato_colaborador = p_contrato
         and to_char(data,'YYYYmm')  = p_mes
         and data                    < trunc(sysdate);
   End If;            
end sp_putGpPontoMensal;
/
