create or replace FUNCTION SP_PutConvDadosBancario
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_banco                  numeric,   
    p_sq_agencia                numeric,   
    p_op_conta                  varchar,
    p_nr_conta                  varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' or p_operacao = 'A' Then
      -- Insere registro
      update ac_acordo
         set sq_agencia            = p_sq_agencia,
             operacao_conta        = p_op_conta,
             numero_conta          = p_nr_conta
         where sq_siw_solicitacao  = p_chave;

   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM ac_acordo where sq_siw_solicitacao = p_chave;
   End If;
   END; $$ LANGUAGE 'PLPGSQL' VOLATILE;