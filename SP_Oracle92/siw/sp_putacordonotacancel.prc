create or replace procedure SP_PutAcordoNotaCancel
   (p_operacao                 in  varchar2,
    p_chave_aux                in  number   default null,
    p_chave_aux2               in  number   default null,
    p_data                     in  date     default null,
    p_valor                    in  number   default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ac_nota_cancelamento
        (sq_nota_cancelamento, sq_acordo_nota, data, valor)
        (select sq_nota_cancelamento.nextval, p_chave_aux, p_data, p_valor from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update ac_nota_cancelamento
         set data                    = p_data,
             valor                   = p_valor
       where sq_nota_cancelamento = p_chave_aux2;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete ac_nota_cancelamento where sq_nota_cancelamento = p_chave_aux2;
   End If;
end SP_PutAcordoNotaCancel;
/
