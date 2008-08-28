alter function dbo.SolicRestricao(@p_chave int, @p_chave_aux int = null) returns varchar(255) as
begin
  Declare @Result varchar(2);
  Declare @w_tipo varchar(255);
  Declare c_restricoes cursor for
    select max(c.problema+c.criticidade) as tipo
      from siw_solicitacao                  a
           inner   join siw_restricao       c  on (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
             left  join siw_restricao_etapa d  on (c.sq_siw_restricao   = d.sq_siw_restricao and
                                                   d.sq_projeto_etapa   = coalesce(@p_chave_aux,d.sq_projeto_etapa)
                                                  )
     where a.sq_siw_solicitacao = @p_chave
       and d.sq_projeto_etapa   = coalesce(@p_chave_aux,d.sq_projeto_etapa)
       and c.fase_atual         <> 'C';

  Open c_restricoes
  Fetch Next from c_restricoes into @w_tipo
  While @@Fetch_Status = 0 Begin
     If @w_tipo is null  Set @Result = 'N';
     Else                Set @Result = @w_tipo;
     Fetch Next from c_restricoes into @w_tipo
  End
  Close c_restricoes
  Deallocate c_restricoes
  
  return(@Result);
end
