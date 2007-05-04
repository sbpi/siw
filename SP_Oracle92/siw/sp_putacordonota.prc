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
    p_espec_despesa            in  number   default null,
    p_observacao               in  varchar2 default null,
    p_abrange_inicial          in  varchar2 default null,
    p_abrange_acrescimo        in  varchar2 default null,
    p_abrange_reajuste         in  varchar2 default null,
    p_sq_acordo_parcela        in  number   default null,
    p_data_cancelamento        in date      default null,
    p_valor_cancelamento       in number    default null,
    p_chave_nova               out number
   ) is
   w_chave   number(18);
begin
   If p_operacao = 'I' Then
      select sq_acordo_nota.nextval into w_chave from dual;
      -- Insere registro
      insert into ac_acordo_nota
        (sq_acordo_nota, sq_siw_solicitacao, sq_tipo_documento, sq_acordo_outra_parte, 
         sq_acordo_aditivo, numero, data, valor, sq_lcfonte_recurso, sq_especificacao_despesa, observacao,
         abrange_inicial, abrange_acrescimo, abrange_reajuste, data_cancelamento, valor_cancelamento
        )
        (select w_chave, p_chave_aux, p_sq_tipo_documento, p_sq_acordo_outra_parte, 
          p_sq_acordo_aditivo, p_numero, p_data, p_valor, p_sq_lcfonte_recurso, p_espec_despesa, p_observacao,
          p_abrange_inicial, p_abrange_acrescimo, p_abrange_reajuste, p_data_cancelamento, p_valor_cancelamento 
           from dual
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
             sq_especificacao_despesa = p_espec_despesa,
             observacao               = p_observacao,
             abrange_inicial          = p_abrange_inicial,
             abrange_acrescimo        = p_abrange_acrescimo,
             abrange_reajuste         = p_abrange_reajuste,
             data_cancelamento        = p_data_cancelamento,
             valor_cancelamento       = p_valor_cancelamento
       where sq_acordo_nota = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete ac_acordo_nota where sq_acordo_nota = p_chave;
   Elsif p_operacao = 'PARCELA' Then
      insert into ac_parcela_nota
         (sq_acordo_parcela, sq_acordo_nota)
      values
         (p_sq_acordo_parcela, p_chave);
   Elsif p_operacao = 'EXCLUIPARCELA' Then
      delete ac_parcela_nota where sq_acordo_nota = p_chave;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end SP_PutAcordoNota;
/
