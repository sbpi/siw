create or replace trigger TG_FN_LANCAMENTO_DOC_IN_UP
  after insert or update on fn_lancamento_doc
  for each row
declare
  w_quitacao_retencao date;
  w_quitacao_imposto  date;
  w_valor_retencao     number(18,2) := 0;

  -- cursor para recuperar os impostos calculados com base no valor nominal
  cursor c_imposto_total is
    select a.sq_imposto,
           a.aliquota_total, a.aliquota_retencao, a.aliquota_normal,
           decode(:new.calcula_tributo,
                 'S',decode(:new.calcula_retencao,'S',:new.valor*a.aliquota_total/100, :new.valor*a.aliquota_normal/100),
                     decode(:new.calcula_retencao, 'S', :new.valor*a.aliquota_retencao/100, 0)
                 ) vl_total,
           decode(:new.calcula_retencao, 'S', :new.valor*a.aliquota_retencao/100, 0) vl_retencao,
           decode(:new.calcula_tributo, 'S', :new.valor*a.aliquota_normal/100, 0) vl_normal,
           b.calculo, b.dia_pagamento,
           decode(b.sigla, 'CPMF', 'I', 'ISS', 'M', 'T') prazo
      from fn_imposto_incid   a,
           fn_imposto         b,
           fn_lancamento      c
     where a.sq_tipo_documento  = :new.sq_tipo_documento
       and a.sq_imposto         = b.sq_imposto 
       and b.calculo            = 0
       and a.sq_tipo_lancamento = c.sq_tipo_lancamento 
       and c.sq_siw_solicitacao = :new.sq_siw_solicitacao;

  -- cursor para recuperar os impostos baseados no valor líquido do documento,
  -- deduzidos os impostos
  cursor c_imposto_liquido (l_valor in number) is
    select a.sq_imposto,
           a.aliquota_total, a.aliquota_retencao, a.aliquota_normal,
           l_valor*a.aliquota_total/100 vl_total,
           l_valor*a.aliquota_retencao/100 vl_retencao,
           l_valor*a.aliquota_normal/100 vl_normal,
           b.calculo, b.dia_pagamento,
           decode(b.sigla, 'CPMF', 'I', 'ISS', 'M', 'T') prazo
      from fn_imposto_incid   a,
           fn_imposto         b,
           fn_lancamento      c
     where a.sq_tipo_documento  = :new.sq_tipo_documento
       and a.sq_imposto         = b.sq_imposto 
       and b.calculo            = 1
       and a.sq_tipo_lancamento = c.sq_tipo_lancamento 
       and c.sq_siw_solicitacao = :new.sq_siw_solicitacao;
begin
  -- Remove os impostos existentes para o documento
  delete fn_imposto_doc where sq_lancamento_doc = :new.sq_lancamento_doc;

  -- Insere a nova relação de impostos calculados com base no valor total do documento
  for crec in c_imposto_total loop
     w_valor_retencao := w_valor_retencao + crec.vl_retencao;
     -- Calcula a data de vencimento da retenção
     If crec.prazo = 'I' Then -- Se o prazo for imediato
        w_quitacao_retencao := :new.data;
     Else
        w_quitacao_retencao := :new.data + 13 - to_char(:new.data,'d');
     End If;

     -- Calcula a data de vencimento do imposto normal
     If crec.prazo = 'I' Then -- Se o prazo for imediato
        w_quitacao_imposto := w_quitacao_retencao;
     Elsif crec.prazo = 'M' Then -- Se o vencimento for no mês seguinte
        w_quitacao_imposto := last_day(add_months(:new.data,1));
     Else -- Se o vencimento for no mês seguinte ao encerramento do trimestre
        w_quitacao_imposto := last_day(add_months(to_date('0101'||to_char(:new.data,'yyyy'),'ddmmyyyy'),ceil(to_char(:new.data,'mm')/3)*3));
     End if;
     -- Ajusta o dia do vencimento
     If to_char(w_quitacao_imposto,'dd') > crec.dia_pagamento and crec.dia_pagamento > 0 Then
        w_quitacao_imposto := to_date(substr(100+crec.dia_pagamento,2,2)||to_char(w_quitacao_imposto,'mmyyyy'),'ddmmyyyy');
     End If;

     If crec.vl_total <> 0 Then
        -- Grava o imposto com as datas calculadas
        insert into fn_imposto_doc
          (sq_lancamento_doc, sq_imposto, aliquota_total, aliquota_retencao, aliquota_normal, valor_total, valor_retencao, valor_normal, quitacao_retencao, quitacao_imposto)
        values
          (:new.sq_lancamento_doc, crec.sq_imposto, crec.aliquota_total, crec.aliquota_retencao, crec.aliquota_normal, crec.vl_total, crec.vl_retencao, crec.vl_normal, w_quitacao_retencao, w_quitacao_imposto);
     End If;
  end loop;

  -- Insere os impostos calculados com base no valor líquido do documento
  for crec in c_imposto_liquido (:new.valor-w_valor_retencao) loop
     -- Calcula a data de vencimento da retenção
     If crec.prazo = 'I' Then -- Se o prazo for imediato
        w_quitacao_retencao := :new.data;
     Else
        w_quitacao_retencao := :new.data + 13 - to_char(:new.data,'d');
     End If;

     -- Calcula a data de vencimento do imposto normal
     If crec.prazo = 'I' Then -- Se o prazo for imediato
        w_quitacao_imposto := w_quitacao_retencao;
     Elsif crec.prazo = 'M' Then -- Se o vencimento for no mês seguinte
        w_quitacao_imposto := last_day(add_months(:new.data,1));
     Else -- Se o vencimento for no mês seguinte ao encerramento do trimestre
        w_quitacao_imposto := last_day(add_months(to_date('0101'||to_char(:new.data,'yyyy'),'ddmmyyyy'),ceil(to_char(:new.data,'mm')/3)*3));
     End if;
     -- Ajusta o dia do vencimento
     If to_char(w_quitacao_imposto,'dd') > crec.dia_pagamento and crec.dia_pagamento > 0 Then
        w_quitacao_imposto := to_date(substr(100+crec.dia_pagamento,2,2)||to_char(w_quitacao_imposto,'mmyyyy'),'ddmmyyyy');
     End If;

     -- Grava o imposto com as datas calculadas, se o valor total for maior que zero
     If crec.vl_total <> 0 Then
        insert into fn_imposto_doc
          (sq_lancamento_doc, sq_imposto, aliquota_total, aliquota_retencao, aliquota_normal, valor_total, valor_retencao, valor_normal, quitacao_retencao, quitacao_imposto)
        values
          (:new.sq_lancamento_doc, crec.sq_imposto, crec.aliquota_total, crec.aliquota_retencao, crec.aliquota_normal, crec.vl_total, crec.vl_retencao, crec.vl_normal, w_quitacao_retencao, w_quitacao_imposto);
     End If;
  end loop;

end TG_FN_LANCAMENTO_DOC_IN_UP;
/
