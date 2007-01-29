create or replace procedure SP_PutConvDadosBancario
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_sq_banco                 in  number,   
    p_sq_agencia               in  number,   
    p_op_conta                 in  varchar2,
    p_nr_conta                 in  varchar2
   ) is
begin
   If p_operacao = 'I' or p_operacao = 'A' Then
      -- Insere registro
      update ac_acordo
         set sq_agencia            = p_sq_agencia,
             operacao_conta        = p_op_conta,
             numero_conta          = p_nr_conta
         where sq_siw_solicitacao  = p_chave;

   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete ac_acordo where sq_siw_solicitacao = p_chave;
   End If;
   
end SP_PutConvDadosBancario;
/
