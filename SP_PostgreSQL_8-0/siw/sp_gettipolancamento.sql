create or replace FUNCTION SP_GetTipoLancamento
   (p_chave     numeric,
    p_chave_aux numeric,
    p_cliente   numeric,
    p_restricao varchar,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
    
   w_projeto numeric(18);
   w_rubrica numeric(18);
   w_tipo    varchar(1);
   w_menu    varchar(4);
BEGIN
   If upper(p_restricao) = 'SUBTODOS' Then
     -- Recupera os tipos aos quais o atual pode ser subordinado
      open p_result for
         select a.sq_tipo_lancamento as chave, a.sq_tipo_lancamento_pai,
                montanomeTipoLancamento(a.sq_tipo_lancamento) as nm_tipo,
                coalesce(b.qtd,0) as qt_lancamentos
           from fn_tipo_lancamento   a
                left  join (select x.sq_tipo_lancamento, count(x.sq_siw_solicitacao) qtd 
                              from fn_lancamento x
                            group by x.sq_tipo_lancamento
                           )      b on (a.sq_tipo_lancamento = b.sq_tipo_lancamento)
          where a.cliente = p_cliente
            and 0         = coalesce(b.qtd,0)
         order by a.nome;
   Elsif upper(p_restricao) = 'SUBPARTE' Then
     -- Se for alteração, não deixa vincular a si mesmo nem a algum filho
      open p_result for
         select a.sq_tipo_lancamento as chave, a.sq_tipo_lancamento_pai,
                montanomeTipoLancamento(a.sq_tipo_lancamento) as nm_tipo
           from fn_tipo_lancamento   a
                left  join (select x.sq_tipo_lancamento, count(x.sq_siw_solicitacao) qtd 
                              from fn_lancamento x
                            group by x.sq_tipo_lancamento
                           )      b on (a.sq_tipo_lancamento = b.sq_tipo_lancamento)
          where a.cliente = p_cliente
            and a.sq_tipo_lancamento not in (select x.sq_tipo_lancamento
                                           from fn_tipo_lancamento x
                                          where x.cliente   = p_cliente
                                         start with x.sq_tipo_lancamento = p_chave_aux
                                         connect by prior x.sq_tipo_lancamento = x.sq_tipo_lancamento_pai
                                        )
         order by a.nome;
   Elsif p_restricao = 'ARVORE' Then
      -- Recupera a árvore das etapas
      open p_result for 
         select a.sq_tipo_lancamento as chave, a.nome, a.descricao, a.receita, a.despesa, a.ativo, a.sq_tipo_lancamento_pai,
                case a.receita when 'S' Then 'Sim' Else 'Não' end as nm_receita,
                case a.despesa when 'S' Then 'Sim' Else 'Não' end as nm_despesa,
                case a.ativo   when 'S' Then 'Sim' Else 'Não' end as nm_ativo,
                coalesce(b.qtd,0) as qt_lancamentos, coalesce(c.qtd,0) as qt_filhos,
                level
           from fn_tipo_lancamento   a
                left  join (select x.sq_tipo_lancamento, count(x.sq_siw_solicitacao) qtd 
                              from fn_lancamento x
                                   inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                     inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and z.sigla <> 'CA')
                            group by x.sq_tipo_lancamento
                           )      b on (a.sq_tipo_lancamento = b.sq_tipo_lancamento)
                left  join (select x.sq_tipo_lancamento_pai, count(x.sq_tipo_lancamento) qtd 
                              from fn_tipo_lancamento x
                             where x.sq_tipo_lancamento_pai is not null
                            group by x.sq_tipo_lancamento_pai
                           )      c on (a.sq_tipo_lancamento = c.sq_tipo_lancamento_pai)
          where a.cliente = p_cliente
            and a.sq_tipo_lancamento not in (select x.sq_tipo_lancamento
                                           from fn_tipo_lancamento x
                                          where x.cliente   = p_cliente
                                         start with x.sq_tipo_lancamento = p_chave_aux
                                         connect by prior x.sq_tipo_lancamento = x.sq_tipo_lancamento_pai
                                        )
            and (p_chave_aux is null or (p_chave_aux is not null and a.sq_tipo_lancamento <> p_chave_aux))
         connect by prior a.sq_tipo_lancamento = a.sq_tipo_lancamento_pai
         start with coalesce(a.sq_tipo_lancamento_pai,0) = coalesce(p_chave_aux,0)
         order by montanomeTipoLancamento(a.sq_tipo_lancamento);
   Elsif upper(p_restricao) = 'REEMBOLSO' Then
     -- Se reembolso, recupera classificação inicial
      open p_result for
         select chave, sq_tipo_lancamento_pai, nm_tipo
           from (select a.sq_tipo_lancamento as chave, a.sq_tipo_lancamento_pai,
                        montanomeTipoLancamento(a.sq_tipo_lancamento) as nm_tipo
                   from fn_tipo_lancamento   a
                  where a.cliente                = p_cliente
                    and a.sq_tipo_lancamento_pai is null
                    and a.despesa                = 'S'
                    and a.receita                = 'N'
                )
         where rownum = 1;
   Else
      If length(p_restricao) = 25 Then
         w_menu    := substr(p_restricao,1,4);
         w_projeto := substr(p_restricao,5,10);
         w_rubrica := substr(p_restricao,15,10);
         w_tipo    := coalesce(substr(p_restricao,25,1),'T');
      End If;
      -- Recupera os tipos de lançamento financeiro do cliente
      open p_result for 
         select a.sq_tipo_lancamento as chave, a.nome, a.descricao, a.receita, a.despesa, a.ativo,
                montanomeTipoLancamento(a.sq_tipo_lancamento) as nm_tipo,
                a.sq_tipo_lancamento_pai,
                case a.receita when 'S' Then 'Sim' Else 'Não' end as nm_receita,
                case a.despesa when 'S' Then 'Sim' Else 'Não' end as nm_despesa,
                case a.ativo   when 'S' Then 'Sim' Else 'Não' end as nm_ativo,
                acentos(a.nome) as ordena
           from fn_tipo_lancamento   a
          where a.cliente     = p_cliente
            and ((p_chave     is null) or (p_chave     is not null and a.sq_tipo_lancamento = p_chave))
            and (p_restricao is null or 
                 (p_restricao is not null and 
                  (instr(p_restricao,'VINC') = 0 or (instr(p_restricao,'VINC') > 0 and 0 = (select count(*) from fn_tipo_lancamento where sq_tipo_lancamento_pai = a.sq_tipo_lancamento))) and
                  ((substr(p_restricao,3,1) = 'R' and a.receita = 'S') or 
                   (substr(p_restricao,3,1) = 'D' and a.despesa = 'S') or
                   ((w_menu not in ('PDSV','CLPC','CLLC')) or
                    (w_menu = 'PDSV' and
                     0 < (select count(*) 
                            from pd_vinculo_financeiro x 
                           where x.sq_siw_solicitacao = w_projeto
                             and x.sq_projeto_rubrica = coalesce(w_rubrica,x.sq_projeto_rubrica)
                             and x.sq_tipo_lancamento = a.sq_tipo_lancamento
                             and (w_tipo              = 'T' or
                                  (w_tipo             <> 'T' and
                                   ((w_tipo           = 'D' and x.diaria        = 'S') or
                                    (w_tipo           = 'H' and x.hospedagem    = 'S') or
                                    (w_tipo           = 'V' and x.veiculo       = 'S') or
                                    (w_tipo           = 'S' and x.seguro        = 'S') or
                                    (w_tipo           = 'B' and x.bilhete       = 'S') or
                                    (w_tipo           = 'R' and x.ressarcimento = 'S')
                                   )
                                  )
                                 )
                         )
                    ) or
                    (w_menu in ('CLPC','CLLC') and
                     0 < (select count(*) 
                            from cl_vinculo_financeiro x
                                 inner join siw_menu   y on (x.sq_menu = y.sq_menu and y.sigla = w_menu||'CAD')
                           where x.sq_siw_solicitacao = w_projeto
                             and x.sq_projeto_rubrica = coalesce(w_rubrica,x.sq_projeto_rubrica)
                             and x.sq_tipo_lancamento = a.sq_tipo_lancamento
                             and (w_tipo              = 'T' or
                                  (w_tipo             <> 'T' and
                                   ((w_tipo           = 'C' and x.consumo    = 'S') or
                                    (w_tipo           = 'P' and x.permanente = 'S') or
                                    (w_tipo           = 'S' and x.servico    = 'S') or
                                    (w_tipo           = 'O' and x.outros     = 'S')
                                   )
                                  )
                                 )
                         )
                    )
                   )
                  )
                 )
                );
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;