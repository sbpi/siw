create or replace FUNCTION sp_putSolicRestricao
   (p_operacao               varchar,
    p_chave                  numeric,
    p_chave_aux              numeric,
    p_pessoa                 numeric,
    p_pessoa_atualizacao     numeric,
    p_tipo_restricao         numeric,
    p_risco                  varchar,
    p_problema               varchar,
    p_descricao              varchar,
    p_probabilidade          numeric,
    p_impacto                numeric,
    p_criticidade            numeric,
    p_estrategia             varchar,
    p_acao_resposta          varchar,
    p_fase_atual             varchar,
    p_data_situacao          date,
    p_situacao_atual         varchar 
   ) RETURNS VOID AS $$
DECLARE
   w_chave_aux  numeric(18);

BEGIN
   -- informada
   If p_operacao = 'I'  Then
      -- Gera a nova chave do registro, a partir da sequence
      select sq_siw_restricao.nextval into w_chave_aux;    
      -- Insere registro
      insert into siw_restricao
        (sq_siw_restricao,  sq_siw_solicitacao,       sq_pessoa,      sq_pessoa_atualizacao,      sq_tipo_restricao,   risco,              problema,         descricao,        probabilidade, 
         impacto,           criticidade,              estrategia,     acao_resposta,              fase_atual,          data_situacao,      situacao_atual,   ultima_atualizacao)

      values
        (w_chave_aux,       p_chave,                  p_pessoa,       p_pessoa_atualizacao,       p_tipo_restricao,    p_risco,            p_problema,       p_descricao,       p_probabilidade, 
         p_impacto,         p_criticidade,            p_estrategia,   p_acao_resposta,            p_fase_atual,        p_data_situacao,    p_situacao_atual, now());
   Elsif p_operacao = 'A' Then 
      -- Altera registro
      update siw_restricao
         set sq_pessoa             = p_pessoa, 
             sq_pessoa_atualizacao = p_pessoa_atualizacao,     
             sq_tipo_restricao     = p_tipo_restricao,
             risco                 = p_risco,
             problema              = p_problema,
             descricao             = p_descricao,
             probabilidade         = p_probabilidade,
             impacto               = p_impacto,
             criticidade           = p_criticidade,
             estrategia            = p_estrategia,
             acao_resposta         = p_acao_resposta,
             fase_atual            = p_fase_atual,
             data_situacao         = p_data_situacao,
             situacao_atual        = p_situacao_atual,             
             ultima_atualizacao    = now()
       where sq_siw_restricao  = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Exclui o registro de siw_restricao_etapa
      DELETE FROM siw_restricao_etapa where sq_siw_restricao = p_chave_aux;
      -- Recupera o período do registro
      DELETE FROM siw_restricao where sq_siw_restricao = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;