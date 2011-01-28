create or replace FUNCTION sp_putPrestacaoContas
   (p_operacao    varchar,
    p_cliente     varchar,
    p_chave       numeric,
    p_chave_pai   numeric,
    p_nome        varchar,
    p_descricao   varchar,
    p_tipo        varchar,
    p_ativo       varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
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
      DELETE FROM ac_prestacao_contas where sq_prestacao_contas = p_chave;
   Elsif p_operacao = 'T' Then
      -- Ativa registro
      update ac_prestacao_contas set ativo = 'S' where sq_prestacao_contas = p_chave;
   Elsif p_operacao = 'D' Then
      -- Desativa registro
      update ac_prestacao_contas set ativo = 'N' where sq_prestacao_contas = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;