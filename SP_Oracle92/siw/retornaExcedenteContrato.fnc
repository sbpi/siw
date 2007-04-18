create or replace function retornaExcedenteContrato(p_chave in number, p_data in date default null) return float is
  -- p_chave: chave da tabela AC_ACORDO
  Result   float := 0;
  w_data   date := p_data;
  
  cursor c_dados is
     select case when a.valor_inicial = 0 
                 then 0 
                 else (a.valor_acrescimo / case when a.valor_inicial = 0 then 1 else a.valor_inicial end) * 100
            end as excedente
       from ac_acordo_aditivo a 
      where a.sq_siw_solicitacao = p_chave
        and w_data between a.inicio and a.fim;
begin
  If p_chave is null Then return 0; End If;
  If p_data  is null Then w_data := sysdate; End If;
  
  for crec in c_dados loop Result := crec.excedente; end loop;
  
  return(Result);
end retornaExcedenteContrato;
/
