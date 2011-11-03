create or replace procedure SP_GetArquivo_PA
   (p_cliente         in  number,
    p_chave           in  number   default null,
    p_chave_aux       in  number   default null,
    p_nome            in  varchar2 default null,
    p_ativo           in  varchar2 default null,
    p_restricao       in  varchar2 default null,
    p_result          out sys_refcursor) is
begin
   -- Recupera os itens de Almoxarifado
   if p_restricao = 'REGISTROS' Then
      open p_result for
         select a.sq_arquivo_local as chave, a.sq_localizacao, a.sq_local_pai, a.nome, a.ativo,
                montanomearquivolocal(a.sq_arquivo_local) as nome_completo,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                d.sq_unidade, d.sigla as sg_unidade
           from pa_arquivo_local          a
                inner join pa_arquivo     b on (a.sq_localizacao = b.sq_localizacao)
                inner join eo_localizacao c on (b.sq_localizacao = c.sq_localizacao)
                inner join eo_unidade     d on (c.sq_unidade     = d.sq_unidade)
          where sq_localizacao = p_chave
            and (p_chave_aux       is null or (p_chave_aux    is not null and a.sq_arquivo_local = p_chave_aux))
            and (p_nome            is null or (p_nome         is not null and upper(acentos(a.nome)) like upper(acentos(p_nome))))
            and (p_ativo           is null or (p_ativo        is not null and a.ativo  = p_ativo));
   Elsif upper(p_restricao) = 'SUBTODOS' Then
      -- Recupera os tipos aos quais o atual pode ser subordinado
      open p_result for
         select a.sq_arquivo_local as chave, a.nome,
           montanomearquivolocal(a.sq_arquivo_local) as nome_completo
           from pa_arquivo_local             a
                inner    join pa_arquivo     b on (a.sq_localizacao = b.sq_localizacao)
                 inner   join eo_localizacao c on (b.sq_localizacao = c.sq_localizacao)
                   inner join eo_unidade     d on (c.sq_unidade     = d.sq_unidade)
          where b.cliente = p_cliente
            and (p_chave       is null or (p_chave        is not null and a.sq_localizacao = p_chave))
            and (p_chave_aux   is null or (p_chave_aux    is not null and d.sq_unidade = p_chave_aux))
         order by a.nome;
   Elsif upper(p_restricao) = 'FOLHA' Then
      -- Recupera os locais onde podem ser arqmazenadas as caixas
      open p_result for
         select a.sq_arquivo_local as chave, a.nome,
           montanomearquivolocal(a.sq_arquivo_local) as nome_completo
           from pa_arquivo_local             a
                inner    join pa_arquivo     b on (a.sq_localizacao = b.sq_localizacao)
                 inner   join eo_localizacao c on (b.sq_localizacao = c.sq_localizacao)
                   inner join eo_unidade     d on (c.sq_unidade     = d.sq_unidade)
          where b.cliente = p_cliente
            and 0         = (select count(*) from pa_arquivo_local where sq_local_pai = a.sq_arquivo_local)
            and (p_chave       is null or (p_chave        is not null and a.sq_localizacao = p_chave))
            and (p_chave_aux   is null or (p_chave_aux    is not null and d.sq_unidade = p_chave_aux))
         order by a.nome;
   Elsif upper(p_restricao) = 'SUBPARTE' Then
      -- Se for alteração, não deixa vincular a si mesmo nem a algum filho
      open p_result for
         select a.sq_arquivo_local as chave, a.sq_localizacao, a.sq_local_pai, a.nome, a.ativo,
                montanomearquivolocal(a.sq_arquivo_local) as nome_completo
           from pa_arquivo_local      a
                inner join pa_arquivo b on (a.sq_localizacao = b.sq_localizacao)
          where b.cliente = p_cliente
            and a.sq_localizacao = p_chave
            and a.sq_arquivo_local not in(select x.sq_arquivo_local
                                            from pa_arquivo_local x
                                                 inner join pa_arquivo y on (x.sq_localizacao = y.sq_localizacao)
                                           where y.cliente = p_cliente
                                          start with x.sq_arquivo_local = p_chave_aux
                                          connect by prior x.sq_arquivo_local = x.sq_local_pai
                                          )
         order by a.nome;

   Elsif upper(p_restricao) = 'IS NULL' Then
      open p_result for
         select a.sq_arquivo_local as chave, a.sq_localizacao, c.cliente as cliente, a.sq_local_pai, a.nome, a.ativo, coalesce(b.filho,0) as filho
           from pa_arquivo_local a
           left join (select sq_local_pai, count(sq_arquivo_local) as filho
                        from pa_arquivo_local x
                    group by sq_local_pai
                     )b on (a.sq_arquivo_local = b.sq_local_pai)
           left join (select sq_localizacao, cliente, nome, ativo
                        from pa_arquivo
                     ) c on (c.sq_localizacao = a.sq_localizacao)
          where cliente = p_cliente
            and (p_chave        is null or (p_chave is not null and a.sq_localizacao = p_chave))
            and a.sq_local_pai  is null
            and (p_nome         is null or (p_nome  is not null and a.nome  = p_nome))
            and (p_ativo        is null or (p_ativo is not null and a.ativo = p_ativo))
         order by a.nome;
   elsif upper(p_restricao) = 'OUTROS' Then
      open p_result for
         select a.sq_localizacao as chave, a.sq_localizacao, a.cliente, a.nome, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                b.nome as nm_localizacao,
                c.sq_unidade, c.sigla as sg_unidade, c.nome as nm_unidade
           from pa_arquivo                a
                inner join eo_localizacao b on (a.sq_localizacao = b.sq_localizacao)
                inner join eo_unidade     c on (b.sq_unidade     = c.sq_unidade)
          where a.cliente       = p_cliente
            and (p_chave        is null or (p_chave        is not null and a.sq_localizacao = p_chave))
            and (p_nome         is null or (p_nome         is not null and upper(acentos(a.nome)) like upper(acentos(p_nome))))
            and (p_ativo        is null or (p_ativo        is not null and a.ativo  = p_ativo));
   Else
      open p_result for
         select a.sq_arquivo_local as chave,c.cliente, a.sq_localizacao, a.sq_local_pai, a.nome, a.ativo, coalesce(b.filho,0) as filho,
                montanomearquivolocal(a.sq_arquivo_local) as nome_completo
           from pa_arquivo_local a
                left join (select sq_local_pai, count(sq_arquivo_local) as filho
                             from pa_arquivo_local x
                           group by sq_local_pai
                          ) b on (a.sq_arquivo_local = b.sq_local_pai)
                left join (select sq_localizacao, cliente, nome, ativo
                             from pa_arquivo x
                          ) c on (a.sq_localizacao = c.sq_localizacao)
          where cliente       = p_cliente
            and (p_chave      is null or (p_chave is not null and a.sq_localizacao   = p_chave))
            and (p_restricao  is null or (p_restricao is not null and a.sq_local_pai = to_number(p_restricao)))
            and (p_nome       is null or (p_nome      is not null and a.nome         = p_nome))
            and (p_ativo      is null or (p_ativo     is not null and a.ativo        = p_ativo))
         order by a.nome;
   end if;
end SP_GetArquivo_PA;
/
