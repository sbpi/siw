create or replace procedure SP_PutCOIdioma
   (p_operacao  in  varchar2,
    p_chave     in  number default null,
    p_nome      in  varchar2,
    p_padrao    in  varchar2,
    p_ativo     in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_idioma (sq_idioma, nome, padrao,ativo) 
         (select sq_idioma.nextval, 
                 trim(p_nome),
                 p_padrao,
                 p_ativo
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_idioma set 
         nome      = trim(p_nome),
         padrao    = p_padrao,
         ativo     = p_ativo
      where sq_idioma = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete co_idioma where sq_idioma = p_chave;
   End If;
end SP_PutCOIdioma;
/

