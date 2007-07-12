create or replace function CalculaIDE(p_chave in numeric, p_data in timestamp, p_inicio in timestamp) returns float as $$
declare
  Result      float := 0;
  w_existe    numeric(18);
  c_ide       float;
  
  c_dados cursor (l_chave numeric, l_data timestamp, l_inicio timestamp)for
     select case when previsto.valor is not null
                 then coalesce(realizado.valor,0)/coalesce(previsto.valor,1)
                 else 1
            end  as ide
       from siw_solicitacao a
            left join (select a.sq_siw_solicitacao, sum(a.peso) as valor
                         from pj_projeto_etapa           a
                              inner join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                        where a.sq_siw_solicitacao = l_chave
                          and a.pacote_trabalho    = 'S'
                          and a.fim_previsto       between coalesce(l_inicio,b.inicio) and coalesce(l_data,now())
                       group by a.sq_siw_solicitacao
                      ) previsto on (a.sq_siw_solicitacao = previsto.sq_siw_solicitacao)
            left join (select a.sq_siw_solicitacao, sum(a.peso) as valor
                         from pj_projeto_etapa a
                              inner join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                        where a.sq_siw_solicitacao = l_chave
                          and a.pacote_trabalho    = 'S'
                          and a.fim_real           between coalesce(l_inicio,b.inicio) and coalesce(l_data,now())
                       group by a.sq_siw_solicitacao
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
        fetch c_dados into c_ide;
        If Not Found Then Exit; End If;
        Result := (c_ide * 100);
    end loop;
    close c_dados;
  End If;
  Return coalesce(Result,0);
end; $$ language 'plpgsql' volatile;
