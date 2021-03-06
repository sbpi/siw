create or replace procedure SP_GetCCTreeVision
   (p_cliente   in  number,
    p_sq_pessoa in number    default null,
    p_sq_menu   in number    default null,
    p_restricao in  varchar2 default null,
    p_result    out siw.sys_refcursor
   ) is
begin
   -- Recupera a �rvore de centros de custo
   If p_restricao is not null Then
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, Nvl(c.sq_cc,0) existe, Nvl(b.Filho,0) Filho
              from ct_cc a,
                   (select sq_cc_pai,count(*) Filho
                                      from ct_cc x
                                     where cliente = p_cliente
                                    group by sq_cc_pai) b,
                   siw_pessoa_cc        c
             where (a.sq_cc         = b.sq_cc_pai (+))
               and (a.sq_cc         = c.sq_cc (+) and
                    c.sq_pessoa (+) = Nvl(p_sq_pessoa,0) and
                    c.sq_menu   (+) = Nvl(p_sq_menu,0)
                   )
               and a.cliente   = p_cliente
               and a.sq_cc_pai is null
             order by a.receita, a.nome;
      Else
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, Nvl(c.sq_cc,0) existe, Nvl(b.Filho,0) Filho
              from ct_cc a,
                    (select sq_cc_pai,count(*) Filho
                                      from ct_cc x
                                     where cliente = p_cliente
                                    group by sq_cc_pai) b,
                   siw_pessoa_cc        c
             where (a.sq_cc = b.sq_cc_pai (+))
               and (a.sq_cc         = c.sq_cc (+) and
                    c.sq_pessoa (+) = Nvl(p_sq_pessoa,0) and
                    c.sq_menu   (+) = Nvl(p_sq_menu,0)
                    )
               and a.cliente      = p_cliente
               and a.sq_cc_pai    = p_restricao
             order by a.receita, a.nome;
      End If;
    End If;
end SP_GetCCTreeVision;
/

