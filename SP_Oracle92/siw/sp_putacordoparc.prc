create or replace procedure SP_PutAcordoParc
   (p_operacao            in varchar2,
    p_chave               in number,
    p_chave_aux           in number,
    p_aditivo             in number,
    p_ordem               in number   default null,
    p_data                in date     default null,
    p_valor               in number   default null,
    p_observacao          in varchar2 default null,
    p_tipo_geracao        in number   default null,
    p_vencimento          in varchar2 default null,
    p_dia_vencimento      in number   default null,
    p_valor_parcela       in varchar2 default null,
    p_valor_diferente     in number   default null,
    p_per_ini             in date     default null,
    p_per_fim             in date     default null,
    p_valor_inicial       in number   default null,
    p_valor_excedente     in number   default null,
    p_valor_reajuste      in number   default null
   ) is
   
   w_cont        number(4) := 1;
   w_vencimento  date;
   w_dia         varchar2(2) := substr(100+p_dia_vencimento,2,2);
   w_valor_1     number(18,4);
   w_valor       number(18,4);
   w_valor_n     number(18,4);
   w_meses       number(4);
   w_meses_parc  number(4);
   w_dias_1      number(5);
   w_dias_n      number(5);
   w_per_ini     date;
   w_per_fim     date;
   w_reg         ac_acordo%rowtype;
   w_aditivo     ac_acordo_aditivo%rowtype;
   w_inicio      date;
   w_fim         date;
   w_total       number(18,4);
   w_ordem       number(18) := 0;
   w_inicial     number(18,4) := p_valor_inicial;
   w_reajuste    number(18,4) := p_valor_reajuste;
   w_excedente   number(18,4) := p_valor_excedente;
   w_inicial_1   number(18,4);
   w_reajuste_1  number(18,4);
   w_excedente_1 number(18,4);
   w_inicial_n   number(18,4);
   w_reajuste_n  number(18,4);
   w_excedente_n number(18,4);
   w_total_i     number(18,4);
   w_total_e     number(18,4);
   w_total_r     number(18,4);
   w_ultimo      number(2);
begin
   If p_operacao = 'I' Then -- Inclusão
      -- O valor de uma parcela de aditivo depende da soma do valor inicial mais reajuste mais excedentes
      If p_aditivo is not null Then
         w_valor := coalesce(w_inicial,0) + coalesce(w_reajuste,0) + coalesce(w_excedente,0);
      Else
         w_valor := p_valor;
      End If;
      
      insert into ac_acordo_parcela
        (sq_acordo_parcela,         sq_siw_solicitacao, ordem,              emissao,       vencimento,      observacao,        valor, 
         inicio,                    fim,                sq_acordo_aditivo,  valor_inicial, valor_excedente, valor_reajuste)
      values
        (sq_acordo_parcela.nextval, p_chave,            p_ordem,            sysdate,       p_data,          p_observacao,      w_valor,
         p_per_ini,                 p_per_fim,          p_aditivo,          w_inicial,     w_excedente,     w_reajuste);
   Elsif p_operacao = 'A' Then -- Alteração
      -- O valor de uma parcela de aditivo depende da soma do valor inicial mais reajuste mais excedentes
      If p_aditivo is not null Then
         w_valor := coalesce(w_inicial,0) + coalesce(w_reajuste,0) + coalesce(w_excedente,0);
      Else
         w_valor := p_valor;
      End If;
      
      update ac_acordo_parcela set
         ordem           = p_ordem,
         emissao         = sysdate,
         vencimento      = p_data,
         observacao      = p_observacao,
         valor           = w_valor,
         inicio          = p_per_ini,
         fim             = p_per_fim,
         valor_inicial   = w_inicial,
         valor_excedente = w_excedente,
         valor_reajuste  = w_reajuste
      where sq_acordo_parcela = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      delete ac_acordo_parcela where sq_acordo_parcela = p_chave_aux;
   Elsif p_operacao = 'G' Then -- Geração parametrizada de parcelas
       -- Recupera os dados do acordo
       select * into w_reg from ac_acordo where sq_siw_solicitacao = p_chave;
       w_inicio := w_reg.inicio;
       w_fim    := w_reg.fim;
       w_total  := w_reg.valor_inicial;

      If p_aditivo is null Then
         -- Remove as parcelas existentes do contrato
         delete ac_acordo_parcela where sq_siw_solicitacao = p_chave;
      Else
         -- Recupera os dados do aditivo
         select * into w_aditivo from ac_acordo_aditivo where sq_acordo_aditivo = p_aditivo;
         If p_tipo_geracao = 11 or p_tipo_geracao = 12 Then 
           -- Se uma parcela, usa os valores totais do aditivo
           w_inicial   := w_aditivo.valor_inicial;
           w_reajuste  := w_aditivo.valor_reajuste;
           w_excedente := w_aditivo.valor_acrescimo;
         Else
           -- Se parcelas mensais, usa os valores mensais do aditivo
           w_inicial   := w_aditivo.parcela_inicial;
           w_reajuste  := w_aditivo.parcela_reajustada;
           w_excedente := w_aditivo.parcela_acrescida;
         End If;

         If w_aditivo.prorrogacao = 'S' Then
            -- Remove as parcelas existentes do aditivo
            delete ac_acordo_parcela where sq_acordo_aditivo = p_aditivo;
            
            -- Recupera dados para geração de parcelas
            w_inicio := w_aditivo.inicio;
            w_fim    := w_aditivo.fim;
            select max(ordem) into w_ordem from ac_acordo_parcela where sq_siw_solicitacao = p_chave;
            w_total    := w_aditivo.valor_aditivo;
            w_total_i  := w_aditivo.valor_inicial;
            w_total_e  := w_aditivo.valor_acrescimo;
            w_total_r  := w_aditivo.valor_reajuste;
         End If;
      End If;
      
      If p_tipo_geracao = 11 Then -- Se uma parcela, no início do acordo
         insert into ac_acordo_parcela
           (sq_acordo_parcela,         sq_siw_solicitacao, ordem,              emissao,       vencimento,      observacao,    valor, 
            inicio,                    fim,                sq_acordo_aditivo,  valor_inicial, valor_excedente, valor_reajuste)
         values
           (sq_acordo_parcela.nextval, p_chave,            1+w_ordem,          sysdate,       w_inicio,        p_observacao,  w_total, 
            w_inicio,                  w_fim,              p_aditivo,          w_inicial,     w_excedente,     w_reajuste);
      Elsif p_tipo_geracao = 12 Then -- Se uma parcela, no fim do acordo
         insert into ac_acordo_parcela
           (sq_acordo_parcela,         sq_siw_solicitacao, ordem,              emissao,       vencimento,      observacao,    valor, 
            inicio,                    fim,                sq_acordo_aditivo,  valor_inicial, valor_excedente, valor_reajuste)
         values
           (sq_acordo_parcela.nextval, p_chave,            1+w_ordem,          sysdate,       w_fim,           p_observacao,  w_total, 
            w_inicio,                  w_fim,              p_aditivo,          w_inicial,     w_excedente,     w_reajuste);
      Else
         -- Define o número de meses da vigência para cálculo do valor mensal e para geração das parcelas
         w_meses      := round(months_between(w_fim, w_inicio));
         w_meses_parc := round(months_between(last_day(w_fim), to_date('01/'||to_char(w_inicio,'mm/yyyy'),'dd/mm/yyyy')));
         If w_meses = 0 Then
            w_meses := 1;
         End If;
         
         If p_valor_parcela = 'I' or p_valor_parcela = 'C' Then
            If p_valor_parcela = 'I' 
               Then w_valor   := round(w_total / w_meses_parc,2);
               Else w_valor   := round(w_total / w_meses,2);
            End If;
            w_valor_1     := w_valor;
            w_valor_n     := w_valor;
            w_inicial_1   := w_inicial;
            w_inicial_n   := w_inicial;
            w_excedente_1 := w_excedente;
            w_excedente_n := w_excedente;
            w_reajuste_1  := w_reajuste;
            w_reajuste_n  := w_reajuste;

            -- Aplica proporcionalidade na primeira e última parcelas
            If p_valor_parcela = 'C' Then
               -- Calcula o valor proporcional do primeiro mês, considerando o mínimo de 1 dia
               w_ultimo := 30; 
               If to_char(last_day(w_inicio),'dd') < 30 Then w_ultimo := to_char(last_day(w_inicio),'dd'); End If;
               w_dias_1 := to_date(w_ultimo||'/'||to_char(w_inicio,'mm/yyyy'),'dd/mm/yyyy') - w_inicio + 1 + (30-w_ultimo);
               If w_dias_1 <= 0 Then w_dias_1 := 1; End If;
               w_valor_1     := round((w_dias_1/30) * w_valor,2);
               w_inicial_1   := round((w_dias_1/30) * w_inicial,2);
               w_excedente_1 := round((w_dias_1/30) * w_excedente,2);
               w_reajuste_1  := round((w_dias_1/30) * w_reajuste,2);
               

               If (w_meses - 2) < 1 Then
                  w_valor_n     := (w_total   - w_valor_1);
                  w_inicial_n   := (w_total_i - w_inicial_1);
                  w_excedente_n := (w_total_e - w_excedente_1);
                  w_reajuste_n  := (w_total_r - w_reajuste_1);
               Else
                  -- Calcula o valor proporcional do último mês, considerando o máximo de 30 dias
                  w_dias_n := w_fim - to_date('01/'||to_char(w_fim,'mm/yyyy'),'dd/mm/yyyy') + 1;
                  If w_dias_n > 30 Then w_dias_n := 30; End If;
                  w_valor_n     := round((w_dias_n/30) * w_valor,2);
                  w_inicial_n   := round((w_dias_n/30) * w_inicial,2);
                  w_excedente_n := round((w_dias_n/30) * w_excedente,2);
                  w_reajuste_n  := round((w_dias_n/30) * w_reajuste,2);

                  w_valor     := (w_total   - w_valor_1     - w_valor_n)     / (w_meses_parc - 2);
                  w_inicial   := (w_total_i - w_inicial_1   - w_inicial_n)   / (w_meses_parc - 2);
                  w_excedente := (w_total_e - w_excedente_1 - w_excedente_n) / (w_meses_parc - 2);
                  w_reajuste  := (w_total_r - w_reajuste_1  - w_reajuste_n)  / (w_meses_parc - 2);
               End If;
            End If;
         Elsif p_valor_parcela = 'P' Then
            w_valor_1 := p_valor_diferente;
            w_valor   := trunc((w_total-w_valor_1) / (w_meses_parc-1),2);
            w_valor_n := w_total - (w_valor_1 + (w_valor * (w_meses_parc - 2)));
         Else
            w_valor_n := p_valor_diferente;
            w_valor   := trunc((w_total-w_valor_n) / (w_meses_parc-1),2);
            w_valor_1 := w_total - (w_valor_n + (w_valor * (w_meses_parc - 2)));
         End If;
          
         for w_cont in 1 .. w_meses_parc loop
            If w_cont = 1 Then
               -- Define o período de realização da primeira parcela
               w_per_ini := w_inicio;
               w_per_fim := last_day(w_inicio);
               
               -- Calcula a data de vencimento da primeira parcela
               If p_vencimento = 'P' Then
                  If p_tipo_geracao = 21 
                     Then w_vencimento := to_date('01'||to_char(add_months(w_inicio,1),'mmyyyy'),'ddmmyyyy');
                     Else w_vencimento := w_inicio;
                  End If;
               Elsif p_vencimento = 'U' Then
                  w_vencimento := last_day(w_inicio);
               Else
                  If p_tipo_geracao = 21 Then
                     w_vencimento := to_date(w_dia||to_char(add_months(w_inicio,1),'mmyyyy'),'ddmmyyyy');
                  Else
                     w_vencimento := to_date(w_dia||to_char(w_inicio,'mmyyyy'),'ddmmyyyy');
                  End If;
               End If;

               If w_vencimento > w_fim    Then w_vencimento := w_fim;    End If;
               If w_vencimento < w_inicio Then w_vencimento := w_inicio; End If;

               -- insere a primeira parcela
               insert into ac_acordo_parcela
                 (sq_acordo_parcela,         sq_siw_solicitacao, ordem,              emissao,              vencimento,             observacao,    valor, 
                  inicio,                    fim,                sq_acordo_aditivo,  valor_inicial,        valor_excedente,        valor_reajuste)
               values
                 (sq_acordo_parcela.nextval, p_chave,            w_cont+w_ordem,     sysdate,              w_vencimento,           p_observacao,  round(w_valor_1,2),
                  w_per_ini,                 w_per_fim,          p_aditivo,          round(w_inicial_1,2), round(w_excedente_1,2), round(w_reajuste_1,2));
            Elsif w_cont = w_meses_parc Then
               -- Define o período de realização da ultima parcela
               w_per_ini := to_date('01'||to_char(w_fim,'mmyyyy'),'ddmmyyyy'); 
               w_per_fim := w_fim;

               -- Calcula a data de vencimento da última parcela
               w_vencimento := add_months(w_vencimento,1);

               If w_vencimento > w_fim    Then w_vencimento := w_fim;    End If;
               If w_vencimento < w_inicio Then w_vencimento := w_inicio; End If;
               
               -- Insere a última parcela
               insert into ac_acordo_parcela
                 (sq_acordo_parcela,         sq_siw_solicitacao, ordem,              emissao,              vencimento,             observacao,    valor, 
                  inicio,                    fim,                sq_acordo_aditivo,  valor_inicial,        valor_excedente,        valor_reajuste)
               values
                 (sq_acordo_parcela.nextval, p_chave,            w_cont+w_ordem,     sysdate,              w_vencimento,           p_observacao,  round(w_valor_n,2),
                  w_per_ini,                 w_per_fim,          p_aditivo,          round(w_inicial_n,2), round(w_excedente_n,2), round(w_reajuste_n,2));
            Else
               -- Calcula a data de vencimento das parcelas intermediárias
               w_vencimento := add_months(w_vencimento,1);
               
               -- Define o período de realização da ultima parcela
               w_per_ini := to_date('01'||to_char(add_months(w_inicio,(w_cont-1)),'mmyyyy'),'ddmmyyyy'); 
               w_per_fim := last_day(add_months(w_inicio,(w_cont-1)));

               If p_vencimento    = 'P' Then w_vencimento := to_date('01'||to_char(w_vencimento,'mmyyyy'),'ddmmyyyy'); 
               Elsif p_vencimento = 'U' Then w_vencimento := last_day(w_vencimento); 
               Else w_vencimento := to_date(w_dia||to_char(w_vencimento,'mmyyyy'),'ddmmyyyy'); 
               End If;

               If w_vencimento > w_fim    Then w_vencimento := w_fim;    End If;
               If w_vencimento < w_inicio Then w_vencimento := w_inicio; End If;
               
               
               insert into ac_acordo_parcela
                 (sq_acordo_parcela,         sq_siw_solicitacao, ordem,              emissao,            vencimento,           observacao,    valor, 
                  inicio,                    fim,                sq_acordo_aditivo,  valor_inicial,      valor_excedente,      valor_reajuste)
               values
                 (sq_acordo_parcela.nextval, p_chave,            w_cont+w_ordem,     sysdate,            w_vencimento,         p_observacao,  round(w_valor,2),
                  w_per_ini,                 w_per_fim,          p_aditivo,          round(w_inicial,2), round(w_excedente,2), round(w_reajuste,2));
            End If;
         end loop;
      End If;
   End If;
end SP_PutAcordoParc;
/
