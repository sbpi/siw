create or replace procedure SP_GetMoedaCotacao
   (p_cliente   in  number   default null,
    p_chave     in  number   default null,
    p_moeda     in  number   default null,
    p_inicio    in  date     default null,
    p_fim       in  date     default null,
    p_restricao in  varchar2 default null,
    p_result    out sys_refcursor) is
begin
  If p_restricao is null Then
    open p_result for 
      select a.sq_moeda_cotacao, a.cliente,       a.sq_moeda,       a.data,             a.taxa_compra,   a.taxa_venda,
             b.codigo cd_moeda,  b.nome nm_moeda, b.sigla sg_moeda, b.simbolo sb_moeda, b.tipo tp_moeda, b.exclusao_ptax,
             b.ativo  at_moeda,
             case b.ativo  when 'S' then 'Sim' else 'Não' end as nm_ativo
        from co_moeda_cotacao    a
             inner join co_moeda b on (a.sq_moeda = b.sq_moeda)
       where cliente = p_cliente
         and (p_chave  is null or (p_chave  is not null and a.sq_moeda_cotacao = p_chave))
         and (p_moeda  is null or (p_moeda  is not null and a.sq_moeda         = p_moeda))
         and (p_inicio is null or (p_inicio is not null and a.data             between p_inicio and p_fim));
  Elsif p_restricao = 'ANTERIOR' Then
    open p_result for 
      select a.sq_moeda_cotacao, a.cliente,       a.sq_moeda,       a.data,             a.taxa_compra,   a.taxa_venda,
             b.codigo cd_moeda,  b.nome nm_moeda, b.sigla sg_moeda, b.simbolo sb_moeda, b.tipo tp_moeda, b.exclusao_ptax,
             b.ativo  at_moeda,
             case b.ativo  when 'S' then 'Sim' else 'Não' end as nm_ativo
        from co_moeda_cotacao    a
             inner join co_moeda b on (a.sq_moeda = b.sq_moeda)
       where cliente = p_cliente
         and (p_chave  is null or (p_chave  is not null and a.sq_moeda_cotacao = p_chave))
         and (p_moeda  is null or (p_moeda  is not null and a.sq_moeda         = p_moeda))
         and (p_inicio is null or (p_inicio is not null and (a.data             between p_inicio and p_fim or
                                                             -- data dentro do período informado
                                                             (a.data            not between p_inicio and p_fim and
                                                              0                 = (select count(*) from co_moeda_cotacao where sq_moeda = a.sq_moeda and data between p_inicio and p_fim) and
                                                              -- data fora do período informado E
                                                              a.data            = (select max(data) from co_moeda_cotacao where sq_moeda = a.sq_moeda and data < p_inicio) AND
                                                              -- data menor que o início do período informado E
                                                              0                 < (select count(*) from co_moeda_cotacao where sq_moeda = a.sq_moeda and data > p_fim)
                                                              -- há cotação em data posterior ao período informado
                                                             )
                                                            )
                                  )
             );
  End If;
end SP_GetMoedaCotacao;
/
