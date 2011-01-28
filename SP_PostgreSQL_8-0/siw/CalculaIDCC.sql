create or replace function CalculaIDCC(p_chave numeric, p_data date, p_inicio date )  RETURNS float AS $$
DECLARE
  Result float := 0;
  w_cliente    numeric(18);
  
   c_dados CURSOR (p_cliente numeric) FOR
     select coalesce(previsto.valor,0) as previsto, coalesce(realizado.valor,0) as realizado,
            case when previsto.valor is not null
                 then coalesce(realizado.valor,0)/case when previsto.valor is null or previsto.valor = 0 then 1 else previsto.valor end
                 else 1
            end  as idcc
       from siw_solicitacao w
            inner join (select a.sq_siw_solicitacao, sum(a.valor) as valor
                          from ac_acordo_parcela            a
                               inner   join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                         where a.sq_siw_solicitacao = p_chave
                           and a.vencimento between coalesce(p_inicio, b.inicio) and coalesce(p_data,now())
                        group by a.sq_siw_solicitacao
                       ) previsto on (w.sq_siw_solicitacao = previsto.sq_siw_solicitacao)
            left  join (select a.sq_siw_solicitacao, sum(d.valor) as valor
                          from ac_acordo_parcela              a
                               inner     join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                               inner     join fn_lancamento   c on (c.cliente            = p_cliente and
                                                                    a.sq_acordo_parcela  = c.sq_acordo_parcela and
                                                                    c.sq_acordo_parcela  is not null
                                                                   )
                                 inner   join siw_solicitacao d on (c.sq_siw_solicitacao  = d.sq_siw_solicitacao)
                         where a.sq_siw_solicitacao = p_chave
                           and a.vencimento between coalesce(p_inicio, b.inicio) and coalesce(p_data,now())
                        group by a.sq_siw_solicitacao
                       ) realizado on (w.sq_siw_solicitacao = realizado.sq_siw_solicitacao)
      where w.sq_siw_solicitacao = p_chave;
BEGIN
  -- Recupera o cliente
  select b.sq_pessoa into w_cliente from siw_solicitacao a join siw_menu b on a.sq_menu = b.sq_menu where a.sq_siw_solicitacao = p_chave;
  
  for crec in c_dados (w_cliente) loop 
      if crec.previsto = 0 and crec.realizado > 0
         then Result := -1;
         else Result := (crec.idcc * 100); 
      end if;
  end loop;

  Return Result;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;