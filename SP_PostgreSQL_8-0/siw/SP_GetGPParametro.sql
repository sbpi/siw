create or replace FUNCTION SP_GetGPParametro
   (p_cliente   numeric,
    p_chave_aux numeric,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os parametros do modulo de recursos humanos de um cliente
      open p_result for 
         select a.sq_unidade_gestao, a.admissao_texto, a.admissao_destino, a.rescisao_texto,
                a.rescisao_destino, a.feriado_legenda, a.feriado_nome, a.ferias_legenda, a.ferias_nome,
                a.viagem_legenda, a.viagem_nome,a.dias_atualizacao_cv, a.aviso_atualizacao_cv, 
                a.tipo_tolerancia, a.minutos_tolerancia, a.vinculacao_contrato, a.limite_diario_extras, 
                horario2minutos('00:00',a.limite_diario_extras) as limite_diario,
                case a.tipo_tolerancia when 1 then 'diários'
                                       when 2 then 'por período'
                                       when 4 then 'por horário'
                end as nm_tipo_tolerancia,
                a.dias_perda_ferias,
                b.sigla as sg_vinculacao_contrato
           from gp_parametro       a
                left join siw_menu b on (a.vinculacao_contrato = b.sq_menu)
          where a.cliente = p_cliente;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;