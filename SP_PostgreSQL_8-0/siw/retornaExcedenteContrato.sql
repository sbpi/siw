create or replace function retornaExcedenteContrato(p_chave numeric, p_data date )  RETURNS float AS $$
DECLARE
  -- p_chave: chave da tabela AC_ACORDO
  Result   float := 0;
  w_data   date := p_data;
  
   c_dados_old CURSOR FOR
     select case when a.valor_inicial = 0 
                 then 0 
                 else (a.valor_acrescimo / case when a.valor_inicial = 0 then 1 else a.valor_inicial end) * 100
            end as excedente
       from ac_acordo_aditivo a 
      where a.sq_siw_solicitacao = p_chave
        and w_data between a.inicio and a.fim;

   c_dados CURSOR FOR
     select case (b.valor_inicial+coalesce(sum(a.valor_reajuste),0))
                 when 0 then 0 
                 else ((sum(a.valor_acrescimo) / (b.valor_inicial+coalesce(sum(a.valor_reajuste),0))) * 100) 
            end as excedente
       from ac_acordo_aditivo          a
            inner join ac_acordo       b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
      where a.sq_siw_solicitacao = p_chave
     group by b.valor_inicial;
BEGIN
  If p_chave is null Then return 0; End If;
  If p_data  is null Then w_data := now(); End If;
  
  for crec in c_dados loop Result := crec.excedente; end loop;
  
  return(Result);END; $$ LANGUAGE 'PLPGSQL' VOLATILE;