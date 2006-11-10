create or replace procedure SP_PutLancamentoRubrica
   (p_operacao             in  varchar2,
    p_chave_aux            in number    default null,
    p_sq_rubrica_origem    in number    default null, 
    p_sq_rubrica_destino   in number    default null,
    p_valor                in number    default null
   ) is
begin
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
      delete fn_lancamento_rubrica where sq_lancamento_doc = p_chave_aux;
   End If;
end SP_PutLancamentoRubrica;
/
