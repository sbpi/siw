create or replace procedure SP_PutCOEtnia
   (p_operacao        in  varchar2,
    p_chave           in  number default null,
    p_nome            in  varchar2,
    p_codigo_siape    in  number,
    p_ativo           in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_etnia (sq_etnia, nome, codigo_siape,ativo)
         (select sq_etnia.nextval,
                 trim(p_nome),
                 p_codigo_siape,
                 p_ativo
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_etnia set
         nome            = trim(p_nome),
         codigo_siape    = p_codigo_siape,
         ativo           = p_ativo
      where sq_etnia = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete co_etnia where sq_etnia = p_chave;
   End If;
end SP_PutCOEtnia;
/

