alter procedure sp_getRecurso
   (@p_cliente      int,
    @p_usuario      int,
    @p_chave        int    = null,
    @p_tipo_recurso int    = null,
    @p_gestora      int    = null,
    @p_codigo       varchar(15)  = null,
    @p_nome         varchar(60)  = null,
    @p_ativo        varchar(1)   = null,
    @p_restricao    varchar(15)  = null

	) as
begin

   If @p_restricao is null or @p_restricao = 'ALOCACAO' or @p_restricao = 'VINCULACAO' or @p_restricao = 'EDICAOT' or @p_restricao = 'EDICAOP'  begin
      -- Recupera recursos
         select a.sq_recurso as chave, a.cliente, a.sq_tipo_recurso, a.sq_unidade_medida, a.unidade_gestora, a.nome, a.codigo, a.descricao, 
                a.finalidade, a.disponibilidade_tipo, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                case disponibilidade_tipo when 1 then 'Prazo indefinido, controle apenas do limite di�rio de unidades'
                                          when 2 then 'Prazo definido, com controle do limite de unidades no per�odo e no dia'
                                          when 3 then 'Prazo definido, controle apenas do limite di�rio de unidades'
                end as nm_disponibilidade_tipo,
                null as tp_vinculo,
                null as ch_vinculo,
                b.nome as nm_unidade, b.sigla as sg_unidade,
                b2.sq_cidade, b2.co_uf, b2.sq_pais,
                c.nome as nm_tipo_recurso, c.sigla as sg_tipo_recurso,
                dbo.montanometiporecurso(c.sq_tipo_recurso,'PRIMEIRO') as nm_tipo_recurso_pai,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                coalesce(e.alocacao,0) alocacao,
                coalesce(f.disponivel,0) disponivel,
                dbo.acesso_recurso(a.sq_recurso, @p_usuario) acesso
         from eo_recurso                        a
                left      join co_pessoa          a1 on (a.sq_recurso          = a1.sq_recurso)
                left      join sr_veiculo         a2 on (a.sq_recurso          = a2.sq_recurso)
                inner     join eo_unidade         b  on (a.unidade_gestora     = b.sq_unidade)
                inner     join co_pessoa_endereco b1 on (b.sq_pessoa_endereco  = b1.sq_pessoa_endereco)
                inner     join co_cidade          b2 on (b1.sq_cidade          = b2.sq_cidade)
                inner     join eo_tipo_recurso    c  on (a.sq_tipo_recurso     = c.sq_tipo_recurso)
                inner     join co_unidade_medida  d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                left      join (select x.sq_recurso, sum(y.unidades_solicitadas) alocacao
                                  from siw_solic_recurso                     x
                                       inner join siw_solic_recurso_alocacao y on (x.sq_solic_recurso = y.sq_solic_recurso and
                                                                                   getDate()       between y.inicio and y.fim+1
                                                                                  )
                                group by x.sq_recurso
                               )                  e  on (a.sq_recurso        = e.sq_recurso)
                left      join (select x.sq_recurso, count(y.sq_recurso) disponivel
                                  from eo_recurso                         x
                                       inner join eo_recurso_disponivel   y on (x.sq_recurso   = y.sq_recurso and
                                                                                (1               = x.disponibilidade_tipo or
                                                                                 (1              <> x.disponibilidade_tipo and
                                                                                  getDate() between y.inicio and y.fim+1
                                                                                 )
                                                                                )
                                                                               )
                                       left  join eo_recurso_indisponivel z on (x.sq_recurso   = z.sq_recurso and
                                                                                getDate()   between z.inicio and z.fim+1
                                                                               )
                                 where x.ativo        = 'S'
                                   and z.sq_recurso is null
                                group by x.sq_recurso
                               )                  f  on (a.sq_recurso        = f.sq_recurso)
                left      join (select y.sq_recurso, count(x.sq_menu) as qtd
                                  from eo_recurso_menu              x
                                       inner   join eo_recurso      y on (x.sq_recurso      = y.sq_recurso)
                                 where y.cliente = @p_cliente
                                   and x.sq_menu = coalesce(@p_gestora,x.sq_menu)
                                group by y.sq_recurso
                               )                  g on (a.sq_recurso           = g.sq_recurso)
          where a.cliente        = @p_cliente
            and a1.sq_pessoa     is null
            and a2.sq_veiculo    is null
            and ((@p_chave        is null) or (@p_chave        is not null and a.sq_recurso      = @p_chave))
            and ((@p_tipo_recurso is null) or (@p_tipo_recurso is not null and a.sq_tipo_recurso = @p_tipo_recurso))
            and ((@p_codigo       is null) or (@p_codigo       is not null and a.codigo            = @p_codigo))
            and ((@p_nome         is null) or (@p_nome         is not null and a.nome              = @p_nome))
            and ((@p_ativo        is null) or (@p_ativo        is not null and a.ativo             = @p_ativo))
            and ((@p_restricao    is null  and
                  (@p_gestora     is null or (@p_gestora      is not null and a.unidade_gestora   = @p_gestora))
                 ) or
                 (@p_restricao    is not null and ((charindex('EDICAO',@p_restricao) = 0 and coalesce(g.qtd,0) > 0) or -- Restri��o para trazer apenas os recursos dispon�veis para o servi�o
                                                  (@p_restricao =  'EDICAOT' and dbo.acesso_recurso(a.sq_recurso, @p_usuario) > 0) or
                                                  (@p_restricao =  'EDICAOP' and dbo.acesso_recurso(a.sq_recurso, @p_usuario) = 4)
                                                 )
                 )
                )
         UNION
         select a.sq_recurso as chave, a.cliente, a.sq_tipo_recurso, a.sq_unidade_medida, a.unidade_gestora, a.nome, a.codigo, a.descricao, 
                a.finalidade, a.disponibilidade_tipo, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                case disponibilidade_tipo when 1 then 'Prazo indefinido, controle apenas do limite di�rio de unidades'
                                          when 2 then 'Prazo definido, com controle do limite de unidades no per�odo e no dia'
                                          when 3 then 'Prazo definido, controle apenas do limite di�rio de unidades'
                end as nm_disponibilidade_tipo,
                'Pessoa' as tp_vinculo,
                a1.sq_pessoa as ch_vinculo,
                b.nome as nm_unidade, b.sigla as sg_unidade,
                b2.sq_cidade, b2.co_uf, b2.sq_pais,
                c.nome as nm_tipo_recurso, c.sigla as sg_tipo_recurso,
                dbo.montanometiporecurso(c.sq_tipo_recurso,'PRIMEIRO') as nm_tipo_recurso_pai,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                coalesce(e.alocacao,0) alocacao,
                coalesce(f.disponivel,0) disponivel,
                dbo.acesso_recurso(a.sq_recurso, @p_usuario) acesso
           from eo_recurso                        a
                inner     join co_pessoa          a1 on (a.sq_recurso = a1.sq_recurso)
                inner     join eo_unidade         b  on (a.unidade_gestora     = b.sq_unidade)
                  inner   join co_pessoa_endereco b1 on (b.sq_pessoa_endereco  = b1.sq_pessoa_endereco)
                    inner join co_cidade          b2 on (b1.sq_cidade          = b2.sq_cidade)
                inner     join eo_tipo_recurso    c  on (a.sq_tipo_recurso     = c.sq_tipo_recurso)
                inner     join co_unidade_medida  d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                left      join (select x.sq_recurso, sum(y.unidades_solicitadas) alocacao
                                  from siw_solic_recurso                     x
                                       inner join siw_solic_recurso_alocacao y on (x.sq_solic_recurso = y.sq_solic_recurso and
                                                                                   getDate()       between y.inicio and y.fim+1
                                                                                  )
                                group by x.sq_recurso
                               )                  e  on (a.sq_recurso        = e.sq_recurso)
                left      join (select x.sq_recurso, count(y.sq_recurso) disponivel
                                  from eo_recurso                         x
                                       inner join eo_recurso_disponivel   y on (x.sq_recurso   = y.sq_recurso and
                                                                                (1               = x.disponibilidade_tipo or
                                                                                 (1              <> x.disponibilidade_tipo and
                                                                                  getDate() between y.inicio and y.fim+1
                                                                                 )
                                                                                )
                                                                               )
                                       left  join eo_recurso_indisponivel z on (x.sq_recurso   = z.sq_recurso and
                                                                                getDate()   between z.inicio and z.fim+1
                                                                               )
                                 where x.ativo        = 'S'
                                   and z.sq_recurso is null
                                group by x.sq_recurso
                               )                  f  on (a.sq_recurso        = f.sq_recurso)
                left      join (select y.sq_recurso, count(x.sq_menu) as qtd
                                  from eo_recurso_menu              x
                                       inner   join eo_recurso      y on (x.sq_recurso      = y.sq_recurso)
                                 where y.cliente = @p_cliente
                                   and x.sq_menu = coalesce(@p_gestora,x.sq_menu)
                                group by y.sq_recurso
                               )                  g on (a.sq_recurso           = g.sq_recurso)
          where a.cliente        = @p_cliente
            and ((@p_chave        is null) or (@p_chave        is not null and a.sq_recurso      = @p_chave))
            and ((@p_tipo_recurso is null) or (@p_tipo_recurso is not null and a.sq_tipo_recurso = @p_tipo_recurso))
            and ((@p_codigo       is null) or (@p_codigo       is not null and a.codigo            = @p_codigo))
            and ((@p_nome         is null) or (@p_nome         is not null and a.nome              = @p_nome))
            and ((@p_ativo        is null) or (@p_ativo        is not null and a.ativo             = @p_ativo))
            and ((@p_restricao    is null  and
                  (@p_gestora     is null or (@p_gestora      is not null and a.unidade_gestora   = @p_gestora))
                 ) or
                 (@p_restricao    is not null and ((charindex('EDICAO',@p_restricao) = 0 and coalesce(g.qtd,0) > 0) or -- Restri��o para trazer apenas os recursos dispon�veis para o servi�o
                                                  (@p_restricao =  'EDICAOT' and dbo.acesso_recurso(a.sq_recurso, @p_usuario) > 0) or
                                                  (@p_restricao =  'EDICAOP' and dbo.acesso_recurso(a.sq_recurso, @p_usuario) = 4)
                                                 )
                 )
                )
         UNION
         select  a.sq_recurso as chave, a.cliente, a.sq_tipo_recurso, a.sq_unidade_medida, a.unidade_gestora, a.nome, a.codigo, a.descricao, 
                a.finalidade, a.disponibilidade_tipo, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                case disponibilidade_tipo when 1 then 'Prazo indefinido, controle apenas do limite di�rio de unidades'
                                          when 2 then 'Prazo definido, com controle do limite de unidades no per�odo e no dia'
                                          when 3 then 'Prazo definido, controle apenas do limite di�rio de unidades'
                end as nm_disponibilidade_tipo,
                'Ve�culo' tp_vinculo,
                a2.sq_veiculo ch_vinculo,
                b.nome as nm_unidade, b.sigla as sg_unidade,
                b2.sq_cidade, b2.co_uf, b2.sq_pais,
                c.nome as nm_tipo_recurso, c.sigla as sg_tipo_recurso,
                dbo.montanometiporecurso(c.sq_tipo_recurso,'PRIMEIRO') as nm_tipo_recurso_pai,
                d.nome as nm_unidade_medida, d.sigla as sg_unidade_medida,
                coalesce(e.alocacao,0) alocacao,
                coalesce(f.disponivel,0) disponivel,
                dbo.acesso_recurso(a.sq_recurso, @p_usuario) acesso
           from eo_recurso                        a
                inner     join sr_veiculo         a2 on (a.sq_recurso = a2.sq_recurso)
                inner     join eo_unidade         b  on (a.unidade_gestora     = b.sq_unidade)
                  inner   join co_pessoa_endereco b1 on (b.sq_pessoa_endereco  = b1.sq_pessoa_endereco)
                    inner join co_cidade          b2 on (b1.sq_cidade          = b2.sq_cidade)
                inner     join eo_tipo_recurso    c  on (a.sq_tipo_recurso     = c.sq_tipo_recurso)
                inner     join co_unidade_medida  d  on (a.sq_unidade_medida   = d.sq_unidade_medida)
                left      join (select x.sq_recurso, sum(y.unidades_solicitadas) alocacao
                                  from siw_solic_recurso                     x
                                       inner join siw_solic_recurso_alocacao y on (x.sq_solic_recurso = y.sq_solic_recurso and
                                                                                   getDate()       between y.inicio and y.fim+1
                                                                                  )
                                group by x.sq_recurso
                               )                  e  on (a.sq_recurso        = e.sq_recurso)
                left      join (select x.sq_recurso, count(y.sq_recurso) disponivel
                                  from eo_recurso                         x
                                       inner join eo_recurso_disponivel   y on (x.sq_recurso   = y.sq_recurso and
                                                                                (1               = x.disponibilidade_tipo or
                                                                                 (1              <> x.disponibilidade_tipo and
                                                                                  getDate() between y.inicio and y.fim+1
                                                                                 )
                                                                                )
                                                                               )
                                       left  join eo_recurso_indisponivel z on (x.sq_recurso   = z.sq_recurso and
                                                                                getDate()   between z.inicio and z.fim+1
                                                                               )
                                 where x.ativo        = 'S'
                                   and z.sq_recurso is null
                                group by x.sq_recurso
                               )                  f  on (a.sq_recurso        = f.sq_recurso)
                left      join (select y.sq_recurso, count(x.sq_menu) as qtd
                                  from eo_recurso_menu              x
                                       inner   join eo_recurso      y on (x.sq_recurso      = y.sq_recurso)
                                 where y.cliente = @p_cliente
                                   and x.sq_menu = coalesce(@p_gestora,x.sq_menu)
                                group by y.sq_recurso
                               )                  g on (a.sq_recurso           = g.sq_recurso)
          where a.cliente        = @p_cliente
            and ((@p_chave        is null) or (@p_chave        is not null and a.sq_recurso      = @p_chave))
            and ((@p_tipo_recurso is null) or (@p_tipo_recurso is not null and a.sq_tipo_recurso = @p_tipo_recurso))
            and ((@p_codigo       is null) or (@p_codigo       is not null and a.codigo            = @p_codigo))
            and ((@p_nome         is null) or (@p_nome         is not null and a.nome              = @p_nome))
            and ((@p_ativo        is null) or (@p_ativo        is not null and a.ativo             = @p_ativo))
            and ((@p_restricao    is null  and
                  (@p_gestora     is null or (@p_gestora      is not null and a.unidade_gestora   = @p_gestora))
                 ) or
                 (@p_restricao    is not null and ((charindex('EDICAO',@p_restricao) = 0 and coalesce(g.qtd,0) > 0) or -- Restri��o para trazer apenas os recursos dispon�veis para o servi�o
                                                  (@p_restricao =  'EDICAOT' and dbo.acesso_recurso(a.sq_recurso, @p_usuario) > 0) or
                                                  (@p_restricao =  'EDICAOP' and dbo.acesso_recurso(a.sq_recurso, @p_usuario) = 4)
                                                 )
                 )
                );
   end else if upper(@p_restricao) = 'MENU'  begin
      -- Recupera os servi�os que podem alocar o recurso
      
        select a.sq_menu, a.nome, a.acesso_geral, a.ultimo_nivel, a.tramite, 
               b.sigla sg_modulo, b.nome nm_modulo, c.sq_recurso
          from siw_menu                   a
               inner join siw_modulo      b on (a.sq_modulo    = b.sq_modulo)
               left  join eo_recurso_menu c on (a.sq_menu      = c.sq_menu and
                                                c.sq_recurso = @p_chave
                                               )
         where a.sq_pessoa = @p_cliente
           and a.tramite   = 'S'
           and a.ativo     = 'S'
        order by dbo.acentos(b.nome), dbo.acentos(a.nome);
   end else if upper(@p_restricao) = 'SERVICO'  begin
      -- Recupera os recursos que podem ser vinculados a uma op��o do menu
      
        select a.sq_menu, a.nome, a.acesso_geral, a.ultimo_nivel, a.tramite, 
               b.sigla sg_modulo, b.nome nm_modulo, c.sq_recurso,
               d.sq_tipo_recurso, d.sq_unidade_medida, d.unidade_gestora, d.nome, d.codigo,
               d.descricao, d.finalidade, d.disponibilidade_tipo, d.ativo
          from siw_menu                     a
               inner   join siw_modulo      b on (a.sq_modulo    = b.sq_modulo)
               inner   join eo_recurso_menu c on (a.sq_menu      = c.sq_menu)
                 inner join eo_recurso      d on (c.sq_recurso   = d.sq_recurso)
         where a.sq_pessoa = @p_cliente
           and a.sq_menu   = @p_chave
           and ((@p_ativo        is null) or (@p_ativo        is not null and a.ativo             = @p_ativo))
        order by dbo.dbo.acentos(b.nome), dbo.dbo.acentos(a.nome);
   end else if @p_restricao = 'EXISTE'  begin
      -- Verifica se o nome ou a codigo do recurso j� foi inserida
       
         select count(a.sq_recurso) as existe
           from eo_recurso  a
          where a.cliente     = @p_cliente
            and sq_recurso <> coalesce(@p_chave,0)
            and ((@p_codigo    is null) or (@p_codigo is not null and dbo.acentos(a.codigo) = dbo.acentos(@p_codigo)))
            and ((@p_nome      is null) or (@p_nome   is not null and dbo.acentos(a.nome)   = dbo.acentos(@p_nome)));
   
   end else if @p_restricao = 'VINCULADO' begin
      -- Verifica se o recurso est� vinculado a alguma solicita��o ou a algum menu
                        
         select (a.qtd + b.qtd) as existe
           from (select count(sq_solic_recurso) as qtd from siw_solic_recurso where sq_recurso = @p_chave) a, 
                (select count(sq_menu)            as qtd from eo_recurso_menu  where sq_recurso = @p_chave) b;
          
   End 
end 
