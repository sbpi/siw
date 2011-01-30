create or replace FUNCTION SP_PutAcordoParc
   (p_operacao            varchar,
    p_chave               numeric,
    p_chave_aux           numeric,
    p_aditivo             numeric,
    p_ordem               numeric,
    p_data                date,
    p_valor               numeric,
    p_observacao          varchar,
    p_tipo_geracao        numeric,
    p_tipo_mes            varchar,
    p_vencimento          varchar,
    p_dia_vencimento      numeric,
    p_valor_parcela       varchar,
    p_valor_diferente     numeric,
    p_per_ini             date,
    p_per_fim             date,
    p_valor_inicial       numeric,
    p_valor_excedente     numeric,
    p_valor_reajuste      numeric   
   ) RETURNS VOID AS $$
DECLARE
   
   w_cont        numeric(4) := 1;
   w_vencimento  date;
   w_dia         varchar(2) := substr(cast(100+p_dia_vencimento as varchar),2,2);
   w_valor_1     numeric(18,4);
   w_valor       numeric(18,4);
   w_valor_n     numeric(18,4);
   w_meses       numeric(4);
   w_meses_parc  numeric(4);
   w_dias_1      numeric(5);
   w_dias_n      numeric(5);
   w_dias        numeric(18);
   w_per_ini     date;
   w_per_fim     date;
   
   w_reg         ac_acordo%rowtype;
   w_aditivo     ac_acordo_aditivo%rowtype;
   w_existe_aditivo varchar(1) := 'N';
   w_parcela     ac_acordo_parcela%rowtype;
   
   w_inicio      date;
   w_fim         date;
   w_total       numeric(18,4);
   w_ordem       numeric(18) := 0;
   w_inicial     numeric(18,4) := coalesce(p_valor_inicial,p_valor,0);
   w_reajuste    numeric(18,4) := coalesce(p_valor_reajuste,0);
   w_excedente   numeric(18,4) := coalesce(p_valor_excedente,0);
   w_inicial_1   numeric(18,4);
   w_reajuste_1  numeric(18,4);
   w_excedente_1 numeric(18,4);
   w_inicial_n   numeric(18,4);
   w_reajuste_n  numeric(18,4);
   w_excedente_n numeric(18,4);
   w_total_i     numeric(18,4);
   w_total_e     numeric(18,4);
   w_total_r     numeric(18,4);
   w_ultimo      numeric(2);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- O valor de uma parcela de aditivo depende da soma do valor inicial mais reajuste mais excedentes
      If p_aditivo is not null Then
         w_valor := coalesce(w_inicial,0) + coalesce(w_reajuste,0) + coalesce(w_excedente,0);
      Else
         w_valor := p_valor;
      End If;
      
      insert into ac_acordo_parcela
        (sq_acordo_parcela,         sq_siw_solicitacao, ordem,              emissao,               vencimento,              observacao,        valor, 
         inicio,                    fim,                sq_acordo_aditivo,  valor_inicial,         valor_excedente,         valor_reajuste)
      values
        (nextVal('sq_acordo_parcela'), p_chave,            p_ordem,            now(),               p_data,                  p_observacao,      w_valor,
         p_per_ini,                 p_per_fim,          p_aditivo,          coalesce(w_inicial, case when p_aditivo is null then w_valor else 0 end), 
         coalesce(w_excedente,0), coalesce(w_reajuste,0));
   Elsif p_operacao = 'A' Then -- Alteração
      -- O valor de uma parcela de aditivo depende da soma do valor inicial mais reajuste mais excedentes
      If p_aditivo is not null Then
         w_valor := coalesce(w_inicial,0) + coalesce(w_reajuste,0) + coalesce(w_excedente,0);
      Else
         w_valor := p_valor;
      End If;
      
      update ac_acordo_parcela set
         ordem           = p_ordem,
         emissao         = now(),
         vencimento      = p_data,
         observacao      = p_observacao,
         valor           = w_valor,
         inicio          = p_per_ini,
         fim             = p_per_fim,
         valor_inicial   = coalesce(w_inicial, valor_inicial),
         valor_excedente = coalesce(w_excedente, valor_excedente),
         valor_reajuste  = coalesce(w_reajuste, valor_reajuste)
      where sq_acordo_parcela = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      If p_aditivo is not null Then
         w_existe_aditivo := 'S';
         select * into w_aditivo from ac_acordo_aditivo where sq_acordo_aditivo = p_aditivo;
         If w_aditivo.prorrogacao = 'N' Then
            update ac_acordo_parcela x
               set valor             = valor_inicial + valor_reajuste,
                   valor_excedente   = 0,
                   sq_acordo_aditivo = (select b.sq_acordo_aditivo
                                          from ac_acordo_parcela a
                                               left join ac_acordo_aditivo b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao and
                                                                                 a.inicio             between b.inicio and b.fim and
                                                                                 b.sq_acordo_aditivo  <> p_aditivo
                                                                                )
                                          where a.sq_acordo_parcela = x.sq_acordo_parcela
                                       )
             where sq_acordo_parcela = p_chave_aux;
         Else
            DELETE FROM ac_acordo_parcela where sq_acordo_parcela = p_chave_aux;
         End If;
      Else 
         DELETE FROM ac_acordo_parcela where sq_acordo_parcela = p_chave_aux;
      End If;
   Elsif p_operacao = 'G' Then -- Geração parametrizada de parcelas
       -- Recupera os dados do acordo
       select * into w_reg from ac_acordo where sq_siw_solicitacao = p_chave;
       w_inicio := w_reg.inicio;
       w_fim    := w_reg.fim;
       w_total  := w_reg.valor_inicial;

      If p_aditivo is null Then
         -- Remove as parcelas existentes do contrato
         DELETE FROM ac_acordo_parcela where sq_siw_solicitacao = p_chave;
         w_total_i  := 0;
         w_total_e  := 0;
         w_total_r  := 0;

         -- Se uma parcela, usa os valores totais do aditivo
         If p_tipo_geracao = 11 or p_tipo_geracao = 12 Then w_inicial := w_total; End If;
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
            DELETE FROM ac_acordo_parcela where sq_acordo_aditivo = p_aditivo;
            
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
           (nextVal('sq_acordo_parcela'), p_chave,            1+w_ordem,          now(),       w_inicio,        p_observacao,  w_total, 
            w_inicio,                  w_fim,              p_aditivo,          w_inicial,     w_excedente,     w_reajuste);
      Elsif p_tipo_geracao = 12 Then -- Se uma parcela, no fim do acordo
         insert into ac_acordo_parcela
           (sq_acordo_parcela,         sq_siw_solicitacao, ordem,              emissao,       vencimento,      observacao,    valor, 
            inicio,                    fim,                sq_acordo_aditivo,  valor_inicial, valor_excedente, valor_reajuste)
         values
           (nextVal('sq_acordo_parcela'), p_chave,            1+w_ordem,          now(),       w_fim,           p_observacao,  w_total, 
            w_inicio,                  w_fim,              p_aditivo,          w_inicial,     w_excedente,     w_reajuste);
      Else
         -- Define o número de meses da vigência para cálculo do valor mensal e para geração das parcelas
         w_meses      := round(months_between(w_fim, w_inicio));
         If p_tipo_mes = 'F' Then
            w_meses_parc := round(months_between(last_day(w_fim), to_date('01/'||to_char(w_inicio,'mm/yyyy'),'dd/mm/yyyy')));
         Else
            w_meses_parc := round(months_between(w_fim, w_inicio));
         End If;
         If w_meses = 0 Then
            w_meses := 1;
         End If;
         
         If p_valor_parcela = 'I' or p_valor_parcela = 'C' Then
            If p_valor_parcela = 'I' Then 
               w_valor   := round(w_total / w_meses_parc,2);
            Else 
               w_valor   := round(w_total / w_meses,2);
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
               w_excedente_1 := round((w_dias_1/30) * w_excedente,2);
               w_reajuste_1  := round((w_dias_1/30) * w_reajuste,2);
               

               If (w_meses - 2) < 1 Then
                  w_valor_n     := (w_total   - w_valor_1);
                  w_excedente_n := (w_total_e - w_excedente_1);
                  w_reajuste_n  := (w_total_r - w_reajuste_1);
               Else
                  -- Calcula o valor proporcional do último mês, considerando o máximo de 30 dias
                  w_dias_n := w_fim - to_date('01/'||to_char(w_fim,'mm/yyyy'),'dd/mm/yyyy') + 1;
                  If w_dias_n > 30 Then w_dias_n := 30; End If;
                  w_valor_n     := round((w_dias_n/30) * w_valor,2);
                  w_excedente_n := round((w_dias_n/30) * w_excedente,2);
                  w_reajuste_n  := round((w_dias_n/30) * w_reajuste,2);

                  w_valor     := (w_total   - w_valor_1     - w_valor_n)     / (w_meses_parc - 2);
                  w_inicial   := (w_total_i - w_inicial_1   - w_inicial_n)   / (w_meses_parc - 2);
                  w_excedente := (w_total_e - w_excedente_1 - w_excedente_n) / (w_meses_parc - 2);
                  w_reajuste  := (w_total_r - w_reajuste_1  - w_reajuste_n)  / (w_meses_parc - 2);
               End If;
            End If;
         Elsif p_valor_parcela = 'P' Then
            w_valor_1    := p_valor_diferente;
            w_valor      := trunc((w_total-w_valor_1) / (w_meses_parc-1),2);
            w_valor_n    := w_total - (w_valor_1 + (w_valor * (w_meses_parc - 2)));
            w_inicial_1  := w_valor_1;
            w_inicial_n  := w_valor_n;
         Else
            w_valor_n    := p_valor_diferente;
            w_valor      := trunc((w_total-w_valor_n) / (w_meses_parc-1),2);
            w_valor_1    := w_total - (w_valor_n + (w_valor * (w_meses_parc - 2)));
            w_inicial_1  := w_valor_1;
            w_inicial_n  := w_valor_n;
         End If;
          
         for w_cont in 1 .. w_meses_parc loop
            If w_cont = 1 Then
               -- Define o período de realização da primeira parcela
               w_per_ini := w_inicio;
               If p_tipo_mes = 'F'
                  Then w_per_fim := last_day(w_inicio);
                  Else w_per_fim := add_months(w_per_ini,1)-1;
               End If;
               
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

               -- Calcula o valor total da parcela
               If(w_existe_aditivo='N' or (w_existe_aditivo='S' and w_aditivo.prorrogacao='N')) Then
                  w_valor_1 := round(coalesce(w_valor_1,0),2) + round(coalesce(w_excedente_1,0),2) + round(coalesce(w_reajuste_1,0),2);
               Else
                  w_valor_1 :=  round(coalesce(w_inicial_1,p_valor_diferente),2) + round(coalesce(w_excedente_1,0),2) + round(coalesce(w_reajuste_1,0),2);
               End If;

               -- Se parcela do contrato original, valor inicial igual ao valor da parcela
               If p_aditivo is null Then w_inicial_1 := w_valor_1; End If;

               -- insere a primeira parcela
               insert into ac_acordo_parcela
                 (sq_acordo_parcela,         sq_siw_solicitacao, ordem,              emissao,              vencimento,             observacao,    valor, 
                  inicio,                    fim,                sq_acordo_aditivo,  valor_inicial,        valor_excedente,        valor_reajuste)
               values
                 (nextVal('sq_acordo_parcela'), p_chave,            w_cont+w_ordem,     now(),              w_vencimento,           p_observacao,  round(w_valor_1,2),
                  w_per_ini,                 w_per_fim,          p_aditivo,          round(coalesce(w_inicial_1,w_valor_1),2), round(coalesce(w_excedente_1,0),2), round(coalesce(w_reajuste_1,0),2));
            Elsif w_cont = w_meses_parc Then
               -- Define o período de realização da ultima parcela
               If p_tipo_mes = 'F' Then
                  w_per_ini := to_date('01'||to_char(w_fim,'mmyyyy'),'ddmmyyyy'); 
                  w_per_fim := w_fim;
               Else
                  w_per_ini := w_per_fim + 1;
                  w_per_fim := w_fim;
               End If;

               -- Calcula a data de vencimento da última parcela
               w_vencimento := add_months(w_vencimento,1);

               If w_vencimento > w_fim    Then w_vencimento := w_fim;    End If;
               If w_vencimento < w_inicio Then w_vencimento := w_inicio; End If;
               
               -- Calcula o valor total da parcela
               If(w_existe_aditivo='N' or (w_existe_aditivo='S' and w_aditivo.prorrogacao='N')) Then
                  w_valor_n := round(coalesce(w_valor_n,0),2) + round(coalesce(w_excedente_n,0),2) + round(coalesce(w_reajuste_n,0),2);
               Else
                  w_valor_n :=  round(coalesce(w_inicial_n,0),2) + round(coalesce(w_excedente_n,0),2) + round(coalesce(w_reajuste_n,0),2);
               End If;

               -- Se parcela do contrato original, valor inicial igual ao valor da parcela
               If p_aditivo is null Then w_inicial_n := w_valor_n; End If;

               -- Insere a última parcela
               insert into ac_acordo_parcela
                 (sq_acordo_parcela,         sq_siw_solicitacao, ordem,              emissao,              vencimento,             observacao,    valor, 
                  inicio,                    fim,                sq_acordo_aditivo,  valor_inicial,        valor_excedente,        valor_reajuste)
               values
                 (nextVal('sq_acordo_parcela'), p_chave,            w_cont+w_ordem,     now(),              w_vencimento,           p_observacao,  round(w_valor_n,2),
                  w_per_ini,                 w_per_fim,          p_aditivo,          round(coalesce(w_inicial_n,w_valor_n),2), round(coalesce(w_excedente_n,0),2), round(coalesce(w_reajuste_n,0),2));
            Else
               -- Calcula a data de vencimento das parcelas intermediárias
               w_vencimento := add_months(w_vencimento,1);
               
               -- Define o período de realização da ultima parcela
               If p_tipo_mes = 'F' Then
                  w_per_ini := to_date('01'||to_char(add_months(w_inicio,(w_cont-1)),'mmyyyy'),'ddmmyyyy'); 
                  w_per_fim := last_day(add_months(w_inicio,(w_cont-1)));
               Else
                  w_per_ini := w_per_fim + 1;
                  w_per_fim := add_months(w_per_ini,1)-1;
               End If;

               If p_vencimento    = 'P' Then w_vencimento := to_date('01'||to_char(w_vencimento,'mmyyyy'),'ddmmyyyy'); 
               Elsif p_vencimento = 'U' Then w_vencimento := last_day(w_vencimento); 
               Else w_vencimento := to_date(w_dia||to_char(w_vencimento,'mmyyyy'),'ddmmyyyy'); 
               End If;

               If w_vencimento > w_fim    Then w_vencimento := w_fim;    End If;
               If w_vencimento < w_inicio Then w_vencimento := w_inicio; End If;
               
               -- Calcula o valor total da parcela
               If(w_existe_aditivo='N' or (w_existe_aditivo='S' and w_aditivo.prorrogacao='N')) Then
                  w_valor := round(coalesce(w_valor,0),2) + round(coalesce(w_excedente,0),2) + round(coalesce(w_reajuste,0),2);
               Else
                  w_valor :=  round(coalesce(w_inicial,0),2) + round(coalesce(w_excedente,0),2) + round(coalesce(w_reajuste,0),2);
               End If;
               
               -- Se parcela do contrato original, valor inicial igual ao valor da parcela
               If p_aditivo is null Then w_inicial := w_valor; End If;

               insert into ac_acordo_parcela
                 (sq_acordo_parcela,         sq_siw_solicitacao, ordem,              emissao,            vencimento,           observacao,    valor, 
                  inicio,                    fim,                sq_acordo_aditivo,  valor_inicial,      valor_excedente,      valor_reajuste)
               values
                 (nextVal('sq_acordo_parcela'), p_chave,            w_cont+w_ordem,     now(),            w_vencimento,         p_observacao,  round(w_valor,2),
                  w_per_ini,                 w_per_fim,          p_aditivo,          round(coalesce(w_inicial,w_valor),2), round(coalesce(w_excedente,0),2), round(coalesce(w_reajuste,0),2));
            End If;
         end loop;
      End If;
      
      -- Atualiza o valor da parcela
      update ac_acordo_parcela
         set valor             = valor_inicial + valor_reajuste + valor_excedente
       where sq_siw_solicitacao = p_chave;

   Elsif p_operacao = 'V' Then
      -- Recupera os dados do aditivo
      select * into w_aditivo from ac_acordo_aditivo where sq_acordo_aditivo = p_aditivo;
      select * into w_parcela from ac_acordo_parcela where sq_acordo_parcela = p_chave_aux;
      
      If to_char(w_aditivo.inicio,'mmyyyy') = to_char(w_aditivo.fim,'mmyyyy') Then
         -- O mês incorpora todo o valor do aditivo
         w_valor := w_aditivo.valor_acrescimo;
      Else
         w_dias := w_aditivo.fim - w_aditivo.inicio;
         If to_char(w_parcela.inicio,'dd') = 1 and to_char(w_aditivo.inicio,'dd') > 1 and to_char(w_aditivo.inicio,'mm') = to_char(p_per_ini,'mm') Then
            -- Parcela termina no último dia mas não começa no primeiro dia
            w_ultimo := 30;
            If to_char(last_day(p_per_ini),'dd') < 30 Then 
               w_ultimo := to_char(last_day(p_per_ini),'dd'); 
            End If;
            w_dias_1 := to_date(w_ultimo||'/'||to_char(p_per_ini,'mm/yyyy'),'dd/mm/yyyy') - w_aditivo.inicio + 1 + (30-w_ultimo);
            If w_dias_1 <= 0 Then 
               w_dias_1 := 1; 
            End If;
            w_valor := round(w_dias_1 * (w_aditivo.valor_acrescimo/w_dias),2);
         Elsif to_char(w_parcela.inicio,'dd') = 1 and w_aditivo.fim <> p_per_fim and to_char(w_aditivo.fim,'mm') = to_char(p_per_fim,'mm') Then
            -- Parcela começa no primeiro dia mas não termina no último dia
            w_dias_n := w_aditivo.fim - p_per_ini + 1;
            If w_dias_n > 30 Then w_dias_n := 30; End If;
            w_valor := round(w_dias_n * (w_aditivo.valor_acrescimo/w_dias),2);
         Else
            -- O mês incorpora todo o valor mensal do aditivo
            w_valor := w_aditivo.parcela_acrescida;
         End If;
      End If;

      -- Atualiza o valor da parcela
      update ac_acordo_parcela
         set sq_acordo_aditivo = p_aditivo,
             valor             = valor_inicial + valor_reajuste + valor_excedente + w_valor,
             valor_excedente   = valor_excedente + w_valor
       where sq_acordo_parcela = p_chave_aux;
       
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;