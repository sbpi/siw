create or replace function SP_GetCCTree
   (p_cliente   numeric,
    p_restricao varchar,
    p_result    refcursor
   ) returns refcursor as $$
begin
   -- Recupera a árvore de centros de custo
   If p_restricao is not null Then
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, coalesce(b.Filho,0) as Filho
              from ct_cc a
                     left outer join 
                       (select sq_cc_pai,count(*) as Filho from ct_cc x where cliente = p_cliente group by sq_cc_pai) b
                     on (a.sq_cc = b.sq_cc_pai)  
             where a.cliente      = p_cliente
               and a.sq_cc_pai    is null
             order by a.receita, a.nome;
      Else
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, coalesce(b.Filho,0) as Filho
              from ct_cc a
                     left outer join
                       (select sq_cc_pai,count(*) as Filho from ct_cc x where cliente = p_cliente group by sq_cc_pai) b
                     on (a.sq_cc = b.sq_cc_pai)  
             where a.cliente      = p_cliente
               and a.sq_cc_pai    = p_restricao
             order by a.receita, a.nome;
      End If;
   End If;
   return p_result;
end; $$ language 'plpgsql' volatile;

