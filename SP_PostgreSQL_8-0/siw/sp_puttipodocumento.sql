create or replace FUNCTION SP_PutTipoDocumento
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   varchar,
    p_nome                      varchar,
    p_sigla                     varchar,
    p_detalha_item              varchar,
    p_ativo                     varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into fn_tipo_documento
        (sq_tipo_documento,         cliente,   nome,   sigla,   detalha_item,   ativo)
      values
        (sq_tipo_documento.nextval, p_cliente, p_nome, p_sigla, p_detalha_item, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update fn_tipo_documento
         set cliente       = p_cliente,
             nome          = p_nome,
             sigla         = p_sigla,
             detalha_item  = p_detalha_item,
             ativo = p_ativo
       where sq_tipo_documento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM fn_tipo_documento where sq_tipo_documento = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;