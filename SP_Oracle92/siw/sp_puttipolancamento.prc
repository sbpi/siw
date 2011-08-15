create or replace procedure SP_PutTipoLancamento
   (p_operacao       in  varchar2,
    p_chave          in  number   default null,
    p_pai            in  number   default null,
    p_cliente        in  varchar2,
    p_nome           in  varchar2 default null,
    p_descricao      in  varchar2 default null,
    p_receita        in  varchar2 default null,
    p_despesa        in  varchar2 default null,
    p_reembolso      in  varchar2 default null,
    p_codigo_externo in  varchar2 default null,
    p_ativo          in  varchar2 default null,
    p_chave_nova     out number
   ) is

   w_chave fn_tipo_documento.sq_tipo_documento%type;
begin
   If p_operacao = 'I' Then
      -- Recupera a chave
      select sq_tipo_lancamento.nextval into w_chave from dual;
      
      -- Insere registro
      insert into fn_tipo_lancamento
        (sq_tipo_lancamento, cliente,   sq_tipo_lancamento_pai, nome,   descricao,   receita,   despesa,   reembolso,   codigo_externo,   ativo)
      values
        (w_chave,            p_cliente, p_pai,                  p_nome, p_descricao, p_receita, p_despesa, p_reembolso, p_codigo_externo, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update fn_tipo_lancamento
         set sq_tipo_lancamento_pai = p_pai,
             nome                   = p_nome,
             descricao              = p_descricao,
             codigo_externo         = p_codigo_externo,
             receita                = p_receita,
             despesa                = p_despesa,
             reembolso              = p_reembolso,
             ativo                  = p_ativo
       where sq_tipo_lancamento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete fn_tipo_lanc_vinc  where sq_tipo_lancamento = p_chave;
      delete fn_tipo_lancamento where sq_tipo_lancamento = p_chave;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end SP_PutTipoLancamento;
/
