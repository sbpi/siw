create or replace FUNCTION SP_PutCLDadosAnalise
   (p_operacao            varchar,
    p_chave               numeric,
    p_sq_lcmodalidade     numeric,    
    p_numero_processo     varchar,
    p_numero_certame      varchar,
    p_tipo_reajuste       numeric,
    p_indice_base         varchar,
    p_sq_eoindicador      numeric,
    p_limite_variacao     numeric,
    p_sq_lcfonte_recurso  numeric,
    p_sq_espec_despesa    numeric,
    p_sq_lcjulgamento     numeric,
    p_financeiro_unico    varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Atualiza a tabela da licitação com os dados da análise
   Update cl_solicitacao set
      sq_lcmodalidade          = p_sq_lcmodalidade,
      processo                 = p_numero_processo,
      numero_certame           = p_numero_certame,
      tipo_reajuste            = p_tipo_reajuste,
      indice_base              = p_indice_base,
      sq_eoindicador           = p_sq_eoindicador,
      limite_variacao          = p_limite_variacao,
      sq_lcfonte_recurso       = p_sq_lcfonte_recurso,
      sq_especificacao_despesa = p_sq_espec_despesa,
      sq_lcjulgamento          = p_sq_lcjulgamento,
      financeiro_unico         = p_financeiro_unico
   Where sq_siw_solicitacao = p_chave;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;