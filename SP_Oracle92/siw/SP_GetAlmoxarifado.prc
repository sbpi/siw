create or replace procedure SP_GetAlmoxarifado
   (p_cliente         in  number,
    p_chave           in  number   default null,
    p_chave_aux       in  number   default null,
    p_nome            in  varchar2 default null,
    p_localizacao     in  number   default null,
    p_ativo           in  varchar2 default null,
    p_restricao       in  varchar2 default null,
    p_result          out sys_refcursor) is
begin
   -- Recupera os itens de Almoxarifado
   if upper(p_restricao) = 'REGISTROS' Then
      open p_result for
           select a.sq_almoxarifado_local as chave, a.sq_almoxarifado, a.sq_local_pai, a.nome, a.ativo, 
                 montanomealmoxlocal(a.sq_almoxarifado_local) as nome_completo,
                 case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                 d.sq_unidade, d.sigla as sg_unidade
           from mt_almoxarifado_local a
                  inner   join mt_almoxarifado b on (a.sq_almoxarifado = b.sq_almoxarifado)
                  inner   join eo_localizacao c on (b.sq_localizacao = c.sq_localizacao)
                  inner   join eo_unidade     d on (c.sq_unidade =  d.sq_unidade)
            where a.sq_almoxarifado = p_chave
              and (p_chave_aux    is null or (p_chave_aux        is not null and a.sq_almoxarifado_local = p_chave_aux))
              and (p_nome         is null or (p_nome         is not null and upper(acentos(a.nome)) like upper(acentos(p_nome))))
              and (p_ativo        is null or (p_ativo        is not null and a.ativo  = p_ativo));   

   Elsif upper(p_restricao) = 'ARMAZENA' Then
     -- Recupera os tipos aos quais o atual pode ser subordinado
      open p_result for
         select a.sq_almoxarifado_local as chave, a.nome, a.sq_local_pai,
                b.nome||' - '||montanomealmoxlocal(a.sq_almoxarifado_local) as nome_completo
           from mt_almoxarifado_local            a
                inner join mt_almoxarifado       b on (a.sq_almoxarifado       = b.sq_almoxarifado)
                left  join mt_almoxarifado_local c on (a.sq_almoxarifado_local = c.sq_local_pai)
          where b.cliente                = p_cliente
            and a.sq_almoxarifado        = p_chave
            and c.sq_almoxarifado_local is null;

   Elsif upper(p_restricao) = 'SUBTODOS' Then
     -- Recupera os tipos aos quais o atual pode ser subordinado
      open p_result for
         select a.sq_almoxarifado_local as chave, a.nome, a.sq_local_pai,
                montanomealmoxlocal(a.sq_almoxarifado_local) as nome_completo
           from mt_almoxarifado_local      a
                inner join mt_almoxarifado b on (a.sq_almoxarifado = b.sq_almoxarifado)
          where b.cliente         = p_cliente
            and a.sq_almoxarifado = p_chave
         order by a.nome;

   Elsif upper(p_restricao) = 'SUBPARTE' Then
     -- Se for altera��o, n�o deixa vincular a si mesmo nem a algum filho
      open p_result for
         select a.sq_almoxarifado_local as chave, a.nome, a.sq_local_pai,
                montanomealmoxlocal(a.sq_almoxarifado_local) as nome_completo
           from mt_almoxarifado_local      a
                inner join mt_almoxarifado b on (a.sq_almoxarifado = b.sq_almoxarifado)
          where b.cliente               = p_cliente
            and a.sq_almoxarifado       = p_chave
            and a.sq_almoxarifado_local not in (select x.sq_almoxarifado_local
                                                   from mt_almoxarifado_local      x
                                                        inner join mt_almoxarifado y on (x.sq_almoxarifado = y.sq_almoxarifado)
                                                  where y.cliente   = p_cliente
                                                 start with x.sq_almoxarifado_local = p_chave_aux
                                                 connect by prior x.sq_almoxarifado_local = x.sq_local_pai
                                                )
         order by a.nome;              
   Elsif upper(p_restricao) = 'IS NULL' Then
       open p_result for
            select a.sq_almoxarifado_local as chave, a.sq_almoxarifado, c.cliente as cliente, a.sq_local_pai, a.nome, a.ativo, coalesce(b.filho,0) as filho 
              from mt_almoxarifado_local a
              left join (select sq_local_pai, count(sq_almoxarifado_local) as filho
                           from mt_almoxarifado_local x
                       group by sq_local_pai
                        )b on (a.sq_almoxarifado_local = b.sq_local_pai)
              left join (select sq_almoxarifado, cliente 
                           from mt_almoxarifado
                        ) c on(c.sq_almoxarifado = a.sq_almoxarifado)
            where cliente = p_cliente
              and (p_chave        is null or (p_chave        is not null and a.sq_almoxarifado = p_chave))
              and a.sq_local_pai  is null
              and (p_nome         is null or (p_nome  is not null and a.nome  = p_nome))
              and (p_ativo        is null or (p_ativo is not null and a.ativo = p_ativo))
         order by a.nome;
   elsif upper(p_restricao) = 'OUTROS' Then
       open p_result for
           select a.sq_almoxarifado as chave, a.cliente, a.sq_localizacao, a.nome, a.ativo, 
                  case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                  b.nome as nm_localizacao, 
                  c.sq_unidade, c.nome as nm_unidade, c.sigla as sg_unidade                                
             from mt_almoxarifado             a 
                  inner   join eo_localizacao b on (a.sq_localizacao = b.sq_localizacao)
                    inner join eo_unidade     c on (b.sq_unidade     = c.sq_unidade)
            where a.cliente = p_cliente
              and (p_chave        is null or (p_chave        is not null and a.sq_almoxarifado = p_chave))
              and (p_nome         is null or (p_nome         is not null and upper(acentos(a.nome)) like upper(acentos(p_nome))))
              and (p_localizacao  is null or (p_localizacao  is not null and a.sq_localizacao = p_localizacao))
              and (p_ativo        is null or (p_ativo        is not null and a.ativo  = p_ativo));
   Else
       open p_result for
          select a.sq_almoxarifado_local as chave, c.cliente as cliente, a.sq_almoxarifado, a.sq_local_pai, a.nome, a.ativo, coalesce(b.filho,0) as filho 
            from mt_almoxarifado_local a
                 left join (select sq_local_pai, count(sq_almoxarifado_local) as filho
                              from mt_almoxarifado_local x
                          group by sq_local_pai
                            ) b on (a.sq_almoxarifado_local = b.sq_local_pai)
                 left join (select sq_almoxarifado, cliente
                              from mt_almoxarifado x
                            ) c on(a.sq_almoxarifado = c.sq_almoxarifado)
          where cliente = p_cliente
          -- and a.sq_local_pai = to_number(p_restricao)
          and (p_chave      is null or (p_chave     is not null and a.sq_almoxarifado = p_chave))
          and (p_restricao  is null or (p_restricao is not null and a.sq_local_pai    = to_number(p_restricao)))
          and (p_nome       is null or (p_nome      is not null and a.nome            = p_nome))
          and (p_ativo      is null or (p_ativo     is not null and a.ativo           = p_ativo))
          order by a.nome;     
   end if;
end SP_GetAlmoxarifado;
/
