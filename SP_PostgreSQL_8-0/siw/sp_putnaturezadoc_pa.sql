create or replace FUNCTION sp_PutNaturezaDoc_PA
   (p_operacao   varchar,
    p_chave      numeric,
    p_cliente    numeric,
    p_nome       varchar,
    p_sigla      varchar,
    p_descricao  varchar,
    p_ativo      varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_natureza_documento (sq_natureza_documento, cliente, nome, sigla, descricao, ativo)
      (select nextVal('sq_natureza_documento'), p_cliente, p_nome, upper(p_sigla), p_descricao, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_natureza_documento
         set cliente   = p_cliente,
             nome      = p_nome,
             sigla     = upper(p_sigla),
             descricao = p_descricao,
             ativo     = p_ativo
       where sq_natureza_documento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pa_natureza_documento
       where sq_natureza_documento = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;