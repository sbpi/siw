create or replace FUNCTION SP_PutOpiniao
   (p_operacao      varchar,
    p_chave         numeric,
    p_cliente       numeric,
    p_nome          varchar,
    p_sigla         varchar,    
    p_ordem         numeric
   ) RETURNS VOID AS $$
DECLARE
   
BEGIN
   -- Grava uma modalidade de contratação
   If p_operacao = 'I' Then
      insert into siw_opiniao
        (sq_siw_opiniao,        cliente,    nome,         sigla,          ordem)
      values
        (nextVal('sq_siw_opiniao'), p_cliente, trim(p_nome), trim(p_sigla),  p_ordem);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_opiniao
         set cliente           = p_cliente,
             nome              = trim(p_nome),
             sigla             = trim(p_sigla),
             ordem             = p_ordem
       where sq_siw_opiniao = p_chave;
   Elsif p_operacao = 'E' Then
      DELETE FROM siw_opiniao
       where sq_siw_opiniao = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;