alter procedure dbo.SP_GetCronograma
   (@p_chave       int,
    @p_chave_aux   int = null,
    @p_inicio      datetime   = null,
    @p_fim         datetime   = null    
    ) as
begin
   -- Recupera o cronograma da rubrica
   
         select a.sq_rubrica_cronograma, a.inicio, a.fim, a.valor_previsto, a.valor_real
           from pj_rubrica_cronograma a
      where a.sq_projeto_rubrica = @p_chave      
        and ((@p_chave_aux is null) or (@p_chave_aux  is not null and a.sq_rubrica_cronograma = @p_chave_aux))
        and ((@p_inicio    is null) or (@p_inicio       is not null and ((a.inicio  between @p_inicio and @p_fim) or
                                                                       (a.fim     between @p_inicio and @p_fim) or
                                                                       (@p_inicio  between a.inicio and a.fim) or
                                                                       (@p_fim     between a.inicio and a.fim)
                                                                       )
                                       )
            );
end 
