create or replace procedure SP_PutDocumentoVinc
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_vinculo                  in  integer 
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into fn_tipo_doc_vinc
        ( sq_tipo_documento , sq_menu)
      values
        (p_chave, p_vinculo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update fn_tipo_doc_vinc
         set sq_menu                = p_vinculo
       where sq_tipo_documento      = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete fn_tipo_doc_vinc where sq_tipo_documento  = p_chave;
   End If;
end SP_PutDocumentoVinc;
/
