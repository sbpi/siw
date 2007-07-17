create or replace procedure sp_putPrestacaoContas
   (p_operacao   in  varchar2            ,
    p_cliente    in  varchar2 default null,
    p_chave      in  number   default null,
    p_chave_pai  in  number   default null,
    p_nome       in  varchar2 default null,
    p_descricao  in  varchar2 default null,
    p_tipo       in  varchar2 default null,
    p_ativo      in  varchar2 default null
   ) is
begin
   If p_operacao in ('I','C') Then
      -- Insere registro
      insert into ac_prestacao_contas
        (sq_prestacao_contas,         cliente,   sq_prestacao_pai, nome,   descricao,   tipo,   ativo)
      values
        (sq_prestacao_contas.nextval, p_cliente, p_chave_pai,      p_nome, p_descricao, p_tipo, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update ac_prestacao_contas
         set sq_prestacao_pai = p_chave_pai,
             nome             = p_nome,
             descricao        = p_descricao,
             tipo             = p_tipo
       where sq_prestacao_contas = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete ac_prestacao_contas where sq_prestacao_contas = p_chave;
   Elsif p_operacao = 'T' Then
      -- Ativa registro
      update ac_prestacao_contas set ativo = 'S' where sq_prestacao_contas = p_chave;
   Elsif p_operacao = 'D' Then
      -- Desativa registro
      update ac_prestacao_contas set ativo = 'N' where sq_prestacao_contas = p_chave;
   End If;
end sp_putPrestacaoContas;
/
