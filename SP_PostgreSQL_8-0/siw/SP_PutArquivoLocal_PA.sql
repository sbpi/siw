create or replace FUNCTION SP_PutArquivoLocal_PA
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_nome                      varchar,
    p_local                     numeric,
    p_local_pai                 numeric,
    p_ativo                     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
         insert into pa_arquivo_local(sq_arquivo_local,         sq_localizacao, sq_local_pai, nome,   ativo)
         (select                      nextVal('sq_arquivo_local'), p_chave,        p_local_pai,  p_nome, p_ativo);
        -- Insere Registro na tabela de locais
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_arquivo_local
         set sq_local_pai = p_local_pai,
             nome         = p_nome,
             ativo        = p_ativo
       where sq_arquivo_local = p_local;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pa_arquivo_local
       where sq_arquivo_local = p_local;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;