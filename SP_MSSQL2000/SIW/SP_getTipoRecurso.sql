alter procedure dbo.sp_getTipoRecurso
   (@p_cliente   int,
    @p_chave     int   = null,
    @p_chave_pai int   = null,
    @p_nome      varchar(30) = null,
    @p_sigla     varchar(10) = null,
    @p_gestora   int   = null,
    @p_ativo     varchar(1) = null,
    @p_restricao varchar(15) = null
	) as
begin
   If @p_restricao = 'REGISTROS' Begin
      -- Recupera os tipos de recurso existentes
      --open @p_result for 
         select a.sq_tipo_recurso as chave, a.sq_tipo_pai, a.cliente, a.nome,
                a.sigla, a.descricao, a.ativo, a.unidade_gestora,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                b.nome nm_unidade, b.sigla sg_unidade
           from eo_tipo_recurso       a
                inner join eo_unidade b on (a.unidade_gestora = b.sq_unidade)
          where a.cliente            = @p_cliente
            and (@p_chave             is null or (@p_chave   is not null and a.sq_tipo_recurso = @p_chave))
            and (@p_nome              is null or (@p_nome    is not null and a.nome = @p_nome))
            and (@p_gestora           is null or (@p_gestora is not null and a.unidade_gestora = @p_gestora))
            and (@p_sigla             is null or (@p_sigla   is not null and a.sigla = upper(@p_sigla)))
            and (@p_ativo             is null or (@p_ativo   is not null and a.ativo = @p_ativo))
         order by a.nome;
   End Else If upper(@p_restricao) = 'SUBTODOS' Begin
     -- Recupera os tipos aos quais o atual pode ser subordinado
      --open @p_result for
         select a.sq_tipo_recurso chave,a.nome,
                dbo.montanometiporecurso(a.sq_tipo_recurso,'UP') as nome_completo,
                coalesce(b.qtd,0) as qt_recursos
           from eo_tipo_recurso   a
                left  join (select x.sq_tipo_recurso, count(x.sq_recurso) qtd 
                              from eo_recurso x
                            group by x.sq_tipo_recurso
                           )      b on (a.sq_tipo_recurso = b.sq_tipo_recurso)
          where a.cliente = @p_cliente
         order by a.nome;
   End Else If upper(@p_restricao) = 'SUBPARTE' Begin
     -- Se for alteração, não deixa vincular a si mesmo nem a algum filho
      --open @p_result for
         select a.sq_tipo_recurso chave,a.nome,
                dbo.montanometiporecurso(a.sq_tipo_recurso,'UP') as nome_completo,
                coalesce(b.qtd,0) as qt_recursos
           from eo_tipo_recurso   a
                left  join (select x.sq_tipo_recurso, count(x.sq_recurso) qtd 
                              from eo_recurso x
                            group by x.sq_tipo_recurso
                           )      b on (a.sq_tipo_recurso = b.sq_tipo_recurso)
          where a.cliente = @p_cliente
            and a.sq_tipo_recurso not in (select chave from dbo.sp_fgettiporecurso(@p_chave, 'DOWN'))
         order by a.nome;
   End Else If upper(@p_restricao) = 'FOLHA' Begin
     -- Recupera apenas os registros sem filhos
      --open @p_result for
         select a.sq_tipo_recurso as chave, a.sq_tipo_pai, a.nome, a.sigla,
                dbo.dbo.montanometiporecurso(a.sq_tipo_recurso) as nome_completo
           from eo_tipo_recurso a
                left  join (select sq_tipo_pai
                              from eo_tipo_recurso 
                            group by sq_tipo_pai
                           )    b on (a.sq_tipo_recurso = b.sq_tipo_pai)
                left  join (select z.sq_tipo_recurso, count(x.sq_menu) as qtd
                              from eo_recurso_menu              x
                                   inner   join eo_recurso      y on (x.sq_recurso      = y.sq_recurso)
                                     inner join eo_tipo_recurso z on (y.sq_tipo_recurso = z.sq_tipo_recurso)
                             where z.cliente = @p_cliente
                               and x.sq_menu = coalesce(@p_chave_pai,x.sq_menu)
                            group by z.sq_tipo_recurso
                           )    c on (a.sq_tipo_recurso = c.sq_tipo_recurso)
          where a.cliente     = @p_cliente
            and b.sq_tipo_pai is null
            and (@p_chave      is null or (@p_chave     is not null and a.sq_tipo_recurso = @p_chave))
            and (@p_chave_pai  is null or (@p_chave_pai is not null and coalesce(c.qtd,0) > 0))
            and (@p_nome       is null or (@p_nome      is not null and a.nome = @p_nome))
            and (@p_gestora    is null or (@p_gestora   is not null and a.unidade_gestora = @p_gestora))
            and (@p_sigla      is null or (@p_sigla     is not null and a.sigla = upper(@p_sigla)))
            and (@p_ativo      is null or (@p_ativo     is not null and a.ativo = @p_ativo))
            and a.sq_tipo_recurso in (select chave from dbo.sp_fgettiporecurso(@p_chave, 'UP'))

         order by 5;
   End Else If upper(@p_restricao) = 'PAI' Begin
     -- Recupera o plano pai do informado
      --open @p_result for
         select a.sq_plano chave,a.titulo nome, a.inicio, a.fim
           from pe_plano            a
                inner join pe_plano b on (b.sq_plano_pai = a.sq_plano)
          where b.cliente  = @p_cliente
            and b.sq_plano = @p_chave
         order by a.titulo;
   End Else If @p_restricao = 'EXISTE' Begin
      -- Verifica se há outro registro com o mesmo nome ou sigla
      --open @p_result for 
         select a.sq_tipo_recurso as chave, a.cliente, a.nome,
                a.sigla, a.descricao, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from eo_tipo_recurso a
          where a.cliente                = @p_cliente
            and a.sq_tipo_recurso        <> coalesce(@p_chave,0)
            and (@p_nome                  is null or (@p_nome    is not null and dbo.acentos(a.nome) = dbo.acentos(@p_nome)))
            and (@p_gestora               is null or (@p_gestora is not null and a.unidade_gestora = @p_gestora))
            and (@p_sigla                 is null or (@p_sigla   is not null and dbo.acentos(a.sigla) = dbo.acentos(@p_sigla)))
            and (@p_ativo                 is null or (@p_ativo   is not null and a.ativo = @p_ativo))
         order by a.nome;
   End Else If @p_restricao = 'VINCULADO' Begin
      -- Verifica se o registro está vinculado a um recurso
      --open @p_result for 
         select a.sq_tipo_recurso as chave, a.cliente, a.nome,
                a.sigla, a.descricao, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from eo_tipo_recurso                a
                inner join eo_recurso          b on (a.sq_tipo_recurso = b.sq_tipo_recurso)
          where a.cliente                = @p_cliente
            and a.sq_tipo_recurso  = @p_chave
         order by a.nome;
   End Else If @p_restricao is not null Begin
      If upper(@p_restricao) = 'IS NULL' Begin
         --open @p_result for
            select a.sq_tipo_recurso as chave, a.cliente, a.sq_tipo_pai, a.nome, a.sigla, a.descricao, a.ativo, coalesce(b.filho,0) as filho,
                dbo.montanometiporecurso(a.sq_tipo_recurso, 'UP') as nome_completo,
                coalesce(c.qtd,0) as qt_recursos
              from eo_tipo_recurso a
                   left  join (select sq_tipo_pai, count(sq_tipo_recurso) as filho 
                                 from eo_tipo_recurso x 
                                where cliente = @p_cliente 
                               group by sq_tipo_pai
                              ) b on (a.sq_tipo_recurso = b.sq_tipo_pai)
                   left  join (select x.sq_tipo_recurso, count(x.sq_recurso) qtd 
                                 from eo_recurso x
                               group by x.sq_tipo_recurso
                              ) c on (a.sq_tipo_recurso = c.sq_tipo_recurso)
             where a.cliente     = @p_cliente
               and a.sq_tipo_pai is null
               and (@p_nome       is null or (@p_nome    is not null and a.nome   = @p_nome))
               and (@p_gestora    is null or (@p_gestora is not null and a.unidade_gestora = @p_gestora))
               and (@p_ativo      is null or (@p_ativo   is not null and a.ativo = @p_ativo))
            order by a.nome;
      End Else Begin
         --open @p_result for
            select a.sq_tipo_recurso as chave, a.cliente, a.sq_tipo_pai, a.nome, a.sigla, a.descricao, a.ativo, coalesce(b.filho,0) as filho,
                dbo.montanometiporecurso(a.sq_tipo_recurso, 'UP') as nome_completo,
                coalesce(c.qtd,0) as qt_recursos
              from eo_tipo_recurso a
                   left join (select sq_tipo_pai, count(sq_tipo_recurso) as filho 
                                from eo_tipo_recurso x 
                               where cliente = @p_cliente 
                              group by sq_tipo_pai
                             ) b on (a.sq_tipo_recurso = b.sq_tipo_pai)
                   left  join (select x.sq_tipo_recurso, count(x.sq_recurso) qtd 
                                 from eo_recurso x
                               group by x.sq_tipo_recurso
                              ) c on (a.sq_tipo_recurso = c.sq_tipo_recurso)
             where a.cliente     = @p_cliente
               and a.sq_tipo_pai = @p_restricao
               and (@p_nome       is null or (@p_nome    is not null and a.nome   = @p_nome))
               and (@p_gestora    is null or (@p_gestora is not null and a.unidade_gestora = @p_gestora))
               and (@p_ativo      is null or (@p_ativo   is not null and a.ativo = @p_ativo))
            order by a.nome;
      end
   end
end
