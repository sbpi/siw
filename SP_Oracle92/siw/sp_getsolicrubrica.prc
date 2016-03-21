create or replace procedure SP_GetSolicRubrica
   (p_chave                in number,
    p_chave_aux            in number    default null,
    p_ativo                in varchar2  default null,
    p_sq_rubrica_destino   in number    default null,
    p_codigo               in varchar2  default null,
    p_aplicacao_financeira in varchar2  default null,
    p_inicio               in date      default null,
    p_fim                  in date      default null,
    p_restricao            in varchar2  default null,
    p_result               out sys_refcursor
   ) is
begin
   If p_restricao is null or p_restricao in ('VISUAL','FOLHA','SUBORDINACAO','SELECAO') Then
      open p_result for 
         select a.sq_projeto_rubrica, a.sq_cc, a.codigo, a.nome, a.descricao, a.ativo, a.sq_rubrica_pai, a.sq_unidade_medida, a.ultimo_nivel,
                a.valor_inicial, a.entrada_prevista, a.entrada_real, (a.entrada_prevista - a.entrada_real) entrada_pendente,
                a.saida_prevista, a.saida_real, (a.saida_prevista-a.saida_real) saida_pendente, a.exige_autorizacao,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.aplicacao_financeira when 'S' then 'Sim' else 'Não' end nm_aplicacao_financeira,
                case a.exige_autorizacao when 'S' then 'Sim' else 'Não' end nm_exige_autorizacao,
                montaOrdemRubrica(a.sq_projeto_rubrica, 'ordenacao') ordena,
                b.nome nm_cc, a.aplicacao_financeira,
                coalesce((select sum(w.valor_previsto)
                            from pj_rubrica_cronograma w
                                 inner join pj_rubrica x on (w.sq_projeto_rubrica = x.sq_projeto_rubrica and x.ultimo_nivel = 'S')
                           where x.sq_siw_solicitacao = a.sq_siw_solicitacao
                             and (p_restricao is null or (p_restricao is not null and p_restricao          <> 'SELECAO'))
                             and (p_chave_aux is null or (p_chave_aux is not null and w.sq_projeto_rubrica = p_chave_aux))
                             and w.sq_projeto_rubrica in (select sq_projeto_rubrica 
                                                            from pj_rubrica 
                                                          connect by prior sq_projeto_rubrica = sq_rubrica_pai 
                                                          start with sq_projeto_rubrica = a.sq_projeto_rubrica
                                                         )
                         ),0) total_previsto,
/*
                coalesce((select sum(y.valor)
                            from vw_projeto_financeiro y
                           where y.sq_projeto = a.sq_siw_solicitacao
                             and y.sg_tramite = 'AT'
                             and (p_restricao is null or (p_restricao is not null and p_restricao          <> 'SELECAO'))
                             and (p_chave_aux is null or (p_chave_aux is not null and y.sq_projeto_rubrica = p_chave_aux))
                             and y.sq_projeto_rubrica in (select sq_projeto_rubrica 
                                                            from pj_rubrica 
                                                          connect by prior sq_projeto_rubrica = sq_rubrica_pai 
                                                          start with sq_projeto_rubrica = a.sq_projeto_rubrica
                                                         )
                         ),0) total_real,
*/
                coalesce((select sum(w.valor_real)
                            from pj_rubrica_cronograma w
                                 inner join pj_rubrica x on (w.sq_projeto_rubrica = x.sq_projeto_rubrica and x.ultimo_nivel = 'S')
                           where x.sq_siw_solicitacao = a.sq_siw_solicitacao
                             and (p_restricao is null or (p_restricao is not null and p_restricao          <> 'SELECAO' and p_restricao <> 'VISUAL'))
                             and (p_chave_aux is null or (p_chave_aux is not null and w.sq_projeto_rubrica = p_chave_aux))
                             and w.sq_projeto_rubrica in (select sq_projeto_rubrica 
                                                            from pj_rubrica 
                                                          connect by prior sq_projeto_rubrica = sq_rubrica_pai 
                                                          start with sq_projeto_rubrica = a.sq_projeto_rubrica
                                                         )
                         ),0) total_real,
                c.quantidade,
                coalesce(i.qt_filhos,0) as qt_filhos,
                d.sq_unidade_medida, d.nome nm_unidade, d.sigla sg_unidade
           from pj_rubrica                      a
                inner join ct_cc                b on (a.sq_cc              = b.sq_cc)
                left  join co_unidade_medida    d on (a.sq_unidade_medida  = d.sq_unidade_medida)
                left  join (select sum(x.quantidade) as quantidade, x.sq_projeto_rubrica
                              from pj_rubrica_cronograma x
                                   inner join pj_rubrica y on (x.sq_projeto_rubrica = y.sq_projeto_rubrica and y.ultimo_nivel = 'S')
                             where (p_chave   is null or (p_chave   is not null and y.sq_siw_solicitacao   = p_chave))
                               and ((p_inicio is null) or (p_inicio is not null and ((x.inicio  between p_inicio and p_fim) or
                                                                                     (x.fim     between p_inicio and p_fim) or
                                                                                     (p_inicio  between x.inicio and x.fim) or
                                                                                     (p_fim     between x.inicio and x.fim)
                                                                                     )
                                                           )
                                   )
                            group by x.sq_projeto_rubrica
                           )                    c on (a.sq_projeto_rubrica = c.sq_projeto_rubrica)
                left  join (select x.sq_projeto_rubrica, count(y.sq_projeto_rubrica) qt_filhos
                              from pj_rubrica            x
                                   inner join pj_rubrica y on (x.sq_projeto_rubrica = y.sq_rubrica_pai)
                             where (p_chave   is null or (p_chave   is not null and y.sq_siw_solicitacao   = p_chave))
                               and x.sq_projeto_rubrica = coalesce(p_chave_aux, x.sq_projeto_rubrica)
                            group by x.sq_projeto_rubrica
                           )                    i on (i.sq_projeto_rubrica = a.sq_projeto_rubrica)
          where (p_chave                is null or (p_chave                is not null and a.sq_siw_solicitacao   = p_chave))
            and (p_chave_aux            is null or (p_chave_aux            is not null and a.sq_projeto_rubrica   = p_chave_aux))
            and (p_ativo                is null or (p_ativo                is not null and a.ativo                = p_ativo))
            and (p_sq_rubrica_destino   is null or (p_sq_rubrica_destino   is not null and a.sq_projeto_rubrica   <> p_sq_rubrica_destino))
            and (p_codigo               is null or (p_codigo               is not null and a.codigo               = p_codigo))
            and (p_aplicacao_financeira is null or (p_aplicacao_financeira is not null and a.aplicacao_financeira = p_aplicacao_financeira))
            and (p_inicio               is null or (p_inicio               is not null and c.sq_projeto_rubrica   is not null))
            and (p_restricao            is null or
                 p_restricao            <> 'SUBORDINACAO' or
                 (p_restricao           = 'SUBORDINACAO' and a.ultimo_nivel = 'N')
                );
   Elsif p_restricao = 'ARVORE' Then
      -- Recupera a árvore das rubricas
      open p_result for 
         select a.sq_projeto_rubrica, a.sq_cc, a.codigo, a.nome, a.descricao, a.ativo, a.sq_rubrica_pai, a.sq_unidade_medida, a.ultimo_nivel,
                a.valor_inicial, a.entrada_prevista, a.entrada_real, (a.entrada_prevista - a.entrada_real) entrada_pendente,
                a.saida_prevista, a.saida_real, (a.saida_prevista-a.saida_real) saida_pendente,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo, a.exige_autorizacao,
                case a.aplicacao_financeira when 'S' then 'Sim' else 'Não' end nm_aplicacao_financeira,
                case a.exige_autorizacao when 'S' then 'Sim' else 'Não' end nm_exige_autorizacao,
                b.nome nm_cc, a.aplicacao_financeira,
                c.total_previsto, c.total_real, c.quantidade,
                coalesce(i.qt_filhos,0) as qt_filhos
           from pj_rubrica                      a
                inner join ct_cc                b on (a.sq_cc              = b.sq_cc)
                left  join (select sum(x.valor_previsto) as total_previsto, 
                                   sum(x.valor_real) as total_real, 
                                   sum(x.quantidade) as quantidade,
                                   x.sq_projeto_rubrica
                              from pj_rubrica_cronograma x
                                   inner join pj_rubrica y on (x.sq_projeto_rubrica = y.sq_projeto_rubrica)
                             where y.sq_siw_solicitacao = p_chave
                               and ((p_inicio is null) or (p_inicio is not null and ((x.inicio  between p_inicio and p_fim) or
                                                                                     (x.fim     between p_inicio and p_fim) or
                                                                                     (p_inicio  between x.inicio and x.fim) or
                                                                                     (p_fim     between x.inicio and x.fim)
                                                                                     )
                                                           )
                                   )
                            group by x.sq_projeto_rubrica
                           )                    c on (a.sq_projeto_rubrica = c.sq_projeto_rubrica)
                left  join (select x.sq_projeto_rubrica, count(y.sq_projeto_rubrica) qt_filhos
                              from pj_rubrica            x
                                   inner join pj_rubrica y on (x.sq_projeto_rubrica = y.sq_projeto_rubrica)
                             where y.sq_siw_solicitacao = p_chave
                               and x.sq_projeto_rubrica = coalesce(p_chave_aux, x.sq_projeto_rubrica)
                            group by x.sq_projeto_rubrica
                           )               i on (i.sq_projeto_rubrica = a.sq_rubrica_pai)
          where a.sq_siw_solicitacao = p_chave
            and a.ultimo_nivel       = 'N'
            and (p_sq_rubrica_destino is null or (p_sq_rubrica_destino is not null and a.sq_projeto_rubrica <> p_sq_rubrica_destino))
         connect by prior a.sq_projeto_rubrica = a.sq_rubrica_pai
         start with coalesce(a.sq_rubrica_pai,0) = coalesce(p_chave_aux,0)
         order by montaOrdemRubrica(a.sq_projeto_rubrica, 'ordenacao');
   Elsif p_restricao = 'PJEXECS' or p_restricao = 'PJEXECN' Then
      open p_result for 
         select a.sq_projeto_rubrica, a.codigo, a.nome, a.descricao, a.ativo, a.sq_rubrica_pai, a.ultimo_nivel, a.aplicacao_financeira,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                montaOrdemRubrica(a.sq_projeto_rubrica, 'ordenacao') ordena,
                coalesce((select sum(case x.aplicacao_financeira when 'S' then -1*w.valor_previsto else w.valor_previsto end)
                            from pj_rubrica_cronograma w
                                 inner join pj_rubrica x on (w.sq_projeto_rubrica = x.sq_projeto_rubrica)
                           where x.sq_siw_solicitacao = coalesce(p_chave, sq_siw_solicitacao)
                             and w.sq_projeto_rubrica = coalesce(p_chave_aux, w.sq_projeto_rubrica)
                             and w.sq_projeto_rubrica in (select sq_projeto_rubrica 
                                                          from pj_rubrica 
                                                         where sq_siw_solicitacao = coalesce(p_chave, sq_siw_solicitacao)
                                                           and sq_projeto_rubrica = coalesce(p_chave_aux, sq_projeto_rubrica)
                                                        connect by prior sq_projeto_rubrica = sq_rubrica_pai 
                                                        start with sq_projeto_rubrica = a.sq_projeto_rubrica
                                                       )
                         ),0) total_previsto
           from pj_rubrica                   a
          where (p_chave                is null or (p_chave                is not null and a.sq_siw_solicitacao   = p_chave))
            and (p_chave_aux            is null or (p_chave_aux            is not null and a.sq_projeto_rubrica   = p_chave_aux))
            and (p_ativo                is null or (p_ativo                is not null and a.ativo                = p_ativo))
            and (p_sq_rubrica_destino   is null or (p_sq_rubrica_destino   is not null and a.sq_projeto_rubrica   <> p_sq_rubrica_destino))
            and (p_codigo               is null or (p_codigo               is not null and a.codigo               = p_codigo))
            and (p_aplicacao_financeira is null or (p_aplicacao_financeira is not null and a.aplicacao_financeira = p_aplicacao_financeira));
   Elsif p_restricao = 'PJFINS' or p_restricao = 'PJFINN' Then
      open p_result for 
         select sq_projeto_rubrica, sg_fn_moeda, valor, aplicacao_financeira,
                retornaHierarquiaRubrica(sq_projeto_rubrica, 'PAIS') lista
           from (select w.sq_projeto_rubrica, w.sg_fn_moeda, w.aplicacao_financeira,
                        sum(case when substr(w.sg_menu,1,3) = 'FNR' then trunc(-1*w.valor,2) else trunc(w.valor,2) end) valor
                   from vw_projeto_financeiro   w
                  where w.sq_projeto         = p_chave
                    and (p_restricao  = 'PJFINN' or (p_restricao = 'PJFINS' and w.sg_tramite = 'AT'))
                    and (p_aplicacao_financeira is null or (p_aplicacao_financeira is not null and w.aplicacao_financeira = p_aplicacao_financeira))
                    and (p_inicio     is null or 
                         (p_inicio    is not null and ((w.sg_tramite = 'AT'  and w.quitacao   between p_inicio and p_fim) or 
                                                       (w.sg_tramite <> 'AT' and w.vencimento between p_inicio and p_fim)
                                                      )
                         )
                        )
                 group by w.sq_projeto_rubrica, w.sg_fn_moeda, w.aplicacao_financeira
                );
   Elsif p_restricao = 'PJEXECLS' or p_restricao = 'PJEXECLN' Then
      open p_result for 
         select a.tipo, a.sq_projeto, a.cd_projeto, a.sq_pj_moeda, a.sg_pj_moeda, 
                a.sq_projeto_rubrica, a.nm_rubrica, montaordemrubrica(a.sq_projeto_rubrica,'ORDENACAO') or_rubrica,
                a.sq_financeiro, a.cd_financeiro, 
                case when substr(a.sg_menu,1,3) = 'FNR' then trunc(-1*a.valor,2) else trunc(a.valor,2) end valor,
                a.sq_fn_moeda, a.sg_fn_moeda, d1.simbolo sb_fn_moeda,
                a.fn_valor, a.fn_sq_moeda, a.fn_sg_moeda, a.fn_sb_moeda, a.ordem or_item,
                a.exige_brl, a.fator_conversao,
                a.brl_taxa_compra_data, a.brl_taxa_compra, a.brl_valor_compra, 
                a.brl_taxa_venda_data,  a.brl_taxa_venda,  a.brl_valor_venda,
                codigo2numero(a.cd_financeiro) or_financeiro,
                b.codigo cd_rubrica, b.nome nm_rubrica,
                b1.codigo cd_rubrica_pai, b1.nome nm_rubrica_pai,
                c.simbolo sb_moeda,
                a.ds_financeiro descricao, d.inicio, 
                e.sigla sg_tramite,
                f.sigla sg_menu,
                g.aviso_prox_conc, g.quitacao, g.vencimento,
                cast(d.fim as date)-cast(g.dias_aviso as integer) as aviso,
                g1.nome nm_forma_pagamento,
                g2.sq_pessoa, g2.nome nm_pessoa, coalesce(g3.cpf, g4.cnpj) cd_pessoa,
                h.data dt_emissao, h.numero, h.valor valor_doc,
                i.sigla sg_tipo_documento, i.nome nm_tipo_documento
           from vw_projeto_financeiro              a
                inner     join pj_rubrica          b on (a.sq_projeto_rubrica = b.sq_projeto_rubrica)
                  left    join pj_rubrica         b1 on (b.sq_rubrica_pai     = b1.sq_projeto_rubrica)
                inner     join co_moeda            c on (a.sq_fn_moeda        = c.sq_moeda)
                inner     join siw_solicitacao     d on (a.sq_financeiro      = d.sq_siw_solicitacao)
                  inner   join co_moeda           d1 on (d.sq_moeda           = d1.sq_moeda)
                  inner   join siw_tramite         e on (d.sq_siw_tramite     = e.sq_siw_tramite)
                  inner   join siw_menu            f on (d.sq_menu            = f.sq_menu)
                  inner   join fn_lancamento       g on (d.sq_siw_solicitacao = g.sq_siw_solicitacao)
                    inner join co_forma_pagamento g1 on (g.sq_forma_pagamento = g1.sq_forma_pagamento)
                    inner join co_pessoa          g2 on (g.pessoa             = g2.sq_pessoa)
                    left  join co_pessoa_fisica   g3 on (g.pessoa             = g3.sq_pessoa)
                    left  join co_pessoa_juridica g4 on (g.pessoa             = g4.sq_pessoa)
                  left    join fn_lancamento_doc   h on (d.sq_siw_solicitacao = h.sq_siw_solicitacao)
                    left join fn_tipo_documento    i on (h.sq_tipo_documento  = i.sq_tipo_documento)
          where a.sq_projeto  = p_chave
            and (p_restricao  = 'PJEXECLN' or (p_restricao = 'PJEXECLS' and a.sg_tramite = 'AT'))
            and (p_inicio     is null or 
                 (p_inicio    is not null and ((a.sg_tramite =  'AT' and a.quitacao   between p_inicio and p_fim) or 
                                           	   (a.sg_tramite <> 'AT' and a.vencimento between p_inicio and p_fim)
                                              )
                 )
                )
            and (p_chave_aux  is null or 
                 (p_chave_aux is not null and a.sq_projeto_rubrica in (select sq_projeto_rubrica 
                                                                         from pj_rubrica 
                                                                        where sq_siw_solicitacao = p_chave
                                                                       connect by prior sq_projeto_rubrica = sq_rubrica_pai 
                                                                       start with sq_projeto_rubrica = p_chave_aux
                                                                      )
	               )
                )
            and (p_aplicacao_financeira is null or (p_aplicacao_financeira is not null and b.aplicacao_financeira = p_aplicacao_financeira));
   Elsif p_restricao = 'FNREXECMP' Then
      open p_result for 
         select a.sq_projeto_rubrica, a.codigo, a.nome, a.descricao, a.ativo, a.sq_rubrica_pai, a.ultimo_nivel, a.aplicacao_financeira,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                montaOrdemRubrica(a.sq_projeto_rubrica, 'ordenacao') ordena,
                c.mes, c.valor
           from pj_rubrica                 a
                left  join (select w.sq_projeto_rubrica, to_char(w.quitacao,'yyyymm') mes,
                                   sum(case when substr(w.sg_menu,1,3) = 'FNR' then trunc(-1*w.valor,2) else trunc(w.valor,2) end) valor
                              from siw_solicitacao                  x
                                   inner join VW_PROJETO_FINANCEIRO w on (w.sq_projeto = x.sq_siw_solicitacao)
                             where x.sq_siw_solicitacao = p_chave 
                               and w.sq_projeto_rubrica = coalesce(p_chave_aux,w.sq_projeto_rubrica)
                               and w.sq_fn_moeda        = x.sq_moeda 
                               and w.sg_tramite         = 'AT'
                               and w.quitacao           between p_inicio and p_fim
                            group by w.sq_projeto_rubrica, to_char(w.quitacao,'yyyymm')
                           )              c on (a.sq_projeto_rubrica = c.sq_projeto_rubrica)
          where a.sq_siw_solicitacao   = p_chave
            and a.ativo                = 'S'
            and (p_chave_aux            is null or (p_chave_aux            is not null and a.sq_projeto_rubrica   = p_chave_aux))
            and (p_aplicacao_financeira is null or (p_aplicacao_financeira is not null and a.aplicacao_financeira = p_aplicacao_financeira));
   Elsif p_restricao = 'PDFINANC' Then
      open p_result for 
         select distinct a.sq_projeto_rubrica, a.sq_cc, a.codigo, a.nome, a.descricao, a.ativo,
                a.valor_inicial, a.entrada_prevista, a.entrada_real, (a.entrada_prevista - a.entrada_real) entrada_pendente,
                a.saida_prevista, a.saida_real, (a.saida_prevista-a.saida_real) saida_pendente, a.exige_autorizacao,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.aplicacao_financeira when 'S' then 'Sim' else 'Não' end nm_aplicacao_financeira,
                case a.exige_autorizacao when 'S' then 'Sim' else 'Não' end nm_exige_autorizacao,
                b.nome nm_cc, a.aplicacao_financeira,
                c.total_previsto, c.total_real, c.quantidade
           from pj_rubrica                       a
                inner join ct_cc                 b on (a.sq_cc              = b.sq_cc)
                left  join (select sum(x.valor_previsto) as total_previsto, 
                                   sum(x.valor_real) as total_real, 
                                   sum(x.quantidade) as quantidade,
                                   x.sq_projeto_rubrica
                              from pj_rubrica_cronograma x
                                   inner join pj_rubrica y on (x.sq_projeto_rubrica = y.sq_projeto_rubrica)
                             where y.sq_siw_solicitacao = p_chave
                               and ((p_inicio is null) or (p_inicio is not null and ((x.inicio  between p_inicio and p_fim) or
                                                                                     (x.fim     between p_inicio and p_fim) or
                                                                                     (p_inicio  between x.inicio and x.fim) or
                                                                                     (p_fim     between x.inicio and x.fim)
                                                                                     )
                                                           )
                                   )
                            group by x.sq_projeto_rubrica
                           )                     c on (a.sq_projeto_rubrica = c.sq_projeto_rubrica)
                inner join pd_vinculo_financeiro d on (a.sq_projeto_rubrica = d.sq_projeto_rubrica and
                                                       (p_codigo            = 'T' or
                                                        (p_codigo           <> 'T' and
                                                         ((p_codigo         = 'D' and d.diaria        = 'S') or
                                                          (p_codigo         = 'H' and d.hospedagem    = 'S')or
                                                          (p_codigo         = 'V' and d.veiculo       = 'S')or
                                                          (p_codigo         = 'S' and d.seguro        = 'S')or
                                                          (p_codigo         = 'B' and d.bilhete       = 'S')or
                                                          (p_codigo         = 'R' and d.ressarcimento = 'S')
                                                         )
                                                        )
                                                       )
                                                      )
          where a.sq_siw_solicitacao   = p_chave;
             
   Elsif p_restricao = 'CLFINANC' Then
      open p_result for 
         select distinct a.sq_projeto_rubrica, a.sq_cc, a.codigo, a.nome, a.descricao, a.ativo,
                a.valor_inicial, a.entrada_prevista, a.entrada_real, (a.entrada_prevista - a.entrada_real) entrada_pendente,
                a.saida_prevista, a.saida_real, (a.saida_prevista-a.saida_real) saida_pendente, a.exige_autorizacao,
                case a.ativo when 'S' then 'Sim' else 'Não' end nm_ativo,
                case a.aplicacao_financeira when 'S' then 'Sim' else 'Não' end nm_aplicacao_financeira,
                case a.exige_autorizacao when 'S' then 'Sim' else 'Não' end nm_exige_autorizacao,
                b.nome nm_cc, a.aplicacao_financeira,
                c.total_previsto, c.total_real, c.quantidade
           from pj_rubrica                       a
                inner join ct_cc                 b on (a.sq_cc              = b.sq_cc)
                left  join (select sum(x.valor_previsto) as total_previsto, 
                                   sum(x.valor_real) as total_real, 
                                   sum(x.quantidade) as quantidade,
                                   x.sq_projeto_rubrica
                              from pj_rubrica_cronograma x
                                   inner join pj_rubrica y on (x.sq_projeto_rubrica = y.sq_projeto_rubrica)
                             where y.sq_siw_solicitacao = p_chave
                               and ((p_inicio is null) or (p_inicio is not null and ((x.inicio  between p_inicio and p_fim) or
                                                                                     (x.fim     between p_inicio and p_fim) or
                                                                                     (p_inicio  between x.inicio and x.fim) or
                                                                                     (p_fim     between x.inicio and x.fim)
                                                                                     )
                                                           )
                                   )
                            group by x.sq_projeto_rubrica
                           )                     c on (a.sq_projeto_rubrica = c.sq_projeto_rubrica)
                inner join cl_vinculo_financeiro d on (a.sq_projeto_rubrica = d.sq_projeto_rubrica and
                                                       (p_codigo            = 'T' or
                                                        (p_codigo           <> 'T' and
                                                         ((p_codigo         = 'C' and d.consumo    = 'S') or
                                                          (p_codigo         = 'P' and d.permanente = 'S')or
                                                          (p_codigo         = 'S' and d.servico    = 'S')or
                                                          (p_codigo         = 'O' and d.outros     = 'S')
                                                         )
                                                        )
                                                       )
                                                      )
          where a.sq_siw_solicitacao   = p_chave
            and d.sq_menu              = p_chave_aux;
             
   Elsif p_restricao = 'FICHA' Then
     open p_result for    
        select sum(a.valor) valor, 
               c.vencimento, d.codigo_interno cd_lancamento, c.sq_siw_solicitacao sq_lancamento, c.tipo tipo_rubrica,
               to_char(c.vencimento, 'DD/MM/YYYY, HH24:MI:SS') phpdt_vencimento,
               d.descricao nm_lancamento, e.sigla sg_lancamento_menu,
               case c.tipo when 5 then e.nome 
                           when 4 then case when lower(d.descricao) like 'devolução%sv%' then 'Devolução de diárias' else 'Entradas' end 
                           when 3 then 'Atualização de aplicação' 
                           when 2 then 'Transferência entre rubricas' 
                           when 1 then 'Dotação inicial' 
               end as operacao,
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
         where a.sq_rubrica_origem = p_chave_aux
           and m.sigla             <> 'CA'
         group by c.vencimento, d.codigo_interno, c.sq_siw_solicitacao, c.tipo, c.vencimento, d.descricao, e.sigla, c.tipo, 
               e.nome, f.nome, f.codigo, h.titulo, g.sq_siw_solicitacao, j.codigo_interno, i.sq_siw_solicitacao, l.nome, l.sigla, m.sigla,
               n.nome
     UNION
        select sum(a.valor_total) valor, 
               c.vencimento, d.codigo_interno cd_lancamento, c.sq_siw_solicitacao sq_lancamento, c.tipo tipo_rubrica,
               to_char(c.vencimento, 'DD/MM/YYYY, HH24:MI:SS') phpdt_vencimento,
               d.descricao nm_lancamento,  e.sigla sg_lancamento_menu,
               case c.tipo when 5 then e.nome 
                           when 4 then case when lower(d.descricao) like 'devolução%sv%' then 'Devolução de diárias' else 'Entradas' end 
                           when 3 then 'Atualização de aplicação' 
                           when 2 then 'Transferência entre rubricas' 
                           when 1 then 'Dotação inicial' 
               end as operacao,
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
         where a.sq_projeto_rubrica = p_chave_aux
           and m.sigla             <> 'CA'
         group by c.vencimento, d.codigo_interno, c.sq_siw_solicitacao, c.tipo, c.vencimento, d.descricao, e.sigla, c.tipo, 
               e.nome, f.nome, f.codigo, h.titulo, g.sq_siw_solicitacao, j.codigo_interno, i.sq_siw_solicitacao, l.nome, l.sigla, m.sigla,
               n.nome;
   End If;  
End SP_GetSolicRubrica;
/
