create or replace function SP_PutCTCC
   (p_operacao  varchar,
    p_chave     numeric,
    p_sq_cc_pai numeric,
    p_cliente   numeric,
    p_nome      varchar,
    p_descricao varchar,
    p_sigla     varchar,
    p_receita   varchar,
    p_regular   varchar,
    p_ativo     varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ct_cc (sq_cc, sq_cc_pai, cliente, nome, descricao, sigla, receita, regular, ativo)
         (select nextval('sq_cc'),
                 p_sq_cc_pai,
                 p_cliente,
                 trim(p_nome),
                 trim(p_descricao),
                 trim(p_sigla),
                 p_ativo,
                 p_receita,
                 p_regular
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update ct_cc set
         sq_cc_pai = p_sq_cc_pai,
         nome      = trim(p_nome),
         descricao = trim(p_descricao),
         sigla     = trim(p_sigla),
         receita   = p_receita,
         regular   = p_regular
      where sq_cc = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from ct_cc where sq_cc = p_chave;
   Elsif p_operacao = 'T' Then
      -- Ativa registro
      update ct_cc set ativo = 'S' where sq_cc = p_chave;
   Elsif p_operacao = 'D' Then
      -- Desativa registro
      update ct_cc set ativo = 'N' where sq_cc = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;