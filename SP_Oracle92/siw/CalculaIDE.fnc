create or replace function CalculaIDE(p_chave in number, p_data in date default null) return float is
  Result float;
  w_existe number(18);
begin
  -- Verifica se a chave informada existe em siw_solicitacao
  select count(sq_siw_solicitacao) into w_existe from siw_solicitacao where sq_siw_solicitacao = coalesce(p_chave,0);
  
  If w_existe = 0 Then
     Result := 0;
  Else
     select sum(b.perc_conclusao*b.peso) / sum(a.perc_conclusao*a.peso)
       into Result
       from pj_projeto_etapa           a
            left join pj_projeto_etapa b on (a.sq_projeto_etapa = b.sq_projeto_etapa and
                                             b.fim_real         < coalesce(p_data,sysdate)
                                            )
      where a.pacote_trabalho    = 'S'
        and a.sq_siw_solicitacao = p_chave
        and a.fim_previsto       <= coalesce(p_data,sysdate)
     group by a.sq_siw_solicitacao;
  End If;
  Return Result;
end CalculaIDE;
/
