create or replace procedure SP_PutLancamentoItem
   (p_operacao            in varchar2,
    p_chave               in number,
    p_chave_aux           in number   default null,
    p_sq_projeto_rubrica  in number   default null,
    p_descricao           in varchar  default null,
    p_quantidade          in varchar2 default null,
    p_valor_unitario      in number   default null,
    p_ordem               in varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then -- Inclusão
      insert into fn_documento_item
        (sq_documento_item,         sq_lancamento_doc,  sq_projeto_rubrica,   descricao,
         quantidade,                valor_unitario,     ordem
        )
      values
        (sq_documento_item.nextval, p_chave,            p_sq_projeto_rubrica, p_descricao,
         p_quantidade,              p_valor_unitario,   p_ordem
        );
   Elsif p_operacao = 'A' Then -- Alteração
      update fn_documento_item
         set sq_projeto_rubrica  = p_sq_projeto_rubrica,
             descricao           = p_descricao,
             quantidade          = p_quantidade,
             valor_unitario      = p_valor_unitario,
             ordem               = p_ordem
       where sq_documento_item = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      delete fn_documento_item where sq_documento_item = p_chave_aux;
   End If;
end SP_PutLancamentoItem;
/
