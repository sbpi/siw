create or replace procedure SP_PutLancamentoValor
   (p_operacao            in varchar2,
    p_chave               in number,
    p_chave_aux           in number   default null,
    p_valor               in number   default null
   ) is
   
   w_sigla     siw_menu.sigla%type;
   w_chave_aux fn_documento_item.sq_documento_item%type := p_chave_aux;
   w_reg       number(18);
begin
   If p_operacao = 'I' Then -- Inclusão
      insert into fn_documento_valores (sq_lancamento_doc, sq_valores, valor) values (p_chave, p_chave_aux, p_valor);
   Elsif p_operacao = 'E' Then -- Exclusão
      delete fn_documento_valores where sq_lancamento_doc = p_chave;
   End If;
end SP_PutLancamentoValor;
/
