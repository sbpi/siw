create or replace FUNCTION sp_putTipoArquivo
   (p_operacao    varchar,
    p_cliente     numeric,
    p_chave       numeric,
    p_nome        varchar,
    p_sigla       varchar,
    p_descricao   varchar,
    p_ativo       varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_tipo_arquivo
        (sq_tipo_arquivo,         cliente,   nome,   sigla,   descricao,   ativo)
      values
        (nextVal('sq_tipo_arquivo'), p_cliente, p_nome, upper(p_sigla), p_descricao, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_tipo_arquivo
         set nome          = p_nome,
             sigla         = upper(p_sigla),
             descricao     = p_descricao,
             ativo         = p_ativo
       where sq_tipo_arquivo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM siw_tipo_arquivo where sq_tipo_arquivo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;