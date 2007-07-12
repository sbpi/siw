create or replace function CalculaIGC(p_chave in numeric) returns float as $$
declare
  Result float := 0;
  w_existe numeric(18);
  c_igc       float;
  
  c_dados cursor (l_chave numeric)for
     select coalesce(case when sum(valor_previsto) = 0 then -1 else sum(valor_real)/sum(valor_previsto) end,0) as igc
       from siw_solicitacao                    a
            inner   join pj_rubrica            b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao and
                                                     b.ativo              = 'S'
                                                    )
              inner join pj_rubrica_cronograma c on (b.sq_projeto_rubrica = c.sq_projeto_rubrica)
      where a.sq_siw_solicitacao = l_chave;
begin
  -- Verifica se a chave informada existe em siw_solicitacao
  select count(a.sq_siw_solicitacao) into w_existe 
    from siw_solicitacao       a
         inner join pj_projeto b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
   where a.sq_siw_solicitacao = coalesce(p_chave,0);
  
  If w_existe = 0 Then
     Result := 0;
  Else
    open c_dados (p_chave);
    loop 
        fetch c_dados into c_igc;
        If Not Found Then Exit; End If;
        Result := (c_igc * 100); 
    end loop;
    close c_dados;
  End If;
  Return Result;
end; $$ language 'plpgsql' volatile;
