create or replace procedure SP_PutAcordoParc
   (p_operacao            in varchar2,
    p_chave               in number,
    p_chave_aux           in number,
    p_ordem               in number   default null,
    p_data                in date     default null,
    p_valor               in number   default null,
    p_observacao          in varchar2 default null,
    p_tipo_geracao        in number   default null,
    p_vencimento          in varchar2 default null,
    p_dia_vencimento      in number   default null,
    p_valor_parcela       in varchar2 default null,
    p_valor_diferente     in number   default null
   ) is
   
   w_cont       number(4) := 1;
   w_vencimento date;
   w_dia        varchar2(2) := substr(100+p_dia_vencimento,2,2);
   w_valor_1    number(18,4);
   w_valor      number(18,4);
   w_valor_n    number(18,4);
   w_meses      number(4);
   w_reg        ac_acordo%rowtype;
begin
   If p_operacao = 'I' Then -- Inclus�o
      insert into ac_acordo_parcela
        (sq_acordo_parcela,         sq_siw_solicitacao, ordem,   emissao, vencimento, observacao,   valor)
      values
        (sq_acordo_parcela.nextval, p_chave,            p_ordem, sysdate, p_data,     p_observacao, p_valor);
   Elsif p_operacao = 'G' Then -- Gera��o parametrizada de parcelas
      -- Remove as parcelas existentes
      delete ac_acordo_parcela where sq_siw_solicitacao = p_chave;
      
      -- Recupera os dados do acordo
      select * into w_reg from ac_acordo where sq_siw_solicitacao = p_chave;
      
      If p_tipo_geracao = 11 Then -- Se uma parcela, no in�cio do acordo
         insert into ac_acordo_parcela
           (sq_acordo_parcela,         sq_siw_solicitacao, ordem,   emissao, vencimento,   observacao,   valor)
         values
           (sq_acordo_parcela.nextval, p_chave,            1,       sysdate, w_reg.inicio, p_observacao, w_reg.valor_inicial);
      Elsif p_tipo_geracao = 12 Then -- Se uma parcela, no fim do acordo
         insert into ac_acordo_parcela
           (sq_acordo_parcela,         sq_siw_solicitacao, ordem,   emissao, vencimento,   observacao,   valor)
         values
           (sq_acordo_parcela.nextval, p_chave,            1,       sysdate, w_reg.fim,    p_observacao, w_reg.valor_inicial);
      Else
         w_meses := round(months_between(w_reg.fim, w_reg.inicio));
         If w_meses = 0 Then
            w_meses := 1;
         End If;
         If p_valor_parcela = 'I' Then
            w_valor   := w_reg.valor_inicial / w_meses;
            w_valor_1 := w_valor;
            w_valor_n := w_valor;
         Elsif p_valor_parcela = 'P' Then
            w_valor_1 := p_valor_diferente;
            w_valor   := trunc((w_reg.valor_inicial-w_valor_1) / (w_meses-1),2);
            w_valor_n := w_reg.valor_inicial - (w_valor_1 + (w_valor * (w_meses - 2)));
         Else
            w_valor_n := p_valor_diferente;
            w_valor   := trunc((w_reg.valor_inicial-w_valor_n) / (w_meses-1),2);
            w_valor_1 := w_reg.valor_inicial - (w_valor_n + (w_valor * (w_meses - 2)));
         End If;
          
         for w_cont in 1 .. w_meses loop
            If w_cont = 1 Then
               -- Calcula a data de vencimento da primeira parcela
               If p_vencimento = 'P' Then
                  If p_tipo_geracao = 21 
                     Then w_vencimento := to_date('01'||to_char(add_months(w_reg.inicio,1),'mmyyyy'),'ddmmyyyy');
                     Else w_vencimento := w_reg.inicio;
                  End If;
               Elsif p_vencimento = 'U' Then
                  w_vencimento := last_day(w_reg.inicio);
               Else
                  If p_tipo_geracao = 21 Then
                     w_vencimento := to_date(w_dia||to_char(add_months(w_reg.inicio,1),'mmyyyy'),'ddmmyyyy');
                  Else
                     w_vencimento := to_date(w_dia||to_char(w_reg.inicio,'mmyyyy'),'ddmmyyyy');
                  End If;
               End If;

               If w_vencimento > w_reg.fim    Then w_vencimento := w_reg.fim;    End If;
               If w_vencimento < w_reg.inicio Then w_vencimento := w_reg.inicio; End If;

               -- insere a primeira parcela
               insert into ac_acordo_parcela
                 (sq_acordo_parcela,         sq_siw_solicitacao, ordem,   emissao, vencimento,   observacao,   valor)
               values
                 (sq_acordo_parcela.nextval, p_chave,            w_cont,  sysdate, w_vencimento, p_observacao, round(w_valor_1,2));
            Elsif w_cont = w_meses Then
               -- Calcula a data de vencimento da �ltima parcela
               w_vencimento := add_months(w_vencimento,1);

               If w_vencimento > w_reg.fim    Then w_vencimento := w_reg.fim;    End If;
               If w_vencimento < w_reg.inicio Then w_vencimento := w_reg.inicio; End If;
               
               -- Insere a �ltima parcela
               insert into ac_acordo_parcela
                 (sq_acordo_parcela,         sq_siw_solicitacao, ordem,   emissao, vencimento,   observacao,   valor)
               values
                 (sq_acordo_parcela.nextval, p_chave,            w_cont,  sysdate, w_vencimento, p_observacao, round(w_valor_n,2));
            Else
               -- Calcula a data de vencimento das parcelas intermedi�rias
               w_vencimento := add_months(w_vencimento,1);
               
               If p_vencimento    = 'P' Then w_vencimento := to_date('01'||to_char(w_vencimento,'mmyyyy'),'ddmmyyyy'); 
               Elsif p_vencimento = 'U' Then w_vencimento := last_day(w_vencimento); 
               Else w_vencimento := to_date(w_dia||to_char(w_vencimento,'mmyyyy'),'ddmmyyyy'); 
               End If;

               If w_vencimento > w_reg.fim    Then w_vencimento := w_reg.fim;    End If;
               If w_vencimento < w_reg.inicio Then w_vencimento := w_reg.inicio; End If;
               
               
               insert into ac_acordo_parcela
                 (sq_acordo_parcela,         sq_siw_solicitacao, ordem,   emissao, vencimento,   observacao,   valor)
               values
                 (sq_acordo_parcela.nextval, p_chave,            w_cont,  sysdate, w_vencimento, p_observacao, round(w_valor,2));
            End If;
         end loop;
      End If;
   Elsif p_operacao = 'A' Then -- Altera��o
      update ac_acordo_parcela
         set ordem      = p_ordem,
             emissao    = sysdate,
             vencimento = p_data,
             observacao = p_observacao,
             valor = p_valor
       where sq_acordo_parcela = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclus�o
      delete ac_acordo_parcela where sq_acordo_parcela = p_chave_aux;
   End If;
end SP_PutAcordoParc;
/
