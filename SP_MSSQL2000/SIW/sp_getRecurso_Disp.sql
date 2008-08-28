alter  procedure sp_getRecurso_Disp
   (@p_cliente   int,
    @p_chave_pai int,
    @p_chave     int   = null,
    @p_inicio    datetime     = null,
    @p_fim       datetime     = null,
    @p_restricao varchar(20)  = null
   ) as
   declare @w_tipo numeric;
begin
   If @p_restricao = 'REGISTROS' begin
      -- Recupera o cronograma de disponibilidade do recurso
       
         select a.sq_recurso_disponivel as chave, a.sq_recurso as chave_pai, a.inicio, a.fim, 
                a.valor, a.unidades, a.limite_diario, a.dia_util,
                case a.dia_util when 'S' then 'Somente dia �til' else 'Qualquer dia' end as nm_dia_util,
                coalesce(c.existe,0) as existe_indisponibilidade
           from eo_recurso_disponivel a
                inner join eo_recurso b on (a.sq_recurso = b.sq_recurso)
                left join (select x.sq_recurso, count(z.sq_recurso_indisponivel) as existe
                             from eo_recurso                         x
                                  inner join eo_recurso_disponivel   y on (x.sq_recurso = y.sq_recurso)
                                  inner join eo_recurso_indisponivel z on (x.sq_recurso = z.sq_recurso and
                                                                           (z.inicio      between y.inicio and y.fim or
                                                                            z.fim         between y.inicio and y.fim
                                                                           )
                                                                          )
                            where x.cliente = @p_cliente
                           group by x.sq_recurso
                          )           c on (a.sq_recurso = c.sq_recurso)
          where b.cliente            = @p_cliente
            and a.sq_recurso         = @p_chave_pai
            and (@p_chave             is null or (@p_chave is not null and a.sq_recurso_disponivel = @p_chave))
            and (@p_inicio            is null or (@p_inicio is not null and (a.inicio    between @p_inicio and @p_fim or
                                                                           a.fim       between @p_inicio and @p_fim or
                                                                           @p_inicio    between a.inicio and a.fim or
                                                                           @p_fim       between a.inicio and a.fim
                                                                          )
                                                )
                )
         order by a.inicio desc, a.fim desc;
   end else if @p_restricao = 'EXISTE' begin
     -- Retorna registros que se sobrep�e ao per�odo informado
      
         select count(sq_recurso) as existe
           from eo_recurso_disponivel a
          where a.sq_recurso              =  @p_chave_pai
            and a.sq_recurso_disponivel  <> coalesce(@p_chave,0)
            and (@p_inicio        is null or (@p_inicio is not null and (a.inicio    between @p_inicio and @p_fim or
                                                                       a.fim       between @p_inicio and @p_fim or
                                                                       @p_inicio    between a.inicio and a.fim or
                                                                       @p_fim       between a.inicio and a.fim
                                                                      )
                                                )
                );
   end else if @p_restricao = 'VINCULADO' begin
      -- Recupera o tipo de disponibilidade para verificar a vincula��o
      select @w_tipo = disponibilidade_tipo   from eo_recurso where sq_recurso = @p_chave_pai;
      
      If @w_tipo = 2 begin
         -- se for recurso tiver controle por per�odo, n�o pode excluir 
         -- se houver registro de indisponibilidade ou de aloca��o no per�odo
                           
            select (a.qtd + b.qtd) as existe
              from (select count(y.sq_recurso_indisponivel) as qtd 
                      from eo_recurso_disponivel              x
                           inner join eo_recurso_indisponivel y on (x.sq_recurso = y.sq_recurso and
                                                                    (y.inicio      between x.inicio and x.fim or
                                                                     y.fim         between x.inicio and x.fim
                                                                    )
                                                                   )
                     where x.sq_recurso = @p_chave_pai
                   ) a, 
                   (select count(y.sq_solic_recurso) as qtd --                   (select count(sq_solic_recurso) as qtd
                      from eo_recurso_disponivel                   x
                           inner   join siw_solic_recurso          y on (x.sq_recurso       = y.sq_recurso)
                             inner join siw_solic_recurso_alocacao z on (y.sq_solic_recurso = z.sq_solic_recurso and
                                                                         (z.inicio            between x.inicio and x.fim or
                                                                          z.fim               between x.inicio and x.fim
                                                                         )
                                                                        )
                     where x.sq_recurso = @p_chave
                   ) b;
      End
   End
end 

