create or replace procedure SP_PutIsUnidade_IS
   (p_operacao                 in  varchar2,
    p_chave                    in  number,
    p_administrativa           in  varchar2,
    p_planejamento             in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into is_unidade
             (sq_unidade, administrativa,   planejamento)
      (select    p_chave, p_administrativa, p_planejamento
         from dual
      );
   Elsif p_operacao = 'A' Then
      update is_unidade
         set administrativa = p_administrativa,
             planejamento   = p_planejamento
       where sq_unidade     = p_chave;
             
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete is_unidade where sq_unidade = p_chave;
   End If;
end SP_PutIsUnidade_IS;
/

