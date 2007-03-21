create or replace procedure SP_PutAcordoAditivo
   (p_operacao                 in  varchar2,
    p_chave                    in  number   default null,
    p_chave_aux                in  number   default null,
    p_protocolo                in  number   default null,
    p_codigo                   in  varchar2 default null,
    p_objeto                   in  varchar2 default null,
    p_inicio                   in  date     default null,
    p_fim                      in  date     default null,
    p_duracao                  in  number   default null,
    p_documento_origem         in  varchar2 default null,
    p_documento_data           in  date     default null,
    p_variacao_valor           in  number   default null,
    p_prorrogacao              in  varchar2 default null,
    p_revisao                  in  varchar2 default null,
    p_acrescimo                in  varchar2 default null,
    p_supressao                in  varchar2 default null,
    p_observacao               in  varchar2 default null,
    p_valor_reajuste           in  number   default null,
    p_parcela_reajustada       in  number   default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ac_acordo_aditivo
        (sq_acordo_aditivo, sq_siw_solicitacao, protocolo, codigo, objeto, inicio, fim, 
         duracao, documento_origem, documento_data, variacao_valor, prorrogacao, revisao, 
         acrescimo, supressao, observacao, valor_reajuste, parcela_reajustada)
       
     (select sq_acordo_aditivo.nextval, p_chave_aux, p_protocolo, p_codigo, p_objeto, p_inicio, p_fim, 
         p_duracao, p_documento_origem, p_documento_data, p_variacao_valor, p_prorrogacao, p_revisao, 
         p_acrescimo, p_supressao, p_observacao, p_valor_reajuste, p_parcela_reajustada from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update ac_acordo_aditivo
         set protocolo          = p_protocolo,
             codigo             = p_codigo,
             objeto             = trim(p_objeto),
             inicio             = p_inicio,
             fim                = p_fim,
             duracao            = p_duracao,
             documento_origem   = p_documento_origem,
             documento_data     = p_documento_data,
             variacao_valor     = p_variacao_valor,
             prorrogacao        = p_prorrogacao,
             revisao            = p_revisao,
             acrescimo          = p_acrescimo,
             supressao          = p_supressao,
             observacao         = p_observacao,
             valor_reajuste     = p_valor_reajuste,
             parcela_reajustada = p_parcela_reajustada
       where sq_acordo_aditivo = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete ac_acordo_aditivo where sq_acordo_aditivo = p_chave;
   End If;
end SP_PutAcordoAditivo;
/
