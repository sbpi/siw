create or replace procedure SP_PutLancamentoPais
   (p_operacao            in varchar2,
    p_solicitacao         in number   default null,
    p_pais                in number   default null,
    p_valor               in number   default null
   ) is
   
begin
   If p_operacao = 'I' Then -- Inclusão
      insert into fn_lancamento_pais (sq_siw_solicitacao, sq_pais, valor) values (p_solicitacao, p_pais, p_valor);
   Elsif p_operacao = 'E' Then -- Exclusão
      delete fn_lancamento_pais where sq_siw_solicitacao = p_solicitacao;
   End If;
end SP_PutLancamentoPais;
/
