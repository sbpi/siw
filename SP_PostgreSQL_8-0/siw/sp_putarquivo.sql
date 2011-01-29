create or replace FUNCTION SP_PutArquivo
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_sq_sistema                numeric,
    p_nome                      varchar,
    p_descricao                 varchar,
    p_tipo                      varchar,
    p_diretorio                 varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_arquivo (sq_arquivo, sq_sistema, nome, descricao, tipo, diretorio)
      (select nextVal('sq_arquivo'), p_sq_sistema, p_nome,  p_descricao, p_tipo, p_diretorio);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_arquivo
         set 
             nome       = p_nome,
             descricao  = p_descricao,
             tipo       = p_tipo,
             diretorio  = p_diretorio
       where sq_arquivo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_arquivo
       where sq_arquivo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;