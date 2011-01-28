create or replace FUNCTION SP_PutAcordoDadosAdicionais
   (p_operacao            varchar,
    p_chave               numeric,
    p_numero_certame      varchar,
    p_numero_ata          varchar,
    p_tipo_reajuste       numeric,
    p_limite_variacao     numeric,
    p_indice_base         varchar,
    p_sq_eoindicador      numeric,
    p_sq_lcfonte_recurso  numeric,
    p_espec_despesa       numeric,
    p_sq_lcmodalidade     numeric,    
    p_numero_empenho      varchar,
    p_numero_processo     varchar,
    p_assinatura          date,
    p_publicacao          date,
    p_financeiro_unico    varchar,
    p_pagina_diario       numeric,
    p_condicao            varchar,
    p_valor_caucao        numeric   
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Atualiza o registro da demanda com os dados da conclusão.
   Update ac_acordo set
      numero_certame           = p_numero_certame,
      numero_ata               = p_numero_ata,
      tipo_reajuste            = p_tipo_reajuste,
      limite_variacao          = p_limite_variacao,
      indice_base              = p_indice_base,
      sq_eoindicador           = p_sq_eoindicador,
      sq_lcfonte_recurso       = p_sq_lcfonte_recurso,
      sq_especificacao_despesa = p_espec_despesa,
      sq_lcmodalidade          = p_sq_lcmodalidade,
      empenho                  = p_numero_empenho,
      assinatura               = p_assinatura,
      publicacao               = p_publicacao,
      financeiro_unico         = p_financeiro_unico,
      pagina_diario_oficial    = p_pagina_diario,
      condicoes_pagamento      = p_condicao,
      valor_caucao             = p_valor_caucao
   Where sq_siw_solicitacao = p_chave;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;