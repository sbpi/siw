alter function CalculaIDC(
	@p_chave  int, 
	@p_data   datetime = null, 
	@p_inicio datetime = null
   ) returns float as

Begin

  Declare @w_existe  int;
  Declare @previsto  float;
  Declare @realizado float;
  Declare @idc       float;
  Declare @Result    float;
  Set @Result = 0;
   
  
  Declare c_dados cursor for
     select coalesce(previsto.valor,0) as previsto, coalesce(realizado.valor,0) as realizado,
            case when previsto.valor is not null
                 then coalesce(realizado.valor,0)/case when previsto.valor is null or previsto.valor = 0 then 1 else previsto.valor end
                 else 1
            end as idc
       from siw_solicitacao a
            left join (select c.sq_siw_solicitacao, sum(a.valor_previsto) as valor
                         from pj_rubrica_cronograma        a
                              inner   join pj_rubrica      b on (a.sq_projeto_rubrica = b.sq_projeto_rubrica)
                                inner join siw_solicitacao c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                        where c.sq_siw_solicitacao          = @p_chave
                          and a.fim                         < @p_data
                          and b.ativo                       = 'S'
                          and (a.inicio                     between coalesce(@p_inicio,c.inicio) and @p_data or
                               coalesce(@p_inicio,c.inicio) between a.inicio                     and a.fim
                              )
                       group by c.sq_siw_solicitacao
                      ) previsto on (a.sq_siw_solicitacao = previsto.sq_siw_solicitacao)
            left join (select c.sq_siw_solicitacao, sum(a.valor_real) as valor
                         from pj_rubrica_cronograma        a
                              inner   join pj_rubrica      b on (a.sq_projeto_rubrica = b.sq_projeto_rubrica)
                                inner join siw_solicitacao c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                        where c.sq_siw_solicitacao          = @p_chave
                          and a.fim                         < @p_data
                          and b.ativo                       = 'S'
                          and (a.inicio                     between coalesce(@p_inicio,c.inicio) and @p_data or
                               coalesce(@p_inicio,c.inicio) between a.inicio                     and a.fim
                              )
                       group by c.sq_siw_solicitacao
                      ) realizado on (a.sq_siw_solicitacao = realizado.sq_siw_solicitacao)
      where a.sq_siw_solicitacao = @p_chave;

  -- Verifica se a chave informada existe em siw_solicitacao
  select @w_existe =  count(a.sq_siw_solicitacao)  
    from siw_solicitacao       a
         inner join pj_projeto b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
   where a.sq_siw_solicitacao = coalesce(@p_chave,0);
  
  If @w_existe = 0 Set @Result = 0;
  Else Begin
    Open c_dados
    Fetch Next from c_dados into @previsto, @realizado, @idc
    While @@Fetch_Status = 0 Begin
      If @previsto = 0 and @realizado > 0 Set @Result = -1.0;
      Else                                Set @Result = (@idc * 100.0); 
      Fetch Next from c_dados into @previsto, @realizado, @idc
    End
    Close c_dados
    Deallocate c_dados
  End
  Return @Result;
end

