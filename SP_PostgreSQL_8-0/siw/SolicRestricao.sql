create or replace function SolicRestricao(p_chave in numeric, p_chave_aux in numeric) returns varchar as $$
declare
  Result varchar(2);
  c_tipo varchar(2);

  c_restricoes cursor (l_chave numeric, l_chave_aux numeric) for
    select max(c.problema||c.criticidade) as tipo
      from siw_solicitacao                  a
           inner   join siw_restricao       c  on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
             left  join siw_restricao_etapa d  on (c.sq_siw_restricao   = d.sq_siw_restricao and
                                                   d.sq_projeto_etapa   = coalesce(l_chave_aux,d.sq_projeto_etapa)
                                                  )
     where a.sq_siw_solicitacao = l_chave
       and d.sq_projeto_etapa   = coalesce(l_chave_aux,d.sq_projeto_etapa)
       and c.fase_atual         <> 'C';
begin
  open c_restricoes (p_chave, p_chave_aux);
  loop
     fetch c_restricoes into c_tipo;
     If Not Found Then Exit; End If;
     If c_tipo is null  Then Result := 'N';
     Else                    Result := c_tipo;
     End If;
  end loop;
  close c_restricoes;
  
  return(Result);
end; $$ language 'plpgsql' volatile;
