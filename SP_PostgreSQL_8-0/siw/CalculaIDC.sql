create or replace function CalculaIDC(p_chave in numeric, p_data in timestamp DEFAULT NULL, p_inicio in timestamp DEFAULT NULL) returns float as $$
declare
  Result      float := 0;
  w_existe    numeric(18);
  c_previsto  numeric(18);
  c_realizado numeric(18);
  c_idc       float;
  
  c_dados cursor (l_chave numeric, l_data timestamp, l_inicio timestamp)for
     select coalesce(previsto.valor,0) as previsto, coalesce(realizado.valor,0) as realizado,
            case when previsto.valor is not null
                 then coalesce(realizado.valor,0)/case when previsto.valor is null or previsto.valor = 0 then 1 else previsto.valor end
                 else 1
            end  as idc
       from siw_solicitacao a
            left join (select c.sq_siw_solicitacao, sum(a.valor_previsto) as valor
                         from pj_rubrica_cronograma        a
                              inner   join pj_rubrica      b on (a.sq_projeto_rubrica = b.sq_projeto_rubrica)
                                inner join siw_solicitacao c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                        where c.sq_siw_solicitacao          = l_chave
                          and a.fim                         < coalesce(l_data,now())
                          and b.ativo                       = 'S'
                          and (a.inicio                     between coalesce(l_inicio,c.inicio) and coalesce(l_data,now()) or
                               coalesce(l_inicio,c.inicio) between a.inicio                     and a.fim
                              )
                       group by c.sq_siw_solicitacao
                      ) previsto on (a.sq_siw_solicitacao = previsto.sq_siw_solicitacao)
            left join (select c.sq_siw_solicitacao, sum(a.valor_real) as valor
                         from pj_rubrica_cronograma        a
                              inner   join pj_rubrica      b on (a.sq_projeto_rubrica = b.sq_projeto_rubrica)
                                inner join siw_solicitacao c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                        where c.sq_siw_solicitacao          = l_chave
                          and a.fim                         < coalesce(l_data,now())
                          and b.ativo                       = 'S'
                          and (a.inicio                     between coalesce(l_inicio,c.inicio) and coalesce(l_data,now()) or
                               coalesce(p_inicio,c.inicio) between a.inicio                     and a.fim
                              )
                       group by c.sq_siw_solicitacao
                      ) realizado on (a.sq_siw_solicitacao = realizado.sq_siw_solicitacao)
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
    open c_dados (p_chave, p_data, p_inicio);
    loop 
        fetch c_dados into c_previsto, c_realizado, c_idc;
        If Not Found Then Exit; End If;
        if c_previsto = 0 and c_realizado > 0
           then Result := -1;
           else Result := (c_idc * 100); 
        end if;
    end loop;
    close c_dados;
  End If;
  Return Result;
end; $$ language 'plpgsql' volatile;
