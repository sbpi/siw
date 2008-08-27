create function CalculaIDE(
	@p_chave  int, 
	@p_data   datetime = null, 
	@p_inicio datetime = null) 
returns float as
Begin
  Declare @Result float;
  Set     @Result = 0;
  Declare @w_existe int;
  Declare @ide varchar(255);
  
Declare c_dados  cursor for
     select case when previsto.valor is not null
                 then coalesce(realizado.valor,0)/coalesce(previsto.valor,1)
                 else 1
            end  as ide
       from siw_solicitacao a
            left join (select a.sq_siw_solicitacao, sum(a.peso) as valor
                         from pj_projeto_etapa           a
                              inner join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                        where a.sq_siw_solicitacao = @p_chave
                          and a.pacote_trabalho    = 'S'
                          and a.fim_previsto       between coalesce(@p_inicio,b.inicio) and @p_data
                       group by a.sq_siw_solicitacao
                      ) previsto on (a.sq_siw_solicitacao = previsto.sq_siw_solicitacao)
            left join (select a.sq_siw_solicitacao, sum(a.peso) as valor
                         from pj_projeto_etapa a
                              inner join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                        where a.sq_siw_solicitacao = @p_chave
                          and a.pacote_trabalho    = 'S'
                          and a.fim_real           between coalesce(@p_inicio,b.inicio) and @p_data
                       group by a.sq_siw_solicitacao
                      ) realizado on (a.sq_siw_solicitacao = realizado.sq_siw_solicitacao)
      where a.sq_siw_solicitacao = @p_chave;

  -- Verifica se a chave informada existe em siw_solicitacao
  select @w_existe = count(a.sq_siw_solicitacao)  
    from siw_solicitacao       a
         inner join pj_projeto b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
   where a.sq_siw_solicitacao = coalesce(@p_chave,0);
  
  Open c_dados
  

  
  If @w_existe = 0 Begin
     Set @Result = 0;
  End Else Begin
    While @@Fetch_Status = 0
      Begin
        Set @Result = (@ide * 100);
		Fetch next from c_dados into @w_existe
      End
  End
  
  Return coalesce(@Result,0);
end
