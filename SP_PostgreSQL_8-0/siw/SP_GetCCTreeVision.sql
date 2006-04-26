create or replace function SP_GetCCTreeVision
   (p_cliente   numeric,
    p_sq_pessoa numeric,
    p_sq_menu   numeric,
    p_restricao varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera a árvore de centros de custo
   If p_restricao is not null Then
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, coalesce(c.sq_cc,0) as existe, coalesce(b.Filho,0) as Filho
              from ct_cc a
                   left outer join (select sq_cc_pai,count(*) as Filho 
                                      from ct_cc x 
                                     where cliente = p_cliente 
                                    group by sq_cc_pai) b      on (a.sq_cc = b.sq_cc_pai)
                   left outer join siw_pessoa_cc        c      on (a.sq_cc     = c.sq_cc and
                                                                   c.sq_pessoa = coalesce(p_sq_pessoa,0) and
                                                                   c.sq_menu   = coalesce(p_sq_menu,0))
             where a.cliente   = p_cliente
               and a.sq_cc_pai is null
             order by a.receita, a.nome;
      Else
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, coalesce(c.sq_cc,0) as existe, coalesce(b.Filho,0) as Filho
              from ct_cc a
                   left outer join (select sq_cc_pai,count(*) as Filho 
                                      from ct_cc x 
                                     where cliente = p_cliente 
                                    group by sq_cc_pai) b on (a.sq_cc = b.sq_cc_pai)  
                   left outer join siw_pessoa_cc        c      on (a.sq_cc     = c.sq_cc and
                                                                   c.sq_pessoa = coalesce(p_sq_pessoa,0) and
                                                                   c.sq_menu   = coalesce(p_sq_menu,0))
             where a.cliente      = p_cliente
               and a.sq_cc_pai    = p_restricao
             order by a.receita, a.nome;
      End If;
    End If;
    return p_result;
end; $$ language 'plpgsql' volatile;