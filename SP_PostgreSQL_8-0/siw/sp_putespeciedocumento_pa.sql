create or replace FUNCTION sp_PutEspecieDocumento_PA
   (p_operacao   varchar,
    p_chave      numeric,
    p_cliente    numeric,
    p_nome       varchar,
    p_sigla      varchar,
    p_assunto    numeric,
    p_ativo      varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_especie_documento (sq_especie_documento, cliente, nome, sigla, sq_assunto, ativo)
      (select sq_especie_documento.nextval, p_cliente, p_nome, p_sigla, p_assunto, p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_especie_documento
         set 
             cliente     = p_cliente,
             nome        = p_nome,
             sigla       = p_sigla,
             sq_assunto  = p_assunto,
             ativo       = p_ativo
       where sq_especie_documento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pa_especie_documento
       where sq_especie_documento = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;