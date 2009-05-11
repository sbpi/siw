create or replace function CalculaIGCC(p_chave in number) return float is
  Result float := 0;
  w_existe number(18);
  
  cursor c_dados is
     select coalesce(previsto.valor,0) as previsto, coalesce(realizado.valor,0) as realizado,
            case when previsto.valor is not null
                 then coalesce(realizado.valor,0)/case when previsto.valor is null or previsto.valor = 0 then 1 else previsto.valor end
                 else 1
            end  as igcc
       from siw_solicitacao a
            left join (select a.sq_siw_solicitacao, sum(a.valor) as valor
                         from ac_acordo_parcela            a
                              inner   join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                        where b.sq_siw_solicitacao          = p_chave
                       group by a.sq_siw_solicitacao
                      ) previsto on (a.sq_siw_solicitacao = previsto.sq_siw_solicitacao)
            left join (select a.sq_siw_solicitacao, sum(d.valor) as valor
                         from ac_acordo_parcela              a
                              inner     join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                              inner     join fn_lancamento   c on (a.sq_acordo_parcela = c.sq_acordo_parcela)
                                inner   join siw_solicitacao d on (c.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                                  inner join siw_tramite     e on (d.sq_siw_tramite      = e.sq_siw_tramite and
                                                                   e.sigla               <> 'CA'
                                                                  )
                        where a.sq_siw_solicitacao          = p_chave
                       group by a.sq_siw_solicitacao
                      ) realizado on (a.sq_siw_solicitacao = realizado.sq_siw_solicitacao)
      where a.sq_siw_solicitacao = p_chave;
begin
  -- Verifica se a chave informada existe em siw_solicitacao
  select count(a.sq_siw_solicitacao) into w_existe 
    from siw_solicitacao              a
         inner join ac_acordo_parcela b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
   where a.sq_siw_solicitacao = coalesce(p_chave,0);
  
  If w_existe = 0 Then
     Result := 0;
  Else
    for crec in c_dados loop Result := (crec.igcc * 100); end loop;
  End If;
  Return Result;
end CalculaIGCC;
/
