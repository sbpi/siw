create or replace procedure SP_PutPDUnidade
   (p_operacao                 in  varchar2,
    p_chave                    in  number,
    p_limite_passagem          in  number,
    p_limite_diaria            in  number,
    p_ativo                    in  varchar2,
    p_ano                      in  number
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_unidade
             (sq_unidade, limite_passagem,   limite_diaria,   ativo,   ano)
      (select p_chave,    p_limite_passagem, p_limite_diaria, p_ativo, p_ano
         from dual
      );
   Elsif p_operacao = 'A' Then
      update pd_unidade
         set limite_passagem = p_limite_passagem,
             limite_diaria   = p_limite_diaria,
             ativo           = p_ativo
       where sq_unidade     = p_chave;
             
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pd_unidade where sq_unidade = p_chave and ano = p_ano;
   End If;
end SP_PutPDUnidade;
/
