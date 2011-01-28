create or replace FUNCTION SP_PutCoPesConBan
   (p_operacao           varchar,
    p_chave              numeric,
    p_pessoa             numeric,
    p_agencia            numeric,
    p_oper               varchar,
    p_numero             varchar,
    p_tipo_conta         numeric,
    p_devolucao          varchar,
    p_saldo              numeric,
    p_ativo              varchar,
    p_padrao             varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_pessoa_conta
         (sq_pessoa_conta,                  operacao,              sq_pessoa,      sq_agencia,
          numero,                           ativo,                 padrao,         tipo_conta,
          devolucao_valor,                  saldo_inicial
         )
      (select
          sq_pessoa_conta_bancaria.nextval, p_oper,                p_pessoa,       p_agencia,
          p_numero,                         p_ativo,               p_padrao,       p_tipo_conta,
          p_devolucao,                      p_saldo
        from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_pessoa_conta set
         operacao             = p_oper,
         tipo_conta           = p_tipo_conta,
         devolucao_valor      = p_devolucao,
         ativo                = p_ativo,
         padrao               = p_padrao,
         saldo_inicial        = p_saldo
      where sq_pessoa_conta   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM co_pessoa_conta where sq_pessoa_conta = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;