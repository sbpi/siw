create or replace procedure SP_GetCCTree
   (p_cliente   in  number,
    p_restricao in  varchar2 default null,
    p_result    out sys_refcursor
   ) is
begin
   -- Recupera a árvore de centros de custo
   If p_restricao is not null Then
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, Nvl(b.Filho,0) Filho
              from ct_cc a
                     left outer join 
                       (select sq_cc_pai,count(*) Filho from ct_cc x where cliente = p_cliente group by sq_cc_pai) b
                     on (a.sq_cc = b.sq_cc_pai)  
             where a.cliente      = p_cliente
               and a.sq_cc_pai    is null
             order by a.receita, a.nome;
      Else
         open p_result for
            select a.sq_cc, a.nome, a.descricao, a.ativo, a.receita, a.regular, a.sigla, Nvl(b.Filho,0) Filho
              from ct_cc a
                     left outer join
                       (select sq_cc_pai,count(*) Filho from ct_cc x where cliente = p_cliente group by sq_cc_pai) b
                     on (a.sq_cc = b.sq_cc_pai)  
             where a.cliente      = p_cliente
               and a.sq_cc_pai    = to_number(p_restricao)
             order by a.receita, a.nome;
      End If;
    End If;
end SP_GetCCTree;
/
