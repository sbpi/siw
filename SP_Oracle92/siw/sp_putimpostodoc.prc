create or replace procedure SP_PutImpostoDoc
   (p_operacao            in varchar2,
    p_documento           in number,
    p_imposto             in number,
    p_solic_retencao      in number   default null,
    p_solic_imposto       in number   default null,
    p_aliquota_total      in number   default null,
    p_aliquota_retencao   in number   default null,
    p_aliquota_normal     in number   default null,
    p_valor_total         in number   default null,
    p_valor_retencao      in number   default null,
    p_valor_normal        in number   default null,
    p_quitacao_retencao   in date     default null,
    p_quitacao_imposto    in date     default null
   ) is
   
begin
   If p_operacao = 'I' Then -- Inclusão
      insert into fn_imposto_doc
        (sq_lancamento_doc, sq_imposto,    solic_retencao,   solic_imposto,   aliquota_total,      aliquota_retencao, 
         aliquota_normal,   valor_total,   valor_retencao,   valor_normal,    quitacao_retencao,   quitacao_imposto)
      values
        (p_documento,       p_imposto,     p_solic_retencao, p_solic_imposto, p_aliquota_total,    p_aliquota_retencao, 
         p_aliquota_normal, p_valor_total, p_valor_retencao, p_valor_normal,  p_quitacao_retencao, p_quitacao_imposto);
   Elsif p_operacao = 'A' Then -- Alteração
      update fn_imposto_doc
         set solic_retencao    = p_solic_retencao,
             solic_imposto     = p_solic_imposto,
             aliquota_total    = p_aliquota_total,
             aliquota_retencao = p_aliquota_retencao,
             aliquota_normal   = p_aliquota_normal,
             valor_total       = p_valor_total,
             valor_retencao    = p_valor_retencao,
             valor_normal      = p_valor_normal,
             quitacao_retencao = p_quitacao_retencao,
             quitacao_imposto  = p_quitacao_imposto
       where sq_lancamento_doc = p_documento
         and sq_imposto        = p_imposto;
   Elsif p_operacao = 'E' Then -- Exclusão
      delete fn_imposto_doc where sq_lancamento_doc = p_documento and sq_imposto = p_imposto;
   End If;
end SP_PutImpostoDoc;
/
