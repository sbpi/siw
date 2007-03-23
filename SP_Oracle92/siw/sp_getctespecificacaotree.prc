create or replace procedure SP_GetCTEspecificacaoTree
   (p_cliente   in  number,
    p_ano       in  varchar2 default null,
    p_restricao in  varchar2 default null,
    p_result    out sys_refcursor
   ) is
begin
   -- Recupera a árvore de centros de custo
   If p_restricao is not null Then
      If upper(p_restricao) = 'IS NULL' Then
         open p_result for
            select a.sq_especificacao_despesa chave, a.cliente, a.sq_cc, a.especificacao_pai, 
                   a.ano, a.codigo, a.nome, a.valor, a.ultimo_nivel, a.ativo, Nvl(b.Filho,0) Filho,
                   c.nome nm_ct_cc
              from ct_especificacao_despesa a
                     left outer join 
                       (select especificacao_pai,count(*) Filho from ct_especificacao_despesa x where cliente = p_cliente group by especificacao_pai) b
                     on (a.sq_especificacao_despesa = b.especificacao_pai)
                     left outer join ct_cc c on (a.sq_cc = c.sq_cc)
             where a.cliente         = p_cliente
               and a.especificacao_pai is null
               and (p_ano is null or (p_ano is not null and a.ano = p_ano))
             order by c.nome;
      Else
         open p_result for
            select a.sq_especificacao_despesa chave, a.cliente, a.sq_cc, a.especificacao_pai, 
                   a.ano, a.codigo, a.nome, a.valor, a.ultimo_nivel, a.ativo, Nvl(b.Filho,0) Filho,
                   c.nome nm_ct_cc
              from ct_especificacao_despesa a
                     left outer join
                       (select especificacao_pai,count(*) Filho from ct_especificacao_despesa x where cliente = p_cliente group by especificacao_pai) b
                     on (a.sq_especificacao_despesa = b.especificacao_pai)  
                     left outer join ct_cc c on (a.sq_cc = c.sq_cc)
             where a.cliente            = p_cliente
               and a.especificacao_pai  = p_restricao
               and (p_ano is null or (p_ano is not null and a.ano = p_ano))               
             order by c.nome;
      End If;
    End If;
end SP_GetCTEspecificacaoTree;
/
