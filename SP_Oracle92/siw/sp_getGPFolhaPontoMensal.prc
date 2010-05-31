create or replace procedure sp_getGPFolhaPontoMensal
   (p_contrato       in number,
    p_mes            in varchar2 default null,
    p_restricao      in varchar2 default null,
    p_result         out sys_refcursor) is
begin
  If p_restricao is null Then  
    -- Recupera as folhas de ponto baseado no contrato e mês
    open p_result for 
    select a.sq_pessoa, 
           d.sq_contrato_colaborador, d.mes, d.horas_trabalhadas, d.horas_extras, d.horas_atrasos, d.horas_banco, 
           d.horas_autorizadas, d.ciencia_gestor, d.ciencia_data,
           to_char(d.ciencia_data,'dd/mm/yyyy, hh24:mi:ss') as php_ciencia_data,
           e.nome as nm_gestor, e.nome_resumido as nm_resumido_gestor
      from gp_contrato_colaborador            a
           inner   join gp_folha_ponto_mensal d on (a.sq_contrato_colaborador = d.sq_contrato_colaborador)
             left  join co_pessoa             e on (d.ciencia_gestor          = e.sq_pessoa)
     where d.sq_contrato_colaborador = p_contrato
       and (p_mes   is null or (p_mes is not null and d.mes = p_mes));
  Elsif p_restricao = 'APROVACAO' Then
    -- Recupera as folhas de ponto baseado no contrato e mês, de pessoas geridas por um usuário
    open p_result for 
    select b.inicio as ini_resp, b.fim as fim_resp,
           d.sq_unidade, d.nome as nm_unidade, d.sigla as sg_unidade,
           e.sq_contrato_colaborador, e.mes, e.horas_trabalhadas, e.horas_extras, e.horas_atrasos, e.horas_banco,
           e.ciencia_gestor, e.ciencia_data,
           f.sq_pessoa, f.nome as nm_pessoa, f.nome_resumido as nm_resumido
      from sg_autenticacao                          a
           inner       join eo_unidade_resp         b on (a.sq_pessoa               = b.sq_pessoa and 
                                                          ((b.fim                   is null and p_mes > to_char(b.inicio,'yyyymm')) or
                                                           (b.fim                   is not null and p_mes between to_char(b.inicio,'yyyymm') and to_char(b.fim,'yyyymm'))
                                                          )
                                                         )
             inner     join gp_contrato_colaborador c on (b.sq_unidade              = c.sq_unidade_exercicio and
                                                          (c.inicio                 between b.inicio and coalesce(b.fim,sysdate) or
                                                           coalesce(c.fim,b.inicio) between b.inicio and coalesce(b.fim,sysdate)
                                                          )
                                                         )
               inner   join eo_unidade              d on (c.sq_unidade_exercicio    = d.sq_unidade)
               inner   join gp_folha_ponto_mensal   e on (c.sq_contrato_colaborador = e.sq_contrato_colaborador and
                                                          e.mes                     = p_mes)
               inner   join co_pessoa               f on (c.sq_pessoa               = f.sq_pessoa)
     where a.sq_pessoa = p_contrato;
  End If;
end sp_getGPFolhaPontoMensal;
/
