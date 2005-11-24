create or replace procedure SP_GetAcaoPPA_IS
   (p_cliente   in  number,
    p_ano       in  number,
    p_programa  in  varchar2  default null,
    p_acao      in  varchar2  default null,
    p_subacao   in  varchar2  default null,
    p_unidade   in  varchar2  default null,
    p_restricao in  varchar2  default null,
    p_chave     in  number    default null,
    p_nome      in  varchar2  default null,
    p_result    out siw.siw.sys_refcursor) is
begin
   If p_restricao is null or p_restricao in ('CONSULTA','IDENTIFICACAO') Then
      open p_result for      
         select a.cd_acao, a.cd_programa, a.cd_programa||a.cd_acao||min(a.cd_subacao)||a.cd_unidade chave, a.cd_tipo_acao, 
                a.cd_unidade||a.cd_programa||a.cd_acao codigo,
                a.cd_produto, a.cd_unidade_medida, a.cd_estagio, 
                a.cd_andamento, a.cd_cronograma, a.cd_orgao, a.cd_tipo_orgao, a.cd_unidade, 
                a.cd_tipo_unidade, a.descricao_acao, b.nome nm_tipo_acao,
                c.nome ds_uni_medida, d.nome ds_orgao, d.sigla sg_orgao, e.nome nm_tipo_orgao, f.nome ds_unidade,
                f.cd_tipo_unidade tp_unidade, g.cd_funcao, g.cd_subfuncao, g.valor_ano_corrente,
                g.valor_total, g.valor_ano_anterior, h.nome ds_funcao, 
                i.descricao ds_subfuncao, j.nome ds_esfera, k.nm_coordenador responsavel, k.fn_coordenador telefone,
                k.em_coordenador email, k.sq_unidade sq_unidade_adm, k.sq_siw_solicitacao, 
                m.nome ds_programa, m.cd_programa, sum(a.empenhado) empenhado, sum(a.aprovado) aprovado, sum(a.liquidado) liquidado,
                Nvl(k.sigla,'---') sg_tramite, upper(k.nm_coordenador) nm_coordenador,
                n.previsao_ano, n.atual_ano, n.real_ano, nvl(n.flag_alteracao,n.flag_inclusao) dt_carga_financ
           from is_sig_acao            a,
                is_sig_tipo_acao       b,
                is_sig_unidade_medida  c,
                is_sig_orgao           d,
                is_sig_tipo_orgao      e,
                is_sig_unidade         f,
                is_ppa_acao            g,
                is_ppa_funcao          h,
                is_ppa_subfuncao       i,
                is_ppa_esfera          j,
                is_sig_programa        m,
                (select l.cd_programa, l.cd_acao, l.cd_subacao, l.cd_unidade,
                                        l.ano, l.cliente, l.nm_coordenador, l.fn_coordenador,
                                        l.em_coordenador, l.sq_unidade, l.sq_siw_solicitacao,
                                        l2.sigla
                                   from is_acao                        l,
                                        siw.siw_solicitacao l1, 
                                        siw.siw_tramite     l2 
                                  where (l.sq_siw_solicitacao = l1.sq_siw_solicitacao) 
                                    and (l1.sq_siw_tramite    = l2.sq_siw_tramite and
                                         'CA'                 <> Nvl(l2.sigla,'---') and
                                         'CONSULTA'           <> Nvl(p_restricao,'a'))
                                )       k,
                is_sig_dado_financeiro  n                 
          where (a.cd_tipo_acao      = b.cd_tipo_acao (+))
            and (a.cd_unidade_medida = c.cd_unidade_medida (+))
            and (a.cd_orgao          = d.cd_orgao        and
                 a.ano               = d.ano)
            and (a.cd_tipo_orgao     = e.cd_tipo_orgao)   
            and (a.cd_unidade        = f.cd_unidade      and
                 a.cd_orgao          = f.cd_orgao        and
                 a.cd_tipo_unidade   = f.cd_tipo_unidade and
                 a.ano               = f.ano)
            and (a.cd_acao_ppa       = g.cd_acao_ppa     and
                 a.cd_programa       = g.cd_programa     and
                 a.cliente           = g.cliente         and
                 a.ano               = g.ano)   
            and (g.cd_funcao         = h.cd_funcao)
            and (g.cd_subfuncao      = i.cd_subfuncao (+))
            and (g.cd_esfera         = j.cd_esfera (+))   
            and (a.cd_programa       = m.cd_programa     and
                 a.ano               = m.ano             and
                 a.cliente           = m.cliente)
            and (a.cd_programa     = k.cd_programa (+)and
                 a.cd_acao         = k.cd_acao (+)    and
                 a.cd_subacao      = k.cd_subacao (+) and
                 a.cd_unidade      = k.cd_unidade (+) and
                 a.ano             = k.ano (+)        and
                 a.cliente         = k.cliente (+))
            and (a.cd_programa     = n.cd_programa (+)and
                 a.cd_acao         = n.cd_acao (+)    and
                 a.cd_subacao      = n.cd_subacao (+) and
                 a.ano             = n.ano (+)        and
                 a.cliente         = n.cliente (+))
            and a.cliente = p_cliente
            and a.ano     = p_ano
            and (p_programa  is null or (p_programa  is not null and a.cd_programa     = p_programa))
            and (p_acao      is null or (p_acao      is not null and a.cd_acao         = p_acao))
            and (p_subacao   is null or (p_subacao   is not null and a.cd_localizador  = p_subacao))
            and (p_unidade   is null or (p_unidade   is not null and a.cd_unidade      = p_unidade))
            and (p_nome      is null or (p_nome      is not null and siw.acentos(a.descricao_acao,null) like '%'||siw.acentos(p_nome,null)||'%'))
            and ('CONSULTA'  = Nvl(p_restricao,'CONSULTA') or 
                 (p_restricao = 'IDENTIFICACAO' and 
                  a.cd_programa||a.cd_acao||a.cd_unidade not in (select n.cd_programa||n.cd_acao||n.cd_unidade 
                                                                   from is_acao                          n,
                                                                        siw.siw_solicitacao o,
                                                                        siw.siw_tramite     p
                                                                  where (n.sq_siw_solicitacao = o.sq_siw_solicitacao)
                                                                    and (o.sq_siw_tramite     = p.sq_siw_tramite and
                                                                         'CA'                 <> Nvl(p.sigla,'-'))
                                                                    and n.cliente = p_cliente 
                                                                    and n.ano = p_ano 
                                                                    and n.cd_acao is not null
                                                                )
                 )
                )
       group by a.cd_acao, a.cd_programa, a.cd_programa||a.cd_acao||a.cd_unidade, a.cd_tipo_acao, 
                a.cd_produto, a.cd_unidade_medida, a.cd_estagio, 
                a.cd_andamento, a.cd_cronograma, a.cd_orgao, a.cd_tipo_orgao, a.cd_unidade, 
                a.cd_tipo_unidade, a.descricao_acao, b.nome,
                c.nome, d.nome, d.sigla, e.nome, f.nome,
                f.cd_tipo_unidade, g.cd_funcao, g.cd_subfuncao, g.valor_ano_corrente,
                g.valor_total, g.valor_ano_anterior, h.nome, 
                i.descricao, j.nome, k.nm_coordenador, k.fn_coordenador,
                k.em_coordenador, k.sq_unidade, m.nome, m.cd_programa, k.sq_siw_solicitacao, k.sigla,
                n.previsao_ano, n.atual_ano, n.real_ano, n.flag_alteracao, n.flag_inclusao;
   Elsif p_restricao = 'FINANCIAMENTO' Then
      open p_result for 
         select a.cd_acao, a.cd_programa, a.cd_programa||a.cd_acao||min(a.cd_subacao)||a.cd_unidade chave, a.cd_tipo_acao, 
                a.cd_produto, a.cd_unidade_medida, a.cd_estagio, 
                a.cd_andamento, a.cd_cronograma, a.cd_orgao, a.cd_tipo_orgao, a.cd_unidade, 
                a.cd_tipo_unidade, a.descricao_acao, b.nome nm_tipo_acao,
                c.nome ds_uni_medida, d.nome ds_orgao, d.sigla sg_orgao, e.nome nm_tipo_orgao, f.nome ds_unidade,
                f.cd_tipo_unidade tp_unidade, g.cd_funcao, g.cd_subfuncao, g.valor_ano_corrente,
                g.valor_total, g.valor_ano_anterior, h.nome ds_funcao, 
                i.descricao ds_subfuncao, j.nome ds_esfera, l.nm_coordenador responsavel, l.fn_coordenador telefone,
                l.em_coordenador email, l.sq_unidade sq_unidade_adm, m.nome ds_programa, m.cd_programa
           from is_sig_acao                            a,
                is_sig_tipo_acao      b,
                is_sig_unidade_medida c,
                is_sig_orgao          d,
                is_sig_tipo_orgao     e,
                is_sig_unidade        f,
                is_ppa_acao           g,
                is_ppa_funcao         h,
                is_ppa_subfuncao      i,
                is_ppa_esfera         j,
                (select l.cd_programa, l.cd_acao, l.cd_subacao, l.cd_unidade,
                                        l.ano, l.cliente, l.nm_coordenador, l.fn_coordenador,
                                        l.em_coordenador, l.sq_unidade, l.sq_siw_solicitacao,
                                        l2.sigla
                                   from is_acao                        l,
                                        siw.siw_solicitacao l1,
                                        siw.siw_tramite     l2
                                  where (l.sq_siw_solicitacao = l1.sq_siw_solicitacao)
                                    and  (l1.sq_siw_tramite    = l2.sq_siw_tramite and
                                          'CA'                <> Nvl(l2.sigla,'---'))
                                )                      l, 
                is_sig_programa       m
          where (a.cd_tipo_acao      = b.cd_tipo_acao (+))
            and (a.cd_unidade_medida = c.cd_unidade_medida (+))
            and (a.cd_orgao          = d.cd_orgao        and
                 a.ano               = d.ano)
            and (a.cd_tipo_orgao     = e.cd_tipo_orgao)   
            and (a.cd_unidade        = f.cd_unidade      and
                 a.cd_orgao          = f.cd_orgao        and
                 a.cd_tipo_unidade   = f.cd_tipo_unidade and
                 a.ano               = f.ano)
            and (a.cd_acao_ppa       = g.cd_acao_ppa     and
                 a.cd_programa       = g.cd_programa     and
                 a.cliente           = g.cliente         and
                 a.ano               = g.ano)   
            and (g.cd_funcao         = h.cd_funcao)
            and (g.cd_subfuncao      = i.cd_subfuncao)
            and (g.cd_esfera         = j.cd_esfera)   
            and (a.cd_programa       = l.cd_programa (+) and
                 a.cd_acao           = l.cd_acao (+)     and
                 a.cd_unidade        = l.cd_unidade (+)  and
                 a.ano               = l.ano (+)         and
                 a.cliente           = l.cliente (+))    
            and (a.cd_programa       = m.cd_programa     and
                 a.ano               = m.ano             and
                 a.cliente           = m.cliente) 
            and a.cliente = p_cliente
            and a.ano     = p_ano
            and (p_nome      is null or (p_nome      is not null and siw.acentos(a.descricao_acao,null) like '%'||siw.acentos(p_nome,null)||'%'))
            and (a.cd_programa||a.cd_acao||a.cd_unidade not in (select n.cd_programa||n.cd_acao||n.cd_unidade
                                                                  from is_sig_acao n 
                                                                 where n.cliente = p_cliente 
                                                                   and n.ano = p_ano
                                                                   and n.cd_programa = p_programa
                                                                   and n.cd_acao     = p_acao
                                                                   and n.cd_unidade  = p_unidade))
            and(a.cd_programa||a.cd_acao                not in (select o.cd_programa||o.cd_acao
                                                                 from is_acao_financ o
                                                                where o.sq_siw_solicitacao = p_chave
                                                                  and o.cliente            = p_cliente
                                                                  and o.ano                = p_ano))
       group by a.cd_acao, a.cd_programa, a.cd_programa||a.cd_acao||a.cd_unidade, a.cd_tipo_acao, 
                a.cd_produto, a.cd_unidade_medida, a.cd_estagio, 
                a.cd_andamento, a.cd_cronograma, a.cd_orgao, a.cd_tipo_orgao, a.cd_unidade, 
                a.cd_tipo_unidade, a.descricao_acao, b.nome,
                c.nome, d.nome, d.sigla, e.nome, f.nome,
                f.cd_tipo_unidade, g.cd_funcao, g.cd_subfuncao, g.valor_ano_corrente,
                g.valor_total, g.valor_ano_anterior, h.nome, 
                i.descricao, j.nome, l.nm_coordenador, l.fn_coordenador,
                l.em_coordenador, l.sq_unidade, m.nome, m.cd_programa;
   End If;
end SP_GetAcaoPPA_IS;
/
