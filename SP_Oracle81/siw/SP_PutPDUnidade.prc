create or replace procedure SP_PutPDUnidade
   (p_operacao                 in  varchar2,
    p_chave                    in  number,
    p_ativo                    in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_unidade (sq_unidade, ativo) values (p_chave, p_ativo);
   Elsif p_operacao = 'A' Then
      update pd_unidade set ativo = p_ativo where sq_unidade = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pd_unidade where sq_unidade = p_chave;
   End If;
end SP_PutPDUnidade;
/
