create or replace FUNCTION SP_PutGPParametro
   (p_cliente                   numeric,
    p_sq_unidade_gestao         numeric,
    p_admissao_texto            varchar,
    p_admissao_destino          varchar,
    p_rescisao_texto            varchar,
    p_rescisao_destino          varchar,
    p_feriado_legenda           varchar,
    p_feriado_nome              varchar,
    p_ferias_legenda            varchar,
    p_ferias_nome               varchar,
    p_viagem_legenda            varchar,
    p_viagem_nome               varchar,
    p_dias_atualizacao_cv       varchar,
    p_aviso_atualizacao_cv      varchar,
    p_tipo_tolerancia           numeric,
    p_minutos_tolerancia        numeric,
    p_vincula_contrato          numeric,
    p_limite_diario_extras      varchar,
    p_dias_perda_ferias         numeric
   ) RETURNS VOID AS $$
DECLARE
   
   p_operacao varchar(1);
   w_existe   numeric(18);
   
BEGIN
   -- Verifica se operação de inclusão ou alteração
   select count(*) into w_existe from gp_parametro a where a.cliente = p_cliente;
   If w_existe > 0 Then
      p_operacao := 'A';
   Else
      p_operacao := 'I';
   End If;
   
   -- Grava os parametros do módulo de recursos humanos do cliente
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gp_parametro
         (cliente,               sq_unidade_gestao,      admissao_texto,    admissao_destino,     rescisao_texto,     rescisao_destino, 
          feriado_legenda,       feriado_nome,           ferias_legenda,    ferias_nome,          viagem_legenda,     viagem_nome, 
          dias_atualizacao_cv,   aviso_atualizacao_cv,   tipo_tolerancia,   minutos_tolerancia,   vinculacao_contrato, limite_diario_extras,
          dias_perda_ferias
          )
      values
         (p_cliente,             p_sq_unidade_gestao,    p_admissao_texto,  p_admissao_destino,   p_rescisao_texto,   p_rescisao_destino,
          p_feriado_legenda,     p_feriado_nome,         p_ferias_legenda,  p_ferias_nome,        p_viagem_legenda,   p_viagem_nome,
          p_dias_atualizacao_cv, p_aviso_atualizacao_cv, p_tipo_tolerancia, p_minutos_tolerancia, p_vincula_contrato, p_limite_diario_extras,
          p_dias_perda_ferias);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update gp_parametro
         set sq_unidade_gestao    = p_sq_unidade_gestao,
             admissao_texto       = p_admissao_texto,
             admissao_destino     = p_admissao_destino,
             rescisao_texto       = p_rescisao_texto,
             rescisao_destino     = p_rescisao_destino,
             feriado_legenda      = p_feriado_legenda,
             feriado_nome         = p_feriado_nome,
             ferias_legenda       = p_ferias_legenda,
             ferias_nome          = p_ferias_nome,
             viagem_legenda       = p_viagem_legenda,
             viagem_nome          = p_viagem_nome,
             dias_atualizacao_cv  = p_dias_atualizacao_cv,
             aviso_atualizacao_cv = p_aviso_atualizacao_cv,
             tipo_tolerancia      = p_tipo_tolerancia,
             minutos_tolerancia   = p_minutos_tolerancia,
             vinculacao_contrato  = p_vincula_contrato,
             limite_diario_extras = p_limite_diario_extras,
             dias_perda_ferias    = p_dias_perda_ferias
       where cliente = p_cliente;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;