create or replace FUNCTION SP_PutPDUnidLimite
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_limite_passagem           numeric,
    p_limite_diaria             numeric,
    p_ano                       numeric
   ) RETURNS VOID AS $$
DECLARE
BEGIN
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
      DELETE FROM pd_unidade_limite where sq_unidade = p_chave and ano = p_ano;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;