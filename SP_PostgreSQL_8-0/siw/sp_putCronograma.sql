create or replace FUNCTION SP_PutCronograma
   (p_operacao             varchar,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_inicio              date,
    p_fim                 date,
    p_valor_previsto      numeric,
    p_valor_real          numeric    
   ) RETURNS VOID AS $$
DECLARE
   w_chave    numeric(18);
   w_pai      numeric(18);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select nextVal('sq_rubrica_cronograma') into w_chave;
      
      -- Insere registro na tabela de etapas do projeto
      Insert Into pj_rubrica_cronograma 
         ( sq_rubrica_cronograma,   sq_projeto_rubrica,  inicio,        fim, 
           valor_previsto,          valor_real)
      Values
         ( w_chave,                 p_chave,              p_inicio,    p_fim,
           p_valor_previsto,        p_valor_real);
   Elsif p_operacao = 'A' Then 
      -- Alteração do cronograma quando o projeto está na fase de cadastramento
      Update pj_rubrica_cronograma set
          inicio                    = p_inicio,
          fim                       = p_fim,
          valor_previsto            = p_valor_previsto,
          valor_real                = p_valor_real
      where sq_rubrica_cronograma   = p_chave_aux;

   Elsif p_operacao = 'V' Then 
      -- Alteração do cronograma quando o projeto está na fase de execução
      Update pj_rubrica_cronograma set valor_real = p_valor_real where sq_rubrica_cronograma = p_chave_aux;

   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de cronograma da rubrica
      DELETE FROM pj_rubrica_cronograma where sq_rubrica_cronograma = p_chave_aux;

   End If;
   END; $$ LANGUAGE 'PLPGSQL' VOLATILE;