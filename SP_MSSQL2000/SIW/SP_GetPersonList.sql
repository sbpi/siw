alter procedure dbo.SP_GetPersonList
   (@p_cliente    int,
    @p_chave      int   = null,
    @p_restricao  varchar(20) = null,
    @p_nome       varchar(60) = null,
    @p_sg_unidade varchar(20) = null,
    @p_codigo     int = null,
    @p_filhos     varchar(1) = null
	) as

Declare @l_item   varchar(18);
Declare @l_tipo   varchar(200);
Set     @l_tipo = @p_restricao + ','
Declare @x_tipo   varchar(200);
Set     @x_tipo = '';
begin
   If @p_restricao = 'PESSOA' or @p_restricao = 'NOVOUSO' Begin
      -- Recupera as pessoas da organiza��o
         select a.sq_pessoa, coalesce(b.cpf, c.username) as cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
                c.username,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                             a
                 left outer    join co_pessoa_fisica  b on (a.sq_pessoa      = b.sq_pessoa)
                 left outer    join sg_autenticacao   c on (a.sq_pessoa      = c.sq_pessoa) 
                    left outer join eo_unidade        d on (c.sq_unidade     = d.sq_unidade)
                    left outer join eo_localizacao    e on (c.sq_localizacao = e.sq_localizacao)
          where a.sq_pessoa_pai = @p_cliente
            and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or    a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
            and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(d.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))
            and (@p_restricao  <> 'NOVOUSO' or (@p_restricao = 'NOVOUSO' and c.username is null))
         order by a.nome_indice;
   End Else If @p_restricao = 'TODOS' Begin
      -- Recupera todas as pessoas do cadastro da organiza��o, f�sicas e jur�dicas
         select a.sq_pessoa, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
                b.codigo,
                c.username, c.ativo usuario,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                              a
                left outer join (select x.sq_pessoa, 
                                        case when y.sq_pessoa is not null 
                                             then y.cpf
                                             else case when z.sq_pessoa is not null
                                                       then z.cnpj
                                                       else null
                                                  end
                                        end codigo
                                   from co_pessoa                          x
                                        left outer join co_pessoa_fisica   y on (x.sq_pessoa = y.sq_pessoa)
                                        left outer join co_pessoa_juridica z on (x.sq_pessoa = z.sq_pessoa)
                                )                      b on (a.sq_pessoa      = b.sq_pessoa)
                 left outer    join sg_autenticacao    c on (a.sq_pessoa      = c.sq_pessoa) 
                    left outer join eo_unidade         d on (c.sq_unidade     = d.sq_unidade)
                    left outer join eo_localizacao     e on (c.sq_localizacao = e.sq_localizacao)
          where (a.sq_pessoa = @p_cliente or a.sq_pessoa_pai = @p_cliente)
            and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or    a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
            and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(d.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))
            and (@p_codigo     is null or (@p_codigo     is not null and b.codigo = @p_codigo))
         order by a.nome_indice;
   End Else If @p_restricao = 'TIPOPESSOA' Begin
      -- Recupera todas as pessoas do cadastro da organiza��o, f�sicas e jur�dicas depEndndo do tipo de pessoa
         select a.sq_pessoa, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
                b.codigo,
                c.username, c.ativo usuario,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                              a
                left outer join (select x.sq_pessoa, 
                                        case when y.sq_pessoa is not null 
                                             then y.cpf
                                             else case when z.sq_pessoa is not null
                                                       then z.cnpj
                                                       else null
                                                  end
                                        end codigo
                                   from co_pessoa                          x
                                        left outer join co_pessoa_fisica   y on (x.sq_pessoa = y.sq_pessoa)
                                        left outer join co_pessoa_juridica z on (x.sq_pessoa = z.sq_pessoa)
                                )                      b on (a.sq_pessoa      = b.sq_pessoa)
                 left outer    join sg_autenticacao    c on (a.sq_pessoa      = c.sq_pessoa) 
                    left outer join eo_unidade         d on (c.sq_unidade     = d.sq_unidade)
                    left outer join eo_localizacao     e on (c.sq_localizacao = e.sq_localizacao)
          where (a.sq_pessoa = @p_cliente or a.sq_pessoa_pai = @p_cliente)
            and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or    a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
            and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(d.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))
            and (@p_codigo     is null or (@p_codigo     is not null and b.codigo = @p_codigo))
            and (@p_filhos     is null or (@p_filhos     is not null and a.sq_tipo_pessoa = @p_filhos))
         order by a.nome_indice;         
   End Else If @p_restricao = 'INTERNOS' Begin
      -- Recupera as pessoas internas � organiza��o
        select a.sq_pessoa, b.cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
               c.username,
               f.sigla sg_unidade, f.nome nm_unidade, g.nome nm_local
          from co_pessoa                               a
                left outer     join  co_pessoa_fisica  b on (a.sq_pessoa      = b.sq_pessoa)
                left outer     join  sg_autenticacao   c on (a.sq_pessoa      = c.sq_pessoa)
                    left outer join eo_unidade         f on (c.sq_unidade     = f.sq_unidade)
                    left outer join eo_localizacao     g on (c.sq_localizacao = g.sq_localizacao),
               co_tipo_vinculo  d,
               co_tipo_pessoa   e
         where a.sq_tipo_vinculo = d.sq_tipo_vinculo
           and d.interno         = 'S'
           and a.sq_tipo_pessoa  = e.sq_tipo_pessoa
           and e.ativo           = 'S'
           and e.nome            = 'F�sica'
           and a.sq_pessoa_pai   = @p_cliente 
           and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or   a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
           and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(f.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))           
      order by a.nome_indice;
   End Else If @p_restricao = 'USUARIOS' Begin
      -- Recupera os usu�rios do sistema
        select a.sq_pessoa, b.cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
               c.username,
               f.sigla sg_unidade, f.nome nm_unidade, g.nome nm_local
          from co_pessoa                           a
                left outer join  co_pessoa_fisica  b on (a.sq_pessoa       = b.sq_pessoa)
                inner      join  sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa)
                  inner    join  eo_unidade        f on (c.sq_unidade      = f.sq_unidade)
                  inner    join  eo_localizacao    g on (c.sq_localizacao  = g.sq_localizacao)
                inner      join co_tipo_vinculo    d on (a.sq_tipo_vinculo = d.sq_tipo_vinculo)
                inner      join co_tipo_pessoa     e on (a.sq_tipo_pessoa  = e.sq_tipo_pessoa)
         where c.ativo          = 'S'
           and d.interno        = 'S'
           and e.ativo          = 'S'
           and e.nome           = 'F�sica'
           and a.sq_pessoa_pai  = @p_cliente 
           and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or   a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
           and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(f.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))           
      order by a.nome_indice;
   End Else If @p_restricao = 'INTERES' Begin
      -- Recupera os usu�rios do sistema
        select a.sq_pessoa, b.cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
               c.username,
               f.sigla sg_unidade, f.nome nm_unidade, g.nome nm_local
          from co_pessoa                                    a
                left outer join co_pessoa_fisica            b on (a.sq_pessoa          = b.sq_pessoa)
                inner      join sg_autenticacao             c on (a.sq_pessoa          = c.sq_pessoa)
                  inner    join eo_unidade                  f on (c.sq_unidade         = f.sq_unidade)
                  inner    join eo_localizacao              g on (c.sq_localizacao     = g.sq_localizacao)
                inner      join co_tipo_vinculo             d on (a.sq_tipo_vinculo    = d.sq_tipo_vinculo)
                inner      join co_tipo_pessoa              e on (a.sq_tipo_pessoa     = e.sq_tipo_pessoa)
                left       join pj_projeto_interes          h on (a.sq_pessoa          = h.sq_pessoa and
                                                                  h.sq_siw_solicitacao = @p_chave
                                                                 )
                left       join gd_demanda_interes          i on (a.sq_pessoa          = i.sq_pessoa and
                                                                  i.sq_siw_solicitacao = @p_chave
                                                                 )
                left       join siw_solicitacao_interessado j on (a.sq_pessoa          = j.sq_pessoa and
                                                                  j.sq_siw_solicitacao = @p_chave
                                                                 )
         where c.ativo          = 'S'
           and d.interno        = 'S'
           and e.ativo          = 'S'
           and e.nome           = 'F�sica'
           and h.sq_pessoa      is null
           and i.sq_pessoa      is null
           and j.sq_pessoa      is null
           and a.sq_pessoa_pai  = @p_cliente 
           and (@p_nome       is null or (@p_nome       is not null and (a.nome_indice       like '%' + upper(dbo.acentos(@p_nome)) + '%' or
                                                                       a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%'
                                                                      )
                                        )
               )
           and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(f.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))           
      order by a.nome_indice;
   End Else If @p_restricao = 'TTCENTRAL' Begin
      -- Recupera as pessoas vinculadas a uma central telef�nica
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                             a
                 left outer    join sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa) 
                    left outer join eo_unidade        d on (c.sq_unidade      = d.sq_unidade)
                    left outer join eo_localizacao    e on (c.sq_localizacao  = e.sq_localizacao)
                 inner         join tt_usuario        b on (a.sq_pessoa       = b.usuario)
                    inner      join tt_central        f on (b.sq_central_fone = f.sq_central_fone and
                                                            f.sq_central_fone = @p_chave)
          where a.sq_pessoa_pai   = @p_cliente
            and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or    a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
            and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(d.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))          
         order by a.nome_resumido_ind;
   End Else If @p_restricao = 'TTTRANSFERE' Begin
      -- Recupera as pessoas vinculadas a uma central telef�nica
         select distinct a.sq_pessoa, a.nome_resumido, a.nome, a.nome_resumido_ind,
                f.sigla sg_unidade, f.nome nm_unidade, g.nome nm_local
           from co_pessoa                      a
                inner    join tt_usuario       b on (a.sq_pessoa             = b.usuario)
                   inner join tt_central       c on (b.sq_central_fone       = c.sq_central_fone)
                   inner join tt_ramal_usuario d on (b.sq_usuario_central    = d.sq_usuario_central)
                inner    join sg_autenticacao  e on (a.sq_pessoa             = e.sq_pessoa)
                   inner join eo_unidade       f on (e.sq_unidade            = f.sq_unidade)
                   inner join eo_localizacao   g on (e.sq_localizacao        = g.sq_localizacao)
          where a.sq_pessoa_pai         = @p_cliente
            and c.sq_central_fone       = @p_chave
            and d.fim                   is null
            and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or    a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
            and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(f.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))            
         order by a.nome_resumido_ind;
   End Else If @p_restricao = 'TTUSUCENTRAL' Begin
      -- Recupera os usu�rios do sistema que ainda n�o est�o vinculados � central informada
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                             a
                 inner         join sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa) 
                    inner      join eo_unidade        d on (c.sq_unidade      = d.sq_unidade)
                    inner      join eo_localizacao    e on (c.sq_localizacao  = e.sq_localizacao)
          where a.sq_pessoa_pai   = @p_cliente
            and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or    a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
            and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(d.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))          
            and not exists(
			select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
            from co_pessoa                             a
                 left outer    join sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa) 
                 left outer    join eo_unidade        d on (c.sq_unidade      = d.sq_unidade)
                 left outer    join eo_localizacao    e on (c.sq_localizacao  = e.sq_localizacao)
                 inner         join tt_usuario        b on (a.sq_pessoa       = b.usuario)
                 inner         join tt_central        f on (b.sq_central_fone = f.sq_central_fone and
                                                            f.sq_central_fone = @p_chave)
            where a.sq_pessoa_pai   = @p_cliente
            and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or    a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
            and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(d.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))          )
          order by nome_resumido_ind
          
   End Else If @p_restricao = 'TTUSURAMAL' Begin
      -- Recupera os usu�rios do sistema que ainda n�o est�o vinculados � central informada
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                c.ativo,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                             a
                 inner         join sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa) 
                    inner      join eo_unidade        d on (c.sq_unidade      = d.sq_unidade)
                    inner      join eo_localizacao    e on (c.sq_localizacao  = e.sq_localizacao)
                inner          join tt_usuario        f on (a.sq_pessoa       = f.usuario)
          where a.sq_pessoa_pai   = @p_cliente
            and c.ativo           = 'S'
            and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or    a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
            and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(d.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))          
            and not exists (
         select a.sq_pessoa, a.nome_resumido, a.nome_resumido_ind,
                c.ativo,
                d.sigla sg_unidade, d.nome nm_unidade, e.nome nm_local
           from co_pessoa                             a
                 left outer    join sg_autenticacao   c on (a.sq_pessoa          = c.sq_pessoa) 
                    left outer join eo_unidade        d on (c.sq_unidade         = d.sq_unidade)
                    left outer join eo_localizacao    e on (c.sq_localizacao     = e.sq_localizacao)
                 inner         join tt_usuario        b on (a.sq_pessoa          = b.usuario)
                    inner      join tt_ramal_usuario  f on (b.sq_usuario_central = f.sq_usuario_central and
                                                            f.sq_ramal           = @p_chave and
                                                            f.fim                is null)
          where a.sq_pessoa_pai   = @p_cliente
            and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or    a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
            and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(d.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))          
         )
         order by nome_resumido_ind;
   End Else If @p_restricao = 'PDUSUARIO' Begin
      -- Recupera os usu�rios do sistema que estiverem cadastrados na PD_UNIDADE
        select a.sq_pessoa chave, a.nome_resumido, a.nome_resumido_ind,
               d.sq_unidade, d.sigla sg_unidade, d.nome nm_unidade, 
               d.sigla + '(' + e.nome + ')' nm_local,
               e.ramal
          from co_pessoa                    a
               left    join sg_autenticacao c on (a.sq_pessoa       = c.sq_pessoa)
                 left  join  eo_unidade     d on (c.sq_unidade      = d.sq_unidade)
                 left  join eo_localizacao  e on (c.sq_localizacao  = e.sq_localizacao)
               inner   join pd_usuario      b on (a.sq_pessoa       = b.sq_pessoa)
          where a.sq_pessoa_pai   = @p_cliente
            and (@p_chave is null or (@p_chave is not null and a.sq_pessoa = @p_chave));
   End Else If @p_restricao = 'CLUSUARIO' Begin
      -- Recupera os usu�rios do sistema que estiverem cadastrados na PD_UNIDADE
        select a.sq_pessoa chave, a.nome_resumido, a.nome_resumido_ind,
               d.sq_unidade, d.sigla sg_unidade, d.nome nm_unidade, 
               d.sigla + '(' + e.nome + ')' nm_local,
               e.ramal
          from co_pessoa                    a
               left    join sg_autenticacao c on (a.sq_pessoa       = c.sq_pessoa)
                 left  join  eo_unidade     d on (c.sq_unidade      = d.sq_unidade)
                 left  join eo_localizacao  e on (c.sq_localizacao  = e.sq_localizacao)
               inner   join cl_usuario      b on (a.sq_pessoa       = b.sq_pessoa)
          where a.sq_pessoa_pai   = @p_cliente
            and (@p_chave is null or (@p_chave is not null and a.sq_pessoa = @p_chave));            
   End Else Begin
--      loop
	while @l_tipo is not null
		begin
         Set @l_item  = Rtrim(LTrim(substring(@l_tipo,1,charindex(',',@l_tipo)-1)));
         If Len(@l_item) > 0 Begin
            Set @x_tipo = @x_tipo + ',''' + @l_item + '''';
         End
         Set @l_tipo = substring(@l_tipo,charindex(',', @l_tipo)+1,200);
	--      Exit when l_tipo is null;
	--     	End Loop;
		End
      Set @x_tipo = upper(substring(@x_tipo,2,200));

      -- Recupera os usu�rios do sistema que estiverem nos v�nculos informados
        select a.sq_pessoa, b.cpf, a.nome, a.nome_resumido, a.nome_indice, a.nome_resumido_ind,
               c.username,
               f.sigla sg_unidade, f.nome nm_unidade, g.nome nm_local
          from co_pessoa                           a
                left outer join  co_pessoa_fisica  b on (a.sq_pessoa       = b.sq_pessoa)
                inner      join  sg_autenticacao   c on (a.sq_pessoa       = c.sq_pessoa and
                                                         c.ativo           = 'S'
                                                        )
                  inner    join  eo_unidade        f on (c.sq_unidade      = f.sq_unidade)
                  inner    join  eo_localizacao    g on (c.sq_localizacao  = g.sq_localizacao)
                inner      join co_tipo_vinculo    d on (a.sq_tipo_vinculo = d.sq_tipo_vinculo and
                                                         d.interno         = 'S' and
                                                         0                 < charindex('''' + upper(d.nome) + '''',@x_tipo)
                                                        )
                inner      join co_tipo_pessoa     e on (a.sq_tipo_pessoa  = e.sq_tipo_pessoa and
                                                         e.ativo           = 'S' and
                                                         e.nome            = 'F�sica'
                                                        )
         where a.sq_pessoa_pai  = @p_cliente 
           and (@p_nome       is null or (@p_nome       is not null and ((a.nome_indice like '%' + upper(dbo.acentos(@p_nome)) + '%')
                                                                   or   a.nome_resumido_ind like '%' + upper(dbo.acentos(@p_nome)) + '%')))
           and (@p_sg_unidade is null or (@p_sg_unidade is not null and dbo.acentos(f.sigla) like '%' + dbo.acentos(@p_sg_unidade) + '%'))         
      order by a.nome_indice;
   End
end
