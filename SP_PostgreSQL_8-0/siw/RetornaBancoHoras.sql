create or replace function RetornaBancoHoras
      (p_contrato     numeric, 
       p_retorno      numeric, -- 1 ou nulo - 00:00; 2 - minutos
       p_mes_ini      varchar, -- formato AAAAMM
       p_mes_fim      varchar, -- formato AAAAMM
       p_restricao    varchar 
      )  RETURNS varchar AS $$
DECLARE
  Result varchar(10) := '00:00';
  w_qtd  numeric(10);
   c_dados CURSOR FOR
     select coalesce(sum(horario2minutos(null,b.horas_banco)),0) as minutos_banco,
            coalesce(sum(horario2minutos(null,coalesce(b.horas_autorizadas,'00:00'))),0) as minutos_autorizados
       from gp_contrato_colaborador          a
            inner join gp_folha_ponto_mensal b on (a.sq_contrato_colaborador = b.sq_contrato_colaborador)
     where a.sq_contrato_colaborador = p_contrato
       and (p_mes_ini is null or (p_mes_ini is not null and b.mes between p_mes_ini and p_mes_fim));
BEGIN
  select count(*) into w_qtd from gp_contrato_colaborador where sq_contrato_colaborador = p_contrato;
  If w_qtd = 0 Then
     Result := 0;
  Else
     for crec in c_dados loop
         If p_restricao = 'TOTAL' Then
            select coalesce(horario2minutos(null,a.banco_horas_saldo),0)+crec.minutos_autorizados
              into Result
              from gp_contrato_colaborador a
            where a.sq_contrato_colaborador = p_contrato;
         Else
            Result := crec.minutos_autorizados;
         End If;
     end loop;
  End If;

  If coalesce(p_retorno,1) = 1 Then
     Result := minutos2horario(Result);
  Else
     Result := Result;
  End If;

  return(Result);END; $$ LANGUAGE 'PLPGSQL' VOLATILE;