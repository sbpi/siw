create or replace function CalculaIDE(p_chave in number, p_data in date default null) return float is
  Result float := 0;
  w_existe number(18);
  
  cursor c_dados is
     select coalesce(realizado.valor/coalesce(previsto.valor,1),0) as ide
       from (select a.sq_siw_solicitacao, sum(a.peso) as valor
               from pj_projeto_etapa           a
              where a.sq_siw_solicitacao = p_chave
                and a.pacote_trabalho    = 'S'
                and a.fim_previsto       <= coalesce(p_data,sysdate)
             group by a.sq_siw_solicitacao
            ) previsto,
            (select a.sq_siw_solicitacao, sum(a.peso) as valor
               from pj_projeto_etapa a
              where a.sq_siw_solicitacao = p_chave
                and a.pacote_trabalho    = 'S'
                and a.fim_real           <= coalesce(p_data,sysdate)
             group by a.sq_siw_solicitacao
            ) realizado;
begin
  -- Verifica se a chave informada existe em siw_solicitacao
  select count(a.sq_siw_solicitacao) into w_existe 
    from siw_solicitacao       a
         inner join pj_projeto b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
   where a.sq_siw_solicitacao = coalesce(p_chave,0);
  
  If w_existe = 0 Then
     Result := 0;
  Else
    for crec in c_dados loop Result := (crec.ide * 100); end loop;
  End If;
  Return coalesce(Result,0);
end CalculaIDE;
/
