create or replace function dados_ferias(p_chave numeric, p_data date )  RETURNS varchar AS $$
DECLARE
/**********************************************************************************
* Nome      : dados_ferias
* Finalidade: Recuperar informações de uma solicitação de férias
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      : 26/04/2010, 11:40
* Parâmetros:
*    p_chave : chave primária de GP_CONTRATO_COLABORADOR
*    p_data  : opcional. 
*              Se nulo, considera o último P.A. encerrado até a data atual; 
*              caso contrário, encerrado (ou a encerrar) até a data informada
* Retorno: se o contrato não existir ou se estiver encerrado, retorna -1
*          se o contrato não permitir gozo de férias, retorna -2
*          se o contrato existir, retorna string contendo informações sobre ela.
*          A string contém vários pedaços separados por |@|
*          1  - data de admissão
*          2  - data de rescisão contratual
*          3  - data de início do período aquisitivo atual
*          4  - data de término do período aquisitivo atual
*          5  - data inicial para gozo das férias no período aquisitivo
*          6  - data limite para término do gozo de férias no período aquisitivo
*          7  - optante por abono pecuniário (nulo se não informado)
*          8  - número de faltas do colaborador que impactam no direito a férias
*          9  - número de dias de direito de férias
*          10 - saldo dos dias de direito a férias
***********************************************************************************/
  Result         varchar(32767) := null;
  w_cliente      numeric(18);
  w_reg          numeric(18);
  w_admissao     date;
  w_rescisao     date;
  w_trata_ferias varchar(1);
  w_abono        varchar(1) := 'N';
  w_inicio_pa    date;
  w_fim_pa       date;
  w_inicio_gozo  date;
  w_fim_gozo     date;
  w_faltas       numeric(3,1) := 0;
  w_dias_direito numeric(3,1) := 0;
  w_saldo        numeric(3,1) := 0;
  w_dias_gozo    numeric(3,1) := 0;

   c_ferias CURSOR (w_inicio date, w_fim date) FOR
     select a.sq_menu, a.nome, a.sigla, a.p1, a.p2, a.p3, a.p4,
            b.sq_siw_solicitacao,
            c.sigla as sg_tramite,
            d.inicio_data, d.inicio_periodo, d.fim_data, d.fim_periodo, d.gozo_previsto, d.gozo_efetivo, d.abono_pecuniario
       from siw_menu                               a
            inner   join siw_modulo                a2 on (a.sq_modulo           = a2.sq_modulo)
            inner   join siw_solicitacao           b  on (a.sq_menu             = b.sq_menu)
              inner join siw_tramite               c  on (b.sq_siw_tramite      = c.sq_siw_tramite and
                                                          c.sigla               <> 'CA'
                                                         )
              inner join gp_ferias                 d  on (b.sq_siw_solicitacao  = d.sq_siw_solicitacao)
      where d.sq_contrato_colaborador = p_chave
        and d.inicio_aquisitivo       = w_inicio
        and d.fim_aquisitivo          = w_fim
     order by d.inicio_data, d.inicio_periodo, d.fim_data, d.fim_periodo;
BEGIN
  if p_chave is not null then
     -- Verifica se o contrato existe e, se existir, recupera seus dados
     select count(sq_contrato_colaborador) into w_reg from gp_contrato_colaborador where sq_contrato_colaborador = p_chave;
     if w_reg > 0 then
        -- Recupera as datas de admissão, rescisão e se o contrato permite o gozo de férias
        select a.cliente, a.inicio, a.fim, a.trata_ferias into w_cliente, w_admissao, w_rescisao, w_trata_ferias
          from gp_contrato_colaborador a 
         where a.sq_contrato_colaborador = p_chave;

        If w_trata_ferias = 'S' or coalesce(p_data,now()) < w_admissao Then
           w_inicio_pa := w_admissao;

           Loop
             w_fim_pa    := to_date(to_char(w_inicio_pa,'ddmm')||(to_char(w_inicio_pa,'yyyy')+1),'ddmmyyyy')-1;
             -- Define período de gozo relativo ao período aquisitivo
             w_inicio_gozo := w_fim_pa + 1;
             w_fim_gozo    := to_date(to_char(w_inicio_gozo,'ddmm')||(to_char(w_inicio_gozo,'yyyy')+1),'ddmmyyyy')-60;
             
             If p_data is null Then
                -- Se não foi informada uma data, o período aquisitivo em questão é o que abrange a data atual
                If trunc(now()) between w_inicio_gozo and w_fim_gozo or trunc(now()) < w_inicio_gozo Then 
                  Exit; 
                End If;
             Else
                -- Caso contrário, o período aquisitivo em questão é o que abrange a data informada
                If p_data between w_inicio_gozo and w_fim_gozo or p_data < w_inicio_gozo Then Exit; End If;
             End If;
             -- Reconfigura início de novo período aquisitivo
             w_inicio_pa := w_fim_pa + 1;
           End Loop;
           
           -- Verifica o número de faltas no período aquisitivo
           select coalesce(sum(case when a.fim_data    <= w_fim_pa    then a.fim_data    else w_fim_pa    end -
                      case when a.inicio_data >= w_inicio_pa then a.inicio_data else w_inicio_pa end
                      +1),0)
             into w_faltas
             from gp_afastamento                 a
                  inner join gp_tipo_afastamento b on (a.sq_tipo_afastamento = b.sq_tipo_afastamento and
                                                       b.abate_ferias        = 'S'
                                                      )
            where a.sq_contrato_colaborador = p_chave
              and (a.inicio_data between w_inicio_pa and w_fim_pa or
                   a.fim_data    between w_inicio_pa and w_fim_pa
                  );
            
           -- Recupera os dias de direito a férias, em função das faltas
           select a.dias_ferias
             into w_dias_direito
             from gp_ferias_dias a
            where a.cliente = w_cliente
              and w_faltas  between a.faixa_inicio and a.faixa_fim
              and a.ativo   = 'S';
            
           for crec in c_ferias(w_inicio_pa, w_fim_pa) loop
              -- Verifica se o colaborador optou pelo abono pecuniário para o período aquisitivo
              If crec.abono_pecuniario = 'S' Then w_abono := 'S'; End If;
              
              -- Calcula o número de dias de gozo do período aquisitivo
              If crec.sg_tramite <> 'AT' Then
                w_dias_gozo := w_dias_gozo + crec.gozo_previsto;
              Else
                w_dias_gozo := w_dias_gozo + crec.gozo_efetivo;
              End If;
           end loop;
           
           -- Calcula o saldo disponível para gozo de férias no período aquisitivo
           w_saldo := w_dias_direito - w_dias_gozo;

           -- Monta string com os dados de retorno
           Result := to_char(w_admissao,'dd/mm/yyyy')||'|@|'||
                     to_char(w_rescisao,'dd/mm/yyyy')||'|@|'||
                     to_char(w_inicio_pa,'dd/mm/yyyy')||'|@|'||
                     to_char(w_fim_pa,'dd/mm/yyyy')||'|@|'||
                     to_char(w_inicio_gozo,'dd/mm/yyyy')||'|@|'||
                     to_char(w_fim_gozo,'dd/mm/yyyy')||'|@|'||
                     w_abono||'|@|'||
                     w_faltas||'|@|'||
                     w_dias_direito||'|@|'||
                     w_saldo;
        Else
          Result := '-2'; 
        End If; 
     Else
       Result := '-1'; 
     End if;
  end if;
  return(Result);
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;