create or replace FUNCTION SP_PutAtualizaEtapa
   (p_chave               numeric,
    p_chave_aux           numeric,
    p_usuario             numeric,
    p_perc_conclusao      numeric,
    p_inicio_real         date,
    p_fim_real            date,
    p_situacao_atual      varchar,
    p_exequivel           varchar,
    p_justificativa_inex  varchar,
    p_outras_medidas      varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Atualiza a tabela de etapas do projeto
   Update pj_projeto_etapa set
       perc_conclusao            = nvl(p_perc_conclusao,perc_conclusao),
       inicio_real               = p_inicio_real,
       fim_real                  = p_fim_real,
       situacao_atual            = p_situacao_atual,
       sq_pessoa_atualizacao     = p_usuario,
       exequivel                 = p_exequivel,
       justificativa_inexequivel = p_justificativa_inex,
       outras_medidas            = p_outras_medidas,
       ultima_atualizacao        = now()
   where sq_siw_solicitacao = p_chave
     and sq_projeto_etapa   = p_chave_aux;

   -- Recalcula os percentuais de execução dos pais da etapa
   sp_calculaPercEtapa(p_chave_aux);

   -- Atualiza os pesos das etapas
   sp_ajustaPesoEtapa(p_chave, null);

   -- Atualiza as datas de início e término das etapas superiores
   sp_ajustaDataEtapa(p_chave);
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;