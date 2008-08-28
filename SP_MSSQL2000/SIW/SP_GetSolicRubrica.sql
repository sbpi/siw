create  procedure Sp_GetSolicRubrica
   (@p_chave                int,
    @p_chave_aux            int    = null,
    @p_ativo                varchar(2)  = null,
    @p_sq_rubrica_destino   int    = null,
    @p_codigo               varchar(20)  = null,
    @p_transferencia        varchar(1)  = null,
    @p_inicio               datetime      = null,
    @p_fim                  datetime      = null,
    @p_restricao            varchar(20)  = null
    
   ) as
begin
   If @p_restricao is null begin
       
         select a.sq_projeto_rubrica, a.sq_cc, a.codigo, a.nome, a.descricao, a.ativo,
                a.valor_inicial, a.entrada_prevista, a.entrada_real, (a.entrada_prevista - a.entrada_real) entrada_pendente,
                a.saida_prevista, a.saida_real, (a.saida_prevista-a.saida_real) saida_pendente,
                case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                case a.aplicacao_financeira when 'S' then 'Sim' else 'N�o' end nm_aplicacao_financeira,
                b.nome nm_cc, a.aplicacao_financeira,
                c.total_previsto, c.total_real
           from pj_rubrica                      a
                inner join ct_cc                b on (a.sq_cc              = b.sq_cc)
                left  join (select sum(x.valor_previsto) as total_previsto, 
                                   sum(x.valor_real) as total_real, 
                                   x.sq_projeto_rubrica
                              from pj_rubrica_cronograma x
                             where ((@p_inicio is null) or (@p_inicio is not null and ((x.inicio  between @p_inicio and @p_fim) or
                                                                                     (x.fim     between @p_inicio and @p_fim) or
                                                                                     (@p_inicio  between x.inicio and x.fim) or
                                                                                     (@p_fim     between x.inicio and x.fim)
                                                                                     )
                                                           )
                                   )
                            group by x.sq_projeto_rubrica
                           )                    c on (a.sq_projeto_rubrica = c.sq_projeto_rubrica)
          where (@p_chave                is null or (@p_chave                is not null and a.sq_siw_solicitacao   = @p_chave))
            and (@p_chave_aux            is null or (@p_chave_aux            is not null and a.sq_projeto_rubrica   = @p_chave_aux))
            and (@p_ativo                is null or (@p_ativo                is not null and a.ativo                = @p_ativo))
            and (@p_sq_rubrica_destino   is null or (@p_sq_rubrica_destino   is not null and a.sq_projeto_rubrica   <> @p_sq_rubrica_destino))
            and (@p_codigo               is null or (@p_codigo               is not null and a.codigo               = @p_codigo))
            and (@p_transferencia is null or (@p_transferencia is not null and a.aplicacao_financeira = @p_transferencia))
            and (@p_inicio               is null or (@p_inicio               is not null and c.sq_projeto_rubrica   is not null));
             
   end else if @p_restricao = 'PDFINANC' begin
       
         select a.sq_projeto_rubrica, a.sq_cc, a.codigo, a.nome, a.descricao, a.ativo,
                a.valor_inicial, a.entrada_prevista, a.entrada_real, (a.entrada_prevista - a.entrada_real) entrada_pendente,
                a.saida_prevista, a.saida_real, (a.saida_prevista-a.saida_real) saida_pendente,
                case a.ativo when 'S' then 'Sim' else 'N�o' end nm_ativo,
                case a.aplicacao_financeira when 'S' then 'Sim' else 'N�o' end nm_aplicacao_financeira,
                b.nome nm_cc, a.aplicacao_financeira,
                c.total_previsto, c.total_real
           from pj_rubrica                       a
                inner join ct_cc                 b on (a.sq_cc              = b.sq_cc)
                left  join (select sum(x.valor_previsto) as total_previsto, 
                                   sum(x.valor_real) as total_real, 
                                   x.sq_projeto_rubrica
                              from pj_rubrica_cronograma x
                             where ((@p_inicio is null) or (@p_inicio is not null and ((x.inicio  between @p_inicio and @p_fim) or
                                                                                     (x.fim     between @p_inicio and @p_fim) or
                                                                                     (@p_inicio  between x.inicio and x.fim) or
                                                                                     (@p_fim     between x.inicio and x.fim)
                                                                                     )
                                                           )
                                   )
                            group by x.sq_projeto_rubrica
                           )                     c on (a.sq_projeto_rubrica = c.sq_projeto_rubrica)
                inner join pd_vinculo_financeiro d on (a.sq_projeto_rubrica = d.sq_projeto_rubrica and
                                                       (@p_codigo            = 'T' or
                                                        (@p_codigo           <> 'T' and
                                                         ((@p_codigo         = 'D' and d.diaria     = 'S') or
                                                          (@p_codigo         = 'H' and d.hospedagem = 'S')or
                                                          (@p_codigo         = 'V' and d.veiculo    = 'S')or
                                                          (@p_codigo         = 'S' and d.seguro     = 'S')or
                                                          (@p_codigo         = 'B' and d.bilhete    = 'S')
                                                         )
                                                        )
                                                       )
                                                      )
          where a.sq_siw_solicitacao   = @p_chave;
             
   end else if @p_restricao = 'FICHA' begin
       
        select sum(a.valor) valor, 
               c.vencimento, d.codigo_interno cd_lancamento, c.sq_siw_solicitacao sq_lancamento, c.tipo tipo_rubrica,
               dbo.to_char(c.vencimento, 'DD/MM/YYYY, HH24:MI:SS') phpdt_vencimento,
               d.descricao nm_lancamento, e.sigla sg_lancamento_menu,
               case c.tipo when 5 then e.nome when 4 then 'Entradas' when 3 then 'Atualiza��o de aplica��o' when 2 then 'Transfer�ncia entre rubricas' when 1 then 'Dota��o inicial' end operacao,
               f.nome nm_rubrica, f.codigo codigo_rubrica,
               h.titulo nm_projeto, g.sq_siw_solicitacao sq_projeto, j.codigo_interno cd_acordo, i.sq_siw_solicitacao sq_acordo,
               l.nome nm_label, l.sigla sg, m.sigla sg_tramite,
               n.nome nm_cc
          from fn_lancamento_rubrica                 a
               inner          join fn_lancamento_doc b on (a.sq_lancamento_doc  = b.sq_lancamento_doc)
                 inner        join fn_lancamento     c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                   inner      join siw_solicitacao   d on (c.sq_siw_solicitacao = d.sq_siw_solicitacao)
                     inner    join siw_menu          e on (d.sq_menu            = e.sq_menu)
                     inner    join siw_tramite       m on (d.sq_siw_tramite     = m.sq_siw_tramite)
               inner          join pj_rubrica        f on (a.sq_rubrica_origem  = f.sq_projeto_rubrica)
                 inner        join pj_projeto        g on (f.sq_siw_solicitacao = g.sq_siw_solicitacao)
                   left       join siw_solicitacao   h on (g.sq_siw_solicitacao = h.sq_siw_solicitacao)
                     left     join ac_acordo         i on (h.sq_solic_pai       = i.sq_siw_solicitacao)
                       left   join siw_solicitacao   j on (i.sq_siw_solicitacao = j.sq_siw_solicitacao)
                         left join siw_menu          l on (j.sq_menu            = l.sq_menu)
               inner          join ct_cc             n on (n.sq_cc              = f.sq_cc)
         where a.sq_rubrica_origem = @p_chave_aux
           and m.sigla             <> 'CA'
         group by c.vencimento, d.codigo_interno, c.sq_siw_solicitacao, c.tipo, c.vencimento, d.descricao, e.sigla, c.tipo, 
               e.nome, f.nome, f.codigo, h.titulo, g.sq_siw_solicitacao, j.codigo_interno, i.sq_siw_solicitacao, l.nome, l.sigla, m.sigla,
               n.nome
     UNION
        select sum(a.valor_total) valor, 
               c.vencimento, d.codigo_interno cd_lancamento, c.sq_siw_solicitacao sq_lancamento, c.tipo tipo_rubrica,
               dbo.to_char(c.vencimento, 'DD/MM/YYYY, HH24:MI:SS') phpdt_vencimento,
               d.descricao nm_lancamento,  e.sigla sg_lancamento_menu,
               case c.tipo when 5 then e.nome when 4 then 'Entradas' when 3 then 'Atualiza��o de aplica��o' when 2 then 'Transfer�ncia entre rubricas' when 1 then 'Dota��o inicial' end operacao,
               f.nome nm_rubrica, f.codigo codigo_rubrica,
               h.titulo nm_projeto, g.sq_siw_solicitacao sq_projeto, j.codigo_interno cd_acordo, i.sq_siw_solicitacao sq_acordo,
               l.nome nm_label, l.sigla sg, m.sigla sg_tramite,
               n.nome nm_cc
          from fn_documento_item                     a
               inner          join fn_lancamento_doc b on (a.sq_lancamento_doc  = b.sq_lancamento_doc)
                 inner        join fn_lancamento     c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                   inner      join siw_solicitacao   d on (c.sq_siw_solicitacao = d.sq_siw_solicitacao)
                     inner    join siw_menu          e on (d.sq_menu            = e.sq_menu)
                     inner    join siw_tramite       m on (d.sq_siw_tramite     = m.sq_siw_tramite)                     
               inner          join pj_rubrica        f on (a.sq_projeto_rubrica = f.sq_projeto_rubrica)
                 inner        join pj_projeto        g on (f.sq_siw_solicitacao = g.sq_siw_solicitacao)
                   left       join siw_solicitacao   h on (g.sq_siw_solicitacao = h.sq_siw_solicitacao)
                     left     join ac_acordo         i on (h.sq_solic_pai       = i.sq_siw_solicitacao)
                       left   join siw_solicitacao   j on (i.sq_siw_solicitacao = j.sq_siw_solicitacao)
                         left join siw_menu          l on (j.sq_menu            = l.sq_menu)
               inner          join ct_cc             n on (f.sq_cc              = n.sq_cc)
         where a.sq_projeto_rubrica = @p_chave_aux
           and m.sigla             <> 'CA'
         group by c.vencimento, d.codigo_interno, c.sq_siw_solicitacao, c.tipo, c.vencimento, d.descricao, e.sigla, c.tipo, 
               e.nome, f.nome, f.codigo, h.titulo, g.sq_siw_solicitacao, j.codigo_interno, i.sq_siw_solicitacao, l.nome, l.sigla, m.sigla,
               n.nome;
   End
End 

