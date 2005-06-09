create or replace procedure SP_PutCOForm
   (p_operacao  in  varchar2,
    p_chave     in  number default null,
    p_tipo      in  varchar2,
    p_nome      in  varchar2,
    p_ordem     in  number,
    p_ativo     in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_formacao (sq_formacao, tipo, nome, ordem,ativo) 
         (select sq_formacao.nextval,
                 p_tipo, 
                 trim(p_nome),
                 p_ordem,
                 p_ativo
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_formacao set
         tipo      = p_tipo,      
         nome      = trim(p_nome),
         ordem     = p_ordem,
         ativo     = p_ativo
      where sq_formacao = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete co_formacao where sq_formacao = p_chave;
   End If;
end SP_PutCOForm;
/

