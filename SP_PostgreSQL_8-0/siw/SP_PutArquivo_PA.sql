create or replace FUNCTION SP_PutArquivo_PA
   (p_operacao                  varchar,
    p_cliente                   numeric,
    p_chave                     numeric,
    p_nome                      varchar,
    p_ativo                     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
         insert into pa_arquivo (sq_localizacao, cliente,   nome,   ativo)
         (select                 p_chave,        p_cliente, p_nome, p_ativo from dual);
      -- Insere Registro na tabela de locais
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_arquivo set
         nome                   = p_nome,
         ativo                  = p_ativo
      where sq_localizacao      = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pa_arquivo where sq_localizacao = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;