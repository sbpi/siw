create procedure sp_getRecurso_Indisp
   (@p_cliente   int,
    @p_chave_pai int,
    @p_chave     int   = null,
    @p_inicio    datetime     = null,
    @p_fim       datetime     = null,
    @p_restricao varchar(20) = null

   ) as
begin
   If @p_restricao = 'REGISTROS' begin
      -- Recupera o cronograma de indisponibilidade do recurso
       
         select a.sq_recurso_indisponivel as chave, a.sq_recurso as chave_pai, a.inicio, a.fim, a.justificativa,
                c.sq_recurso_disponivel, c.dia_util
           from eo_recurso_indisponivel          a
                inner join eo_recurso            b on (a.sq_recurso = b.sq_recurso)
                left  join eo_recurso_disponivel c on (a.sq_recurso = c.sq_recurso and
                                                       (a.inicio      between coalesce(c.inicio, a.inicio) and coalesce(c.fim, a.fim) or
                                                        a.fim         between coalesce(c.inicio, a.inicio) and coalesce(c.fim, a.fim)
                                                       )
                                                      )
          where b.cliente            = @p_cliente
            and a.sq_recurso         = @p_chave_pai
            and (@p_chave             is null or (@p_chave is not null and a.sq_recurso_indisponivel = @p_chave))
            and (@p_inicio            is null or (@p_inicio is not null and (a.inicio    between @p_inicio and @p_fim or
                                                                           a.fim       between @p_inicio and @p_fim or
                                                                           @p_inicio    between a.inicio and a.fim or
                                                                           @p_fim       between a.inicio and a.fim
                                                                          )
                                                )
                )
         order by a.inicio desc, a.fim desc;
   end else if @p_restricao = 'EXISTE' begin
     -- Retorna registros que se sobrepõe ao período informado
      
         select count(sq_recurso) as existe
           from eo_recurso_indisponivel a
          where a.sq_recurso               =  @p_chave_pai
            and a.sq_recurso_indisponivel  <> coalesce(@p_chave,0)
            and (@p_inicio        is null or (@p_inicio is not null and (a.inicio    between @p_inicio and @p_fim or
                                                                       a.fim       between @p_inicio and @p_fim or
                                                                       @p_inicio    between a.inicio and a.fim or
                                                                       @p_fim       between a.inicio and a.fim
                                                                      )
                                                )
                );
   End 
end 

