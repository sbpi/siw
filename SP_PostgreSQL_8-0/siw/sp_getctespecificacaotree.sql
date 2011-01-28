create or replace FUNCTION SP_GetCTEspecificacaoTree
   (p_cliente    numeric,
    p_ano        varchar,
    p_restricao  varchar,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
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
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;