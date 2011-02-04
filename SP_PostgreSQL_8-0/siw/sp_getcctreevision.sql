create or replace FUNCTION SP_GetCCTreeVision
   (p_cliente    numeric,
    p_sq_pessoa numeric,
    p_sq_menu   numeric,
    p_restricao  varchar,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera a árvore de centros de custo
   If p_restricao is not null Then
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, Nvl(c.sq_cc,0) existe, Nvl(b.Filho,0) Filho
              from ct_cc a
                   left outer join (select sq_cc_pai,count(*) Filho 
                                      from ct_cc x 
                                     where cliente = p_cliente 
                                    group by sq_cc_pai) b      on (a.sq_cc = b.sq_cc_pai)
                   left outer join siw_pessoa_cc        c      on (a.sq_cc     = c.sq_cc and
                                                                   c.sq_pessoa = Nvl(p_sq_pessoa,0) and
                                                                   c.sq_menu   = Nvl(p_sq_menu,0))
             where a.cliente   = p_cliente
               and a.sq_cc_pai is null
             order by a.receita, a.nome;
      Else
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, Nvl(c.sq_cc,0) existe, Nvl(b.Filho,0) Filho
              from ct_cc a
                   left outer join (select sq_cc_pai,count(*) Filho 
                                      from ct_cc x 
                                     where cliente = p_cliente 
                                    group by sq_cc_pai) b on (a.sq_cc = b.sq_cc_pai)  
                   left outer join siw_pessoa_cc        c      on (a.sq_cc     = c.sq_cc and
                                                                   c.sq_pessoa = Nvl(p_sq_pessoa,0) and
                                                                   c.sq_menu   = Nvl(p_sq_menu,0))
             where a.cliente      = p_cliente
               and a.sq_cc_pai    = to_number(p_restricao)
             order by a.receita, a.nome;
      End If;
    End If;

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;