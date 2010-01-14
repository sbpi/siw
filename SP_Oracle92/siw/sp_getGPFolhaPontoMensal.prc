create or replace procedure sp_getGPFolhaPontoMensal
   (p_contrato       in number,
    p_mes            in varchar2 default null,
    p_restricao      in varchar2 default null,
    p_result         out sys_refcursor) is
begin  
  -- Recupera as folhas de ponto baseado no contrato e mês
  open p_result for 
  select d.sq_contrato_colaborador,
         d.mes,
         d.horas_trabalhadas,
         d.horas_extras,
         d.horas_atrasos,
         d.horas_banco
  from gp_folha_ponto_mensal d
 where d.sq_contrato_colaborador = p_contrato
   and (p_mes   is null or (p_mes is not null and d.mes = p_mes));
  
end sp_getGPFolhaPontoMensal;
/
