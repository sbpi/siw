create or replace procedure SP_PutTipoDocumento
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  varchar2,
    p_nome                     in  varchar2,
    p_sigla                    in  varchar2,
    p_detalha_item             in  varchar2,
    p_ativo                    in  varchar2
   ) is
begin
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
      delete fn_tipo_documento where sq_tipo_documento = p_chave;
   End If;
end SP_PutTipoDocumento;
/
