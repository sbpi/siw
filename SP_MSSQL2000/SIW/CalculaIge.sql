alter function dbo.CalculaIGE(@p_chave int) returns float as
Begin
  Declare @Result   float;
  Declare @w_existe int;

  -- Verifica se a chave informada existe em siw_solicitacao
  select @w_existe = count(sq_siw_solicitacao) from siw_solicitacao where sq_siw_solicitacao = coalesce(@p_chave,0);
  
  If @w_existe = 0 Set @Result = 0;
  Else Begin
     select @Result = coalesce(sum(a.perc_conclusao*a.peso)/(case sum(a.peso) when 0 then 1 else sum(a.peso) end),0)
       from pj_projeto_etapa            a
      where a.sq_etapa_pai is null
        and a.sq_siw_solicitacao = @p_chave;
  End
  
  return(@Result);
end