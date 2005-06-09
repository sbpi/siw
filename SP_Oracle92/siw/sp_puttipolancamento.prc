create or replace procedure SP_PutTipoLancamento
   (p_operacao   in  varchar2            ,
    p_chave      in  number  default null,
    p_cliente    in  varchar2            ,
    p_nome       in  varchar2            ,
    p_descricao  in  varchar2            ,
    p_receita    in  varchar2            ,
    p_despesa    in  varchar2            ,
    p_ativo      in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into fn_tipo_lancamento
        (sq_tipo_lancamento, cliente, nome, descricao, receita, despesa, ativo)
      values
        (sq_tipo_lancamento.nextval, p_cliente, p_nome, p_descricao, p_receita, p_despesa, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update fn_tipo_lancamento
         set cliente       = p_cliente,
             nome          = p_nome,
             descricao     = p_descricao,
             receita       = p_receita,
             despesa       = p_despesa,
             ativo         = p_ativo
       where sq_tipo_lancamento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete fn_tipo_lancamento where sq_tipo_lancamento = p_chave;
   End If;
end SP_PutTipoLancamento;
/

