create or replace procedure SP_PutTipoDocumento
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  varchar2  default null,
    p_nome                     in  varchar2  default null,
    p_sigla                    in  varchar2  default null,
    p_detalha_item             in  varchar2  default null,
    p_codigo                   in  varchar2  default null,
    p_especie                  in  number    default null,
    p_ativo                    in  varchar2  default null,
    p_chave_nova               out number
   ) is
   
   w_chave fn_tipo_documento.sq_tipo_documento%type;
begin
   If p_operacao = 'I' Then
      -- Recupera a chave
      select sq_tipo_documento.nextval into w_chave from dual;
      
      -- Insere registro
      insert into fn_tipo_documento
        (sq_tipo_documento, cliente,   nome,   sigla,   detalha_item,   codigo_externo, sq_especie_documento, ativo)
      values
        (w_chave,           p_cliente, p_nome, p_sigla, p_detalha_item, p_codigo,       p_especie,            p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update fn_tipo_documento
         set cliente              = p_cliente,
             nome                 = p_nome,
             sigla                = p_sigla,
             detalha_item         = p_detalha_item,
             codigo_externo       = p_codigo,
             sq_especie_documento = p_especie,
             ativo                = p_ativo
       where sq_tipo_documento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete fn_tipo_doc_vinc  where sq_tipo_documento = p_chave;
      delete fn_tipo_documento where sq_tipo_documento = p_chave;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end SP_PutTipoDocumento;
/
