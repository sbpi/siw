create or replace procedure SP_PutIsUnidadeLimite_IS
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_ano                      in  number default null,
    p_limite                   in  number default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into is_unidade_limite (sq_unidade, ano, limite_orcamento)
      values (p_chave, p_ano, p_limite);
   Elsif p_operacao = 'A' Then
      update is_unidade_limite
         set limite_orcamento = p_limite
       where sq_unidade       = p_chave
         and ano              = p_ano;
             
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete is_unidade_limite where sq_unidade = p_chave and ano = p_ano;
   End If;
end SP_PutIsUnidadeLimite_IS;
/
