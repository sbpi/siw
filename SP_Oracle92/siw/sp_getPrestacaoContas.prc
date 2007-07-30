create or replace procedure sp_getPrestacaoContas
   (p_cliente   in number,
    p_chave     in number   default null,
    p_chave_pai in number   default null,
    p_nome      in varchar2 default null,
    p_tipo      in varchar2 default null,
    p_ativo     in varchar2 default null,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao = 'REGISTROS' Then
      -- Recupera as prestacoes existentes
      open p_result for 
         select a.sq_prestacao_contas as chave, a.sq_prestacao_pai, a.cliente, a.nome,
                a.descricao, a.tipo, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from ac_prestacao_contas       a
          where a.cliente            = p_cliente
            and (p_chave             is null or (p_chave   is not null and a.sq_prestacao_contas = p_chave))
            and (p_nome              is null or (p_nome    is not null and a.nome = p_nome))
            and (p_tipo              is null or (p_tipo    is not null and a.tipo = p_tipo))
            and (p_ativo             is null or (p_ativo   is not null and a.ativo = p_ativo))
         order by a.nome;
   Elsif upper(p_restricao) = 'SUBTODOS' Then
     -- Recupera as prestacoes aos quais o atual pode ser subordinado
      open p_result for
         select a.sq_prestacao_contas as chave,a.nome,
                montanomeprestacaocontas(a.sq_prestacao_contas) as nome_completo,
                coalesce(b.qtd,0) as qtd_solic
           from ac_prestacao_contas a
                left  join (select x.sq_prestacao_contas, count(distinct(x.sq_siw_solicitacao)) qtd 
                              from siw_contas_cronograma x
                            group by x.sq_prestacao_contas
                           )      b on (a.sq_prestacao_contas = b.sq_prestacao_contas)
          where a.cliente = p_cliente
         order by a.nome;
   Elsif upper(p_restricao) = 'SUBPARTE' Then
     -- Se for alteração, não deixa vincular a si mesmo nem a algum filho
      open p_result for
         select a.sq_prestacao_contas as chave,a.nome,
                montanomeprestacaocontas(a.sq_prestacao_contas) as nome_completo,
                coalesce(b.qtd,0) as qtd_solic
           from ac_prestacao_contas a
                left  join (select x.sq_prestacao_contas, count(distinct(x.sq_siw_solicitacao)) qtd 
                              from siw_contas_cronograma x
                            group by x.sq_prestacao_contas
                           )      b on (a.sq_prestacao_contas = b.sq_prestacao_contas)
          where a.cliente = p_cliente
            and a.sq_prestacao_contas not in (select x.sq_prestacao_contas
                                              from ac_prestacao_contas x
                                             where x.cliente   = p_cliente
                                            start with x.sq_prestacao_contas = p_chave
                                            connect by prior x.sq_prestacao_contas = x.sq_prestacao_pai
                                           )
         order by a.nome;
   Elsif upper(p_restricao) = 'FOLHA' Then
     -- Recupera apenas os registros sem filhos
      open p_result for
         select  a.sq_prestacao_contas as chave, a.sq_prestacao_pai, a.nome, a.tipo,
                montanomeprestacaocontas(a.sq_prestacao_contas) as nome_completo
           from ac_prestacao_contas a
                left  join (select sq_prestacao_pai
                              from ac_prestacao_contas 
                            group by sq_prestacao_pai
                           )    b on (a.sq_prestacao_contas = b.sq_prestacao_pai)
          where a.cliente     = p_cliente
            and b.sq_prestacao_pai is null
            and (p_chave      is null or (p_chave     is not null and a.sq_prestacao_contas = p_chave))
            and (p_chave_pai  is null or (p_chave_pai is not null and a.sq_prestacao_pai = p_chave_pai))
            and (p_nome       is null or (p_nome      is not null and a.nome = p_nome))
            and (p_tipo       is null or (p_tipo      is not null and a.tipo = p_tipo))
            and (p_ativo      is null or (p_ativo     is not null and a.ativo = p_ativo))
         connect by prior a.sq_prestacao_pai = a.sq_prestacao_contas
         order by 5;
   Elsif upper(p_restricao) = 'PAI' Then
     -- Recupera apenas os registros pais
      open p_result for
         select  a.sq_prestacao_contas as chave, a.sq_prestacao_pai, a.nome, a.tipo,
                montanomeprestacaocontas(a.sq_prestacao_contas) as nome_completo
           from ac_prestacao_contas a
          where a.cliente     = p_cliente
            and a.sq_prestacao_pai is null
            and (p_chave      is null or (p_chave     is not null and a.sq_prestacao_contas = p_chave))
            and (p_chave_pai  is null or (p_chave_pai is not null and a.sq_prestacao_pai = p_chave_pai))
            and (p_nome       is null or (p_nome      is not null and a.nome = p_nome))
            and (p_tipo       is null or (p_tipo      is not null and a.tipo = p_tipo))
            and (p_ativo      is null or (p_ativo     is not null and a.ativo = p_ativo))
         order by 5;
   Elsif p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo nome ou sigla
      open p_result for 
         select a.sq_prestacao_contas as chave, a.cliente, a.nome,
                a.tipo, a.descricao, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from ac_prestacao_contas a
          where a.cliente                = p_cliente
            and a.sq_prestacao_contas    <> coalesce(p_chave,0)
            and (p_chave_pai             is null or (p_chave_pai is not null and a.sq_prestacao_pai = p_chave_pai))
            and (p_nome                  is null or (p_nome      is not null and acentos(a.nome) = acentos(p_nome)))
            and (p_tipo                  is null or (p_tipo      is not null and a.tipo = p_tipo))
            and (p_ativo                 is null or (p_ativo     is not null and a.ativo = p_ativo))
         order by a.nome;
   Elsif p_restricao = 'VINCULADO' Then
      -- Verifica se o registro está vinculado a um recurso
      open p_result for 
         select a.sq_prestacao_contas as chave, a.cliente, a.nome,
                a.descricao, a.tipo, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from ac_prestacao_contas              a
                inner join siw_contas_cronograma b on (a.sq_prestacao_contas = b.sq_prestacao_contas)
          where a.cliente             = p_cliente
            and a.sq_prestacao_contas = p_chave
         order by a.nome;
   Elsif p_restricao is not null Then
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_prestacao_contas as chave, a.cliente, a.sq_prestacao_pai, a.nome, a.descricao, a.ativo, a.tipo, coalesce(b.filho,0) as filho,
                   montanomeprestacaocontas(a.sq_prestacao_contas) as nome_completo,
                   coalesce(c.qtd,0) as qtd_solic, coalesce(d.qtd,0) as qtd_prj
              from ac_prestacao_contas a
                   left  join (select sq_prestacao_pai, count(sq_prestacao_contas) as filho 
                                 from ac_prestacao_contas x 
                                where cliente = p_cliente 
                               group by sq_prestacao_pai
                              ) b on (a.sq_prestacao_contas = b.sq_prestacao_pai)
                   left  join (select x.sq_prestacao_contas, count(x.sq_siw_solicitacao) qtd 
                                 from siw_contas_cronograma x
                               group by x.sq_prestacao_contas
                              ) c on (a.sq_prestacao_contas = c.sq_prestacao_contas)
                   left  join (select x.sq_prestacao_contas, count(distinct(x.sq_siw_solicitacao)) qtd
                                 from siw_contas_cronograma x
                               group by x.sq_prestacao_contas
                              ) d on (a.sq_prestacao_contas = d.sq_prestacao_contas)
             where a.cliente     = p_cliente
               and a.sq_prestacao_pai is null
               and (p_chave      is null or (p_chave   is not null and a.sq_prestacao_contas = p_chave))
               and (p_nome       is null or (p_nome    is not null and a.nome                = p_nome))
               and (p_tipo       is null or (p_tipo    is not null and a.tipo                = p_tipo))
               and (p_ativo      is null or (p_ativo   is not null and a.ativo               = p_ativo))
            order by a.nome;
      Else
         open p_result for
            select a.sq_prestacao_contas as chave, a.cliente, a.sq_prestacao_pai, a.nome, a.descricao, a.tipo, a.ativo, coalesce(b.filho,0) as filho,
                montanomeprestacaocontas(a.sq_prestacao_contas) as nome_completo, coalesce(d.qtd,0) as qtd_prj
              from ac_prestacao_contas a
                   left join (select sq_prestacao_pai, count(sq_prestacao_contas) as filho 
                                from ac_prestacao_contas x 
                               where cliente = p_cliente 
                              group by sq_prestacao_pai
                             ) b on (a.sq_prestacao_contas = b.sq_prestacao_pai)
                   left  join (select x.sq_prestacao_contas, count(x.sq_siw_solicitacao) qtd 
                                 from siw_contas_cronograma x
                               group by x.sq_prestacao_contas
                              ) c on (a.sq_prestacao_contas = c.sq_prestacao_contas)
                   left  join (select x.sq_prestacao_contas, count(distinct(x.sq_siw_solicitacao)) qtd
                                 from siw_contas_cronograma x
                               group by x.sq_prestacao_contas
                              ) d on (a.sq_prestacao_contas = d.sq_prestacao_contas)
             where a.cliente     = p_cliente
               and a.sq_prestacao_pai = p_restricao
               and (p_nome       is null or (p_nome    is not null and a.nome   = p_nome))
               and (p_ativo      is null or (p_ativo   is not null and a.ativo  = p_ativo))
               and (p_tipo       is null or (p_tipo    is not null and a.tipo   = p_tipo))
            order by a.nome;
      End If;
   End If;
end sp_getPrestacaoContas;
/
