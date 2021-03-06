alter procedure Sp_GetCCTreeVision
   (@p_cliente   int,
    @p_pessoa int    = null,
    @p_menu   int    = null,
    @p_restricao varchar(10) = null
   ) as
begin
   -- Recupera a árvore de centros de custo
   If @p_restricao is not null begin
      If upper(@p_restricao) = 'IS NULL' begin
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, coalesce(c.sq_cc,0) existe, coalesce(b.Filho,0) Filho
              from ct_cc a
                   left outer join (select sq_cc_pai,count(*) Filho 
                                      from ct_cc x 
                                     where cliente = @p_cliente 
                                    group by sq_cc_pai) b      on (a.sq_cc = b.sq_cc_pai)
                   left outer join siw_pessoa_cc        c      on (a.sq_cc     = c.sq_cc and
                                                                   c.sq_pessoa = coalesce(@p_pessoa,0) and
                                                                   c.sq_menu   = coalesce(@p_menu,0))
             where a.cliente   = @p_cliente
               and a.sq_cc_pai is null
             order by a.receita, a.nome;
     end  Else begin
        
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, coalesce(c.sq_cc,0) existe, coalesce(b.Filho,0) Filho
              from ct_cc a
                   left outer join (select sq_cc_pai,count(*) Filho 
                                      from ct_cc x 
                                     where cliente = @p_cliente 
                                    group by sq_cc_pai) b on (a.sq_cc = b.sq_cc_pai)  
                   left outer join siw_pessoa_cc        c      on (a.sq_cc     = c.sq_cc and
                                                                   c.sq_pessoa = coalesce(@p_pessoa,0) and
                                                                   c.sq_menu   = coalesce(@p_menu,0))
             where a.cliente      = @p_cliente
               and a.sq_cc_pai    = @p_restricao
             order by a.receita, a.nome;
      End 
    End 
end 


