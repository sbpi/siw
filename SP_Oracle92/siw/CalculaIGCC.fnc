create or replace function CalculaIGCC(p_chave in number) return float is
  Result float := 0;
  w_cliente number(18);
  
  cursor c_dados (p_cliente in number) is
     select coalesce(previsto.valor,0) as previsto, coalesce(realizado.valor,0) as realizado,
            case when previsto.valor is not null
                 then coalesce(realizado.valor,0)/case when previsto.valor is null or previsto.valor = 0 then 1 else previsto.valor end
                 else 1
            end  as igcc
       from siw_solicitacao w
            inner  join (select a.sq_siw_solicitacao, sum(b.valor) as valor
                          from siw_solicitacao                a
                               inner   join ac_acordo_parcela b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                         where a.sq_siw_solicitacao          = p_chave
                        group by a.sq_siw_solicitacao
                       ) previsto on (w.sq_siw_solicitacao = previsto.sq_siw_solicitacao)
            left  join (select a.sq_siw_solicitacao, sum(d.valor) as valor
                          from siw_solicitacao                  a
                               inner     join ac_acordo_parcela b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                                 inner   join fn_lancamento     c on (c.cliente            = p_cliente and
                                                                      b.sq_acordo_parcela  = c.sq_acordo_parcela and
                                                                      c.sq_acordo_parcela  is not null
                                                                     )
                                   inner join siw_solicitacao   d on (c.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                                   inner join siw_tramite       e on (d.sq_siw_tramite      = e.sq_siw_tramite
                                                                     )
                         where a.sq_siw_solicitacao = p_chave
                        group by a.sq_siw_solicitacao
                       ) realizado on (w.sq_siw_solicitacao = realizado.sq_siw_solicitacao)
      where w.sq_siw_solicitacao = p_chave;
begin
  -- Recupera o cliente
  select b.sq_pessoa into w_cliente from siw_solicitacao a join siw_menu b on a.sq_menu = b.sq_menu where a.sq_siw_solicitacao = p_chave;
  
  for crec in c_dados (w_cliente) loop Result := (crec.igcc * 100); end loop;
  Return Result;
end CalculaIGCC;
/
