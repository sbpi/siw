create or replace FUNCTION SP_PutLancamentoRubrica
   (p_operacao              varchar,
    p_chave_aux            numeric,
    p_sq_rubrica_origem    numeric, 
    p_sq_rubrica_destino   numeric,
    p_valor                numeric    
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela rubricas por lancamento
      Insert Into fn_lancamento_rubrica
         (sq_lancamento_rubrica, sq_rubrica_origem, sq_rubrica_destino, 
          sq_lancamento_doc, valor)
      Values 
         (sq_lancamento_rubrica.nextval, p_sq_rubrica_origem, p_sq_rubrica_destino,
          p_chave_aux, p_valor);
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro da tabela por documento
      DELETE FROM fn_lancamento_rubrica where sq_lancamento_doc = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;