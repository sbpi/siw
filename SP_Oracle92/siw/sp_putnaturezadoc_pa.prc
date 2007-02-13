create or replace procedure sp_PutNaturezaDoc_PA
   (p_operacao  in  varchar2             ,
    p_chave     in  number   default null,
    p_cliente   in  number   default null,
    p_nome      in  varchar2 default null,
    p_sigla     in  varchar2 default null,
    p_descricao in  varchar2 default null,
    p_ativo     in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_natureza_documento (sq_natureza_documento, cliente, nome, sigla, descricao, ativo)
      (select sq_natureza_documento.nextval, p_cliente, p_nome, upper(p_sigla), p_descricao, p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_natureza_documento
         set cliente   = p_cliente,
             nome      = p_nome,
             sigla     = upper(p_sigla),
             descricao = p_descricao,
             ativo     = p_ativo
       where sq_natureza_documento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pa_natureza_documento
       where sq_natureza_documento = p_chave;
   End If;
end sp_PutNaturezaDoc_PA;
/
