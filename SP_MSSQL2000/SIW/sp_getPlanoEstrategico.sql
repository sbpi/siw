alter procedure dbo.sp_GetPlanoEstrategico
   (@p_cliente   int,
    @p_chave     int          =null,
    @p_chave_pai int          =null,
    @p_titulo    varchar(100) =null,
    @p_inicio    datetime     =null,
    @p_fim       datetime     =null,
    @p_ativo     varchar(1)   =null,
    @p_restricao varchar(20)  =null
   ) as 
begin
   If upper(@p_restricao) = 'ARQUIVOS' begin
     -- Recupera o plano pai do informado
         select a.sq_plano chave,
                b.sq_siw_arquivo chave_aux, b.cliente, b.nome, b.descricao, 
                b.inclusao, b.tamanho, b.tipo, b.caminho
           from pe_plano_arq           a
                inner join siw_arquivo b on (a.sq_siw_arquivo = b.sq_siw_arquivo)
          where a.sq_plano  = @p_chave_pai
            and b.cliente   = @p_cliente
            and ((@p_chave   is null) or (@p_chave is not null and b.sq_siw_arquivo = @p_chave));
   end else if upper(@p_restricao) = 'SUBTODOS' or upper(@p_restricao) = 'SUBHERDA' begin
     -- Recupera os planos estrat�gicos aos quais o atual pode ser subordinado

         select a.sq_plano chave,a.titulo nome, a.codigo_externo,
                coalesce(d.qtd,0) as qt_solic,
                coalesce(e.qtd,0) as qt_menu
           from pe_plano    a
                left join (select x.sq_plano, count(sq_siw_solicitacao) as qtd
                             from pe_plano x 
                                  inner join siw_solicitacao y on (x.sq_plano = y.sq_plano)
                            where cliente = @p_cliente 
                           group by x.sq_plano
                          ) d on (a.sq_plano = d.sq_plano)
                left join (select y.sq_plano, count(x.sq_menu) as qtd
                             from pe_plano_menu       x 
                                  inner join pe_plano y on (x.sq_plano = y.sq_plano)
                            where y.cliente = @p_cliente 
                           group by y.sq_plano
                          ) e on (a.sq_plano = e.sq_plano)
          where a.cliente = @p_cliente
         order by a.titulo;
   end else if upper(@p_restricao) = 'SUBPARTE' begin
     -- Se for altera��o, n�o deixa vincular a si mesmo nem a algum filho
         select a.sq_plano chave,a.titulo nome, a.codigo_externo,
                coalesce(d.qtd,0) as qt_solic,
                coalesce(e.qtd,0) as qt_menu
           from pe_plano    a
                left join (select x.sq_plano, count(sq_siw_solicitacao) as qtd
                             from pe_plano x 
                                  inner join siw_solicitacao y on (x.sq_plano = y.sq_plano)
                            where cliente = @p_cliente 
                           group by x.sq_plano
                          ) d on (a.sq_plano = d.sq_plano)
                left join (select y.sq_plano, count(x.sq_menu) as qtd
                             from pe_plano_menu       x 
                                  inner join pe_plano y on (x.sq_plano = y.sq_plano)
                            where y.cliente = @p_cliente 
                           group by y.sq_plano
                          ) e on (a.sq_plano = e.sq_plano)
          where a.cliente  = @p_cliente
            and a.sq_plano not in (select chave from dbo.sp_fgetPlano(@p_chave,'DOWN'))
         order by a.titulo;
   end else if upper(@p_restricao) = 'IRMAOS' begin
     -- Recupera todos os planos estrat�gicos vinculados ao mesmo pai do plano informado, menos o que foi informado
         select a.sq_plano chave,a.titulo nome, a.inicio, a.fim
           from pe_plano              a
          where a.cliente        =  @p_cliente
            and a.sq_plano_pai =  @p_chave_pai
            and a.sq_plano     <> coalesce(@p_chave,0)
            and (@p_inicio        is null or (@p_inicio is not null and (a.inicio    between @p_inicio and @p_fim or
                                                                       a.fim       between @p_inicio and @p_fim or
                                                                       @p_inicio    between a.inicio and a.fim or
                                                                       @p_fim       between a.inicio and a.fim
                                                                      )
                                                )
                )
         order by a.titulo;
   end else if upper(@p_restricao) = 'MENU' or upper(@p_restricao) = 'MENUVINC' begin
      -- Recupera os servi�os que podem ser vinculados ao plano
        select a.sq_menu, a.nome, a.acesso_geral, a.ultimo_nivel, a.tramite, a.sigla,
               b.sigla as sg_modulo, b.ordem as or_modulo, b.nome as nm_modulo, c.sq_plano, 
               coalesce(d.qtd,0) as qtd
          from siw_menu                      a
               inner   join siw_modulo       b on (a.sq_modulo      = b.sq_modulo)
               left    join pe_plano_menu    c on (a.sq_menu        = c.sq_menu and
                                                   c.sq_plano       = @p_chave
                                                  )
               left    join (select x.sq_plano, x.sq_menu, count(x.sq_siw_solicitacao) as qtd
                               from siw_solicitacao        x
                                    inner join siw_tramite y on (x.sq_siw_tramite = y.sq_siw_tramite and
                                                                 'CA'             <> y.sigla
                                                                )
                              where x.sq_plano is not null
                             group by x.sq_plano, x.sq_menu
                            )                d on (a.sq_menu        = d.sq_menu and
                                                   c.sq_plano       = d.sq_plano
                                                  )
         where a.sq_pessoa = @p_cliente
           and a.tramite   = 'S'
           and (upper(@p_restricao)  = 'MENU' or
                (upper(@p_restricao) = 'MENUVINC' and
                 c.sq_plano         is not null
                )
               )
        order by dbo.acentos(b.nome), dbo.acentos(a.nome);
   end else if @p_restricao = 'OBJETOS' begin
      -- Recupera os objetos ligados a um plano estrat�gico
         select a.sq_plano, a.titulo,  a.codigo_externo,
                c.sq_siw_solicitacao, coalesce(c.codigo_interno,cast(c.sq_siw_solicitacao as varchar)) as codigo_interno, c.titulo,
                d.nome, 
                e.sq_peobjetivo, e.sigla, e.nome
           from pe_plano                                a
                inner     join siw_solicitacao_objetivo b  on (a.sq_plano           = b.sq_plano)
                  inner   join siw_solicitacao          c  on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                    inner join siw_menu                 d  on (c.sq_menu            = d.sq_menu)
                  inner   join pe_objetivo              e  on (b.sq_peobjetivo      = e.sq_peobjetivo)
         where a.cliente = @p_cliente
           and (@p_chave             is null or (@p_chave is not null and a.sq_plano = @p_chave));
   end else if @p_restricao = 'REGISTROS' begin
      -- Recupera os planos estrat�gicos existentes
         select a.sq_plano as chave, a.cliente, a.sq_plano_pai, a.titulo, a.codigo_externo,
                case when c.titulo is not null
                     then c.titulo + ' - ' + b.titulo + ' - '+ a.titulo
                     else case when b.titulo is not null
                               then b.titulo+' - '+ a.titulo
                               else a.titulo
                          end
                end as nome_completo, 
                case when c.titulo is not null
                     then dbo.acentos(c.titulo+' - '+b.titulo+' - '+ a.titulo)
                     else case when b.titulo is not null
                               then dbo.acentos(b.titulo+' - '+ a.titulo)
                               else dbo.acentos(a.titulo)
                          end
                end as ac_nome_completo,
                a.missao, a.valores, a.visao_presente, a.visao_futuro, 
                a.inicio, a.fim, a.ativo,
                coalesce(d.filho,0) as filho
           from pe_plano              a
                left    join pe_plano b on (a.sq_plano_pai = b.sq_plano)
                  left  join pe_plano c on (b.sq_plano_pai = c.sq_plano)
                   left join (select sq_plano_pai, count(sq_plano) as filho 
                                from pe_plano x 
                               where cliente = @p_cliente 
                              group by sq_plano_pai
                             )        d on (a.sq_plano = d.sq_plano_pai)
          where a.cliente            = @p_cliente
            and (@p_chave             is null or (@p_chave is not null and a.sq_plano = @p_chave))
            and (@p_chave_pai         is null or (@p_chave_pai is not null and a.sq_plano_pai = @p_chave_pai))
            and (@p_titulo            is null or (@p_titulo is not null and a.titulo    = @p_titulo))
            and (@p_ativo             is null or (@p_ativo is not null and a.ativo      = @p_ativo))
            and (@p_inicio            is null or (@p_inicio is not null and (a.inicio    between @p_inicio and @p_fim or
                                                                           a.fim       between @p_inicio and @p_fim or
                                                                           @p_inicio    between a.inicio and a.fim or
                                                                           @p_fim       between a.inicio and a.fim
                                                                          )
                                                )
                )
         order by a.titulo;
   end else if @p_restricao is not null begin
      If upper(@p_restricao) = 'IS NULL' begin
            select a.sq_plano as chave, a.cliente, a.sq_plano_pai, a.titulo, a.missao, a.valores, a.visao_presente, a.visao_futuro, 
                   a.inicio, a.fim, a.ativo, a.codigo_externo, coalesce(b.filho,0) as filho, coalesce(c.qtd,0) as qt_objetivo,
                   coalesce(d.qtd,0) as qt_solic
              from pe_plano a
                   left join (select sq_plano_pai, count(sq_plano) as filho 
                                from pe_plano x 
                               where cliente = @p_cliente 
                              group by sq_plano_pai
                             ) b on (a.sq_plano = b.sq_plano_pai)
                   left join (select sq_plano, count(sq_peobjetivo) as qtd
                                from pe_objetivo x 
                               where cliente = @p_cliente 
                              group by sq_plano
                             ) c on (a.sq_plano = c.sq_plano)
                   left join (select x.sq_plano, count(sq_siw_solicitacao) as qtd
                                from pe_plano x 
                                     inner join siw_solicitacao y on (x.sq_plano = y.sq_plano)
                               where cliente = @p_cliente 
                              group by x.sq_plano
                             ) d on (a.sq_plano = d.sq_plano)
             where a.cliente      = @p_cliente
               and a.sq_plano_pai is null
               and (@p_titulo      is null or (@p_titulo is not null and a.titulo    = @p_titulo))
               and (@p_ativo       is null or (@p_ativo  is not null and a.ativo      = @p_ativo))
               and (@p_inicio      is null or (@p_inicio is not null and (a.inicio   between @p_inicio and @p_fim or
                                                                        a.fim      between @p_inicio and @p_fim or
                                                                        @p_inicio   between a.inicio and a.fim or
                                                                        @p_fim      between a.inicio and a.fim
                                                                       )
                                             )
                   )
            order by a.titulo;
      end else begin
            select a.sq_plano as chave, a.cliente, a.sq_plano_pai, a.titulo, a.missao, a.valores, a.visao_presente, a.visao_futuro, 
                   a.inicio, a.fim, a.ativo, a.codigo_externo, coalesce(b.filho,0) as filho, coalesce(c.qtd,0) as qt_objetivo,
                   coalesce(d.qtd,0) as qt_solic
              from pe_plano a
                   left join (select sq_plano_pai, count(sq_plano) as filho 
                                from pe_plano x 
                               where cliente = @p_cliente 
                              group by sq_plano_pai
                             ) b on (a.sq_plano = b.sq_plano_pai)
                   left join (select sq_plano, count(sq_peobjetivo) as qtd
                                from pe_objetivo x 
                               where cliente = @p_cliente 
                              group by sq_plano
                             ) c on (a.sq_plano = c.sq_plano)
                   left join (select x.sq_plano, count(sq_siw_solicitacao) as qtd
                                from pe_plano x 
                                     inner join siw_solicitacao y on (x.sq_plano = y.sq_plano)
                               where cliente = @p_cliente 
                              group by x.sq_plano
                             ) d on (a.sq_plano = d.sq_plano)
             where a.cliente      = @p_cliente
               and a.sq_plano_pai = @p_restricao
               and (@p_titulo      is null or (@p_titulo is not null and a.titulo    = @p_titulo))
               and (@p_ativo       is null or (@p_ativo  is not null and a.ativo     = @p_ativo))
               and (@p_inicio      is null or (@p_inicio is not null and (a.inicio   between @p_inicio and @p_fim or
                                                                        a.fim      between @p_inicio and @p_fim or
                                                                        @p_inicio   between a.inicio and a.fim or
                                                                        @p_fim      between a.inicio and a.fim
                                                                       )
                                             )
                   )
            order by a.titulo;
      End
   End
End
