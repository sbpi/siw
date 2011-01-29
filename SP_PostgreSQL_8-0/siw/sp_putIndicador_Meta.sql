create or replace FUNCTION sp_putIndicador_meta
   (p_operacao           varchar,
    p_usuario            numeric,
    p_chave              numeric,
    p_chave_aux          numeric,
    p_plano              numeric,
    p_indicador          numeric,
    p_titulo             varchar,
    p_descricao          varchar,
    p_ordem              numeric,
    p_inicio             date,
    p_fim                date,
    p_base               numeric,
    p_pais               numeric,
    p_regiao             numeric,
    p_uf                 varchar,
    p_cidade             numeric,
    p_valor_inicial      varchar,
    p_quantidade         varchar,
    p_cumulativa         varchar,
    p_pessoa             numeric,
    p_unidade            numeric,
    p_situacao_atual     varchar,
    p_exequivel          varchar,
    p_justificativa      varchar,
    p_outras_medidas     varchar 
   ) RETURNS VOID AS $$
DECLARE
   w_chave_aux  numeric(18);
   w_regiao numeric(18);
BEGIN
   -- Garante a gravação da região quando a UF for informada
   If p_pais is not null and p_uf is not null and p_regiao is null Then
     select sq_regiao into w_regiao from co_uf where sq_pais = p_pais and co_uf = p_uf;
   Else
     w_regiao := p_regiao;
   End If;
   
   If p_operacao = 'I'  Then
      -- Gera a nova chave do registro, a partir da sequence
      select nextVal('sq_solic_meta') into w_chave_aux;    
      -- Insere registro
      insert into siw_solic_meta
        (sq_solic_meta,     sq_siw_solicitacao,  sq_eoindicador,    sq_pessoa,    titulo,       descricao,     ordem,          inicio,       fim, 
         base_geografica,   sq_pais,             sq_regiao,         co_uf,        sq_cidade,    quantidade,    cumulativa,     cadastrador,  sq_unidade,
         valor_inicial,     sq_plano,            ultima_alteracao)
      values
        (w_chave_aux,       p_chave,             p_indicador,       p_pessoa,     p_titulo,     p_descricao,   p_ordem,        p_inicio,     p_fim, 
         p_base,            p_pais,              w_regiao,          p_uf,         p_cidade,     p_quantidade,  p_cumulativa,   p_usuario,    p_unidade,
         p_valor_inicial,   p_plano,             now());
   Elsif p_operacao = 'A' Then 
      If p_plano is null Then
         -- Tratamento para metas ligadas a solicitações
         If p_exequivel is null Then
            -- Altera as informações de cadastro da meta
            update siw_solic_meta
               set sq_eoindicador    = p_indicador,
                   sq_pessoa         = p_pessoa,
                   titulo            = p_titulo,     
                   descricao         = p_descricao,
                   ordem             = p_ordem,
                   inicio            = p_inicio,
                   fim               = p_fim,
                   sq_pais           = p_pais,
                   sq_regiao         = w_regiao,
                   co_uf             = p_uf,
                   sq_cidade         = p_cidade,
                   base_geografica   = p_base,
                   valor_inicial     = p_valor_inicial,
                   quantidade        = p_quantidade,
                   cumulativa        = p_cumulativa,
                   cadastrador       = p_usuario,             
                   sq_unidade        = p_unidade,
                   ultima_alteracao  = now()
             where sq_solic_meta = p_chave_aux;
         Else
            -- Altera as informações de monitoramento da meta
            update siw_solic_meta
               set situacao_atual            = p_situacao_atual,
                   exequivel                 = p_exequivel,
                   justificativa_inexequivel = p_justificativa,
                   outras_medidas            = p_outras_medidas,
                   cadastrador               = p_usuario,
                   ultima_alteracao          = now()
             where sq_solic_meta = p_chave_aux;
         End If;
      Else
         -- Tratamento para metas ligadas a planos estratégicos
            update siw_solic_meta
               set sq_eoindicador            = p_indicador,
                   sq_pessoa                 = p_pessoa,
                   titulo                    = p_titulo,     
                   descricao                 = p_descricao,
                   ordem                     = p_ordem,
                   inicio                    = p_inicio,
                   fim                       = p_fim,
                   sq_pais                   = p_pais,
                   sq_regiao                 = w_regiao,
                   co_uf                     = p_uf,
                   sq_cidade                 = p_cidade,
                   base_geografica           = p_base,
                   valor_inicial             = p_valor_inicial,
                   quantidade                = p_quantidade,
                   cumulativa                = p_cumulativa,
                   sq_unidade                = p_unidade,
                   situacao_atual            = p_situacao_atual,
                   exequivel                 = p_exequivel,
                   justificativa_inexequivel = p_justificativa,
                   outras_medidas            = p_outras_medidas,
                   cadastrador               = p_usuario,
                   ultima_alteracao          = now()
             where sq_solic_meta = p_chave_aux;
      End If;
   Elsif p_operacao = 'E' Then
      -- Remove o cronograma da meta
      DELETE FROM siw_meta_cronograma where sq_solic_meta = p_chave_aux;
      
      -- Remove o registro da meta
      DELETE FROM siw_solic_meta      where sq_solic_meta = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;