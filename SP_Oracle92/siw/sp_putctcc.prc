create or replace procedure SP_PutCTCC
   (p_operacao  in  varchar2,
    p_chave     in  number default null,
    p_sq_cc_pai in  number default null,
    p_cliente   in  number,
    p_nome      in  varchar2,
    p_descricao in  varchar2,
    p_sigla     in  varchar2,
    p_receita   in  varchar2,
    p_regular   in  varchar2,
    p_ativo     in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ct_cc (sq_cc, sq_cc_pai, cliente, nome, descricao, sigla, receita, regular, ativo)
         (select sq_cc.nextval,
                 p_sq_cc_pai,
                 p_cliente,
                 trim(p_nome),
                 trim(p_descricao),
                 trim(p_sigla),
                 p_ativo,
                 p_receita,
                 p_regular
            from dual
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
      delete ct_cc where sq_cc = p_chave;
   Elsif p_operacao = 'T' Then
      -- Ativa registro
      update ct_cc set ativo = 'S' where sq_cc = p_chave;
   Elsif p_operacao = 'D' Then
      -- Desativa registro
      update ct_cc set ativo = 'N' where sq_cc = p_chave;
   End If;
end SP_PutCTCC;
/

