create or replace procedure SP_PutAcordoNota
   (p_operacao                 in  varchar2,
    p_chave                    in  number   default null,
    p_chave_aux                in  number   default null,
    p_sq_tipo_documento        in  number   default null,
    p_sq_acordo_outra_parte    in  number   default null,
    p_sq_acordo_aditivo        in  number   default null,
    p_numero                   in  varchar2 default null,
    p_data                     in  date     default null,
    p_valor                    in  number   default null,
    p_sq_lcfonte_recurso       in  number   default null,
    p_espec_despesa            in  number   default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ac_acordo_nota
        (sq_acordo_nota, sq_siw_solicitacao, sq_tipo_documento, sq_acordo_outra_parte, 
         sq_acordo_aditivo, numero, data, valor, sq_lcfonte_recurso, sq_especificacao_despesa
        )
        (select sq_acordo_nota.nextval, p_chave_aux, p_sq_tipo_documento, p_sq_acordo_outra_parte, 
          p_sq_acordo_aditivo, p_numero, p_data, p_valor, p_sq_lcfonte_recurso, p_espec_despesa from dual
        );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update ac_acordo_nota
         set sq_tipo_documento      = p_sq_tipo_documento,
             sq_acordo_outra_parte   = p_sq_acordo_outra_parte,
             sq_acordo_aditivo       = p_sq_acordo_aditivo,
             numero                  = p_numero,
             data                    = p_data,
             valor                   = p_valor,
             sq_lcfonte_recurso      = p_sq_lcfonte_recurso,
             sq_especificacao_despesa = p_espec_despesa
       where sq_acordo_nota = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete ac_acordo_nota where sq_acordo_nota = p_chave;
   End If;
end SP_PutAcordoNota;
/
