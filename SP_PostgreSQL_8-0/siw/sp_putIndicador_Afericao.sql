create or replace FUNCTION sp_putIndicador_Afericao
   (p_operacao       varchar,
    p_usuario        numeric,
    p_chave          numeric,
    p_indicador      numeric,
    p_afericao       date,
    p_inicio         date,
    p_fim            date,
    p_pais           numeric,
    p_regiao         numeric,
    p_uf             varchar,
    p_cidade         numeric,
    p_base           numeric,
    p_fonte          varchar,
    p_valor          numeric,
    p_previsao       varchar,
    p_observacao     varchar 
   ) RETURNS VOID AS $$
DECLARE
   w_chave  numeric(18);
   w_regiao numeric(18);
BEGIN
   -- Garante a gravação da região quando a UF for informada
   If p_pais is not null and p_uf is not null and p_regiao is null Then
     select sq_regiao into w_regiao from co_uf where sq_pais = p_pais and co_uf = p_uf;
   Else
     w_regiao := p_regiao;
   End If;
   
   If p_operacao = 'I' or p_operacao = 'C' Then
      -- Gera a nova chave do registro, a partir da sequence
      select nextVal('sq_eoindicador_afericao') into w_chave;

      -- Insere registro
      insert into eo_indicador_afericao
        (sq_eoindicador_afericao, sq_eoindicador,  data_afericao, referencia_inicio, referencia_fim,   sq_pais,          sq_regiao,  co_uf,   sq_cidade, 
         cadastrador,             base_geografica, fonte,         valor,             inclusao,         ultima_alteracao, previsao,   observacao)
      values
        (w_chave,                 p_indicador,     p_afericao,    p_inicio,          p_fim,            p_pais,           w_regiao,   p_uf,    p_cidade,
         p_usuario,               p_base,          p_fonte,       p_valor,           now(),          null,             p_previsao, p_observacao);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_indicador_afericao
         set sq_eoindicador    = p_indicador,
             data_afericao     = p_afericao,
             referencia_inicio = p_inicio,
             referencia_fim    = p_fim,
             sq_pais           = p_pais,
             sq_regiao         = w_regiao,
             co_uf             = p_uf,
             sq_cidade         = p_cidade,
             cadastrador       = p_usuario,
             base_geografica   = p_base,
             fonte             = p_fonte,
             valor             = p_valor,
             ultima_alteracao  = now(),
             previsao          = p_previsao,
             observacao        = p_observacao
       where sq_eoindicador_afericao = p_chave;
   Elsif p_operacao = 'E' Then
      -- Recupera o período do registro
      DELETE FROM eo_indicador_afericao where sq_eoindicador_afericao = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;