alter procedure dbo.SP_GetCCTree
   (@p_cliente   int,
    @p_restricao  varchar(50) = null) as
begin
    -- Recupera a árvore de centros de custo
   If @p_restricao is not null begin
      If upper(@p_restricao) = 'IS NULL' begin        
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, coalesce(b.Filho,0) Filho
              from ct_cc a
                     left outer join 
                       (select sq_cc_pai,count(*) Filho from ct_cc x where cliente = @p_cliente group by sq_cc_pai) b
                     on (a.sq_cc = b.sq_cc_pai)  
             where a.cliente      = @p_cliente
               and a.sq_cc_pai    is null
             order by a.receita, a.nome;
      end Else        
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, coalesce(b.Filho,0) Filho
              from ct_cc a
                     left outer join
                       (select sq_cc_pai,count(*) Filho from ct_cc x where cliente = @p_cliente group by sq_cc_pai) b
                     on (a.sq_cc = b.sq_cc_pai)  
             where a.cliente      = @p_cliente
               and a.sq_cc_pai    = @p_restricao
             order by a.receita, a.nome;
      End
    End 
