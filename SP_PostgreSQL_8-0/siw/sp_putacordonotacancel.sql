create or replace FUNCTION SP_PutAcordoNotaCancel
   (p_operacao                  varchar,
    p_chave_aux                 numeric,
    p_chave_aux2                numeric,
    p_data                      date,
    p_valor                     numeric   
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ac_nota_cancelamento
        (sq_nota_cancelamento, sq_acordo_nota, data, valor)
        (select nextVal('sq_nota_cancelamento'), p_chave_aux, p_data, p_valor);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update ac_nota_cancelamento
         set data                    = p_data,
             valor                   = p_valor
       where sq_nota_cancelamento = p_chave_aux2;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM ac_nota_cancelamento where sq_nota_cancelamento = p_chave_aux2;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;