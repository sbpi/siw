create or replace FUNCTION SP_PutPD_Fatura
   (p_operacao             varchar,
    p_chave                numeric,
    p_arquivo              numeric,
    p_agencia              numeric,
    p_tipo                 numeric,
    p_numero               varchar,
    p_inicio               date,
    p_fim                  date,
    p_emissao              date,
    p_vencimento           date,
    p_valor                numeric,
    p_registros            numeric,
    p_importados           numeric,
    p_rejeitados           numeric,
    p_chave_nova          numeric
   ) RETURNS VOID AS $$
DECLARE
   w_chave  numeric(18);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select nextVal('sq_fatura_agencia') into w_chave;
      
      -- Insere registro na tabela de bilhetes
      insert into pd_fatura_agencia
        (sq_fatura_agencia, sq_arquivo_eletronico, agencia_viagem, numero,   inicio_decendio, fim_decendio, emissao,   vencimento,   valor,
         registros,         importados,            rejeitados,     tipo)
      values
        (w_chave,           p_arquivo,             p_agencia,      p_numero, p_inicio,        p_fim,        p_emissao, p_vencimento, p_valor, 
         p_registros,       p_importados,          p_rejeitados,   p_tipo);
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;