create or replace procedure sp_getGPFolhaPontoDiario
   (p_contrato       in number,
    p_mes            in varchar2,
    p_restricao      in varchar2 default null,
    p_result         out sys_refcursor) is
begin  
  -- Recupera as folhas de ponto baseado no contrato e mês
  open p_result for 
    select d.sq_contrato_colaborador, d.data, d.primeira_entrada, d.primeira_saida, d.segunda_entrada, d.segunda_saida,
           d.horas_trabalhadas, d.saldo_diario, d.horas_autorizadas
      from gp_folha_ponto_diaria d
    where d.sq_contrato_colaborador = p_contrato
      and to_char(d.data, 'YYYYMM') = p_mes;
  
end sp_getGPFolhaPontoDiario;
/
