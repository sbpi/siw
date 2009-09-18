create or replace procedure SP_PutTipoLancamento
   (p_operacao   in  varchar2,
    p_chave      in  number   default null,
    p_pai        in  number   default null,
    p_cliente    in  varchar2,
    p_nome       in  varchar2 default null,
    p_descricao  in  varchar2 default null,
    p_receita    in  varchar2 default null,
    p_despesa    in  varchar2 default null,
    p_ativo      in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into fn_tipo_lancamento
        (sq_tipo_lancamento,         cliente,   sq_tipo_lancamento_pai, nome,   descricao,   receita,   despesa,   ativo)
      values
        (sq_tipo_lancamento.nextval, p_cliente, p_pai,                  p_nome, p_descricao, p_receita, p_despesa, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update fn_tipo_lancamento
         set sq_tipo_lancamento_pai = p_pai,
             nome                   = p_nome,
             descricao              = p_descricao,
             receita                = p_receita,
             despesa                = p_despesa,
             ativo                  = p_ativo
       where sq_tipo_lancamento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete fn_tipo_lancamento where sq_tipo_lancamento = p_chave;
   End If;
end SP_PutTipoLancamento;
/
