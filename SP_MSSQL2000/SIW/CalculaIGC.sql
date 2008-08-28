alter function CalculaIGC(@p_chave int) returns float as
Begin
  Declare @Result float;
  Set @Result = 0;
  Declare @w_existe int;
  Declare @igc float;
  
  Declare c_dados cursor for
     select coalesce(case when sum(valor_previsto) = 0 then -1 else sum(valor_real)/sum(valor_previsto) end,0) as igc
       from siw_solicitacao                    a
            inner   join pj_rubrica            b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao and
                                                     b.ativo              = 'S'
                                                    )
              inner join pj_rubrica_cronograma c on (b.sq_projeto_rubrica = c.sq_projeto_rubrica)
      where a.sq_siw_solicitacao = @p_chave;

  -- Verifica se a chave informada existe em siw_solicitacao
  select @w_existe = count(a.sq_siw_solicitacao)
    from siw_solicitacao       a
         inner join pj_projeto b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
   where a.sq_siw_solicitacao = coalesce(@p_chave,0);
  
  If @w_existe = 0 Set @Result = 0;
  Else Begin
    Open c_dados
    Fetch Next from c_dados into @igc
    While @@Fetch_Status = 0 Begin
       Set @Result = (@igc * 100.0);
	Fetch next from c_dados into @igc
    End
    Close c_dados
    Deallocate c_dados
  End

  Return @Result;
end
