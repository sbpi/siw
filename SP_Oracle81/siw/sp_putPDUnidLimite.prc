create or replace procedure SP_PutPDUnidLimite
   (p_operacao                 in  varchar2,
    p_chave                    in  number,
    p_limite_passagem          in  number,
    p_limite_diaria            in  number,
    p_ano                      in  number
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_unidade_limite (sq_unidade, limite_passagem, limite_diaria, ano) values (p_chave, p_limite_passagem, p_limite_diaria, p_ano);
   Elsif p_operacao = 'A' Then
      update pd_unidade_limite
         set limite_passagem = p_limite_passagem,
             limite_diaria   = p_limite_diaria
       where sq_unidade = p_chave
         and ano        = p_ano;
             
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pd_unidade_limite where sq_unidade = p_chave and ano = p_ano;
   End If;
end SP_PutPDUnidLimite;
/
