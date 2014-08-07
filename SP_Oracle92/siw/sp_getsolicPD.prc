create or replace procedure SP_GetSolicPD
   (p_menu         in number,
    p_pessoa       in number,
    p_restricao    in varchar2 default null,
    p_tipo         in number,
    p_ini_i        in date     default null,
    p_ini_f        in date     default null,
    p_fim_i        in date     default null,
    p_fim_f        in date     default null,
    p_atraso       in varchar2 default null,
    p_solicitante  in number   default null,
    p_unidade      in number   default null,
    p_prioridade   in number   default null,
    p_ativo        in varchar2 default null,
    p_proponente   in varchar2 default null,
    p_chave        in number   default null,
    p_assunto      in varchar2 default null,
    p_pais         in number   default null,
    p_regiao       in number   default null,
    p_uf           in varchar2 default null,
    p_cidade       in number   default null,
    p_usu_resp     in number   default null,
    p_uorg_resp    in number   default null,
    p_palavra      in varchar2 default null,
    p_prazo        in number   default null,
    p_fase         in varchar2 default null,
    p_sqcc         in number   default null,
    p_projeto      in number   default null,
    p_atividade    in number   default null,
    p_sq_acao_ppa  in varchar2 default null,
    p_sq_orprior   in number   default null,
    p_empenho      in varchar2 default null,
    p_processo     in varchar2 default null,
    p_result       out sys_refcursor) is
    
    l_item       varchar2(18);
    l_fase       varchar2(200) := p_fase ||',';
    x_fase       varchar2(200) := '';
    
    l_resp_unid  varchar2(10000) :='';
    
    -- cursor que recupera as unidades nas quais o usuário informado é titular ou substituto
    cursor c_unidades_resp is
      select distinct sq_unidade
        from eo_unidade a
      start with sq_unidade in (select sq_unidade
                                  from eo_unidade_resp b
                                 where b.sq_pessoa = p_pessoa
                                   and b.fim       is null)
      connect by prior sq_unidade = sq_unidade_pai;
      
begin
   If p_fase is not null Then
      Loop
         l_item  := Trim(substr(l_fase,1,Instr(l_fase,',')-1));
         If Length(l_item) > 0 Then
            x_fase := x_fase||','''||to_number(l_item)||'''';
         End If;
         l_fase := substr(l_fase,Instr(l_fase,',')+1);
         Exit when l_fase is null or instr(l_fase,',') = 0;
      End Loop;
      x_fase := substr(x_fase,2,200);
   End If;
   
   -- Monta uma string com todas as unidades subordinadas à que o usuário é responsável
   for crec in c_unidades_resp loop
     l_resp_unid := l_resp_unid ||','''||crec.sq_unidade||'''';
   end loop;
   
   If substr(p_restricao,1,2) = 'PD' or Substr(p_restricao,1,4) = 'GRPD' Then
      -- Recupera as viagens que o usuário pode ver
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec,                       a2.nome as nm_unidade_exec, 
                a2.informal as informal_exec,                        a2.vinculada as vinc_exec,
                a2.adm_central as adm_exec,                          a2.sq_tipo_unidade,
                a2.informal,          a2.vinculada,                  a2.adm_central,
                a3.sq_pessoa as tit_exec,a4.sq_pessoa as subst_exec,
                a5.dias_prestacao_contas, 
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.opiniao,            b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,              cast(b.inicio as date)-cast(3 as integer) as aviso,
                b.sq_plano,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when n.sq_cc is null
                                         then '???'
                                         else 'Classif: '||n.nome 
                                    end
                               else ' Plano: '||b3.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end as nm_prioridade,
                d.ordem,
                d1.sq_pessoa as sq_prop, d1.tipo as tp_missao,       d11.codigo_interno,
                codigo2numero(d11.codigo_interno) as ord_codigo_interno,
                case d1.tipo when 'I' then 'Inicial' when 'P' then 'Prorrogação' else 'Complementação' end as nm_tp_missao,
                d1.valor_adicional,   d1.desconto_alimentacao,       d1.desconto_transporte,
                d1.reembolso,         d1.reembolso_valor,            d1.reembolso_observacao,
                d1.ressarcimento,     d1.ressarcimento_valor,        d1.ressarcimento_observacao,
                d1.ressarcimento_data,d1.nacional,                   d1.internacional,
                d1.cumprimento,
                case d1.cumprimento when 'I' then 'Não' when 'P' then 'Sim' when 'C' then 'Cancelada' else 'Não informada' end as nm_cumprimento,
                d2.nome as nm_prop,   d2.nome_resumido as nm_prop_res, d2.nome_indice as nm_prop_ind, d2.nome_resumido_ind as nm_prop_res_ind,
                d3.sq_tipo_vinculo,   d3.nome as nm_tipo_vinculo,      d3.interno,                    d3.contratado,
                d4.sexo,              d4.cpf,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,  e.sigla as sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                o.nome_resumido as nm_solic, o.nome_resumido||' ('||o2.sigla||')' as nm_resp,
                p.nome_resumido as nm_exec,
                n1.valor_diaria, d1.valor_passagem as valor_trecho,
                d5.limite_passagem, d5.limite_diaria,
                to_char(r.saida,'dd/mm/yyyy, hh24:mi:ss') as phpdt_saida, to_char(r.chegada,'dd/mm/yyyy, hh24:mi:ss') as phpdt_chegada,
                pd_retornatrechos(b.sq_siw_solicitacao) as trechos,
                case when (b1.sigla in ('PC','AP') and soma_dias(a.sq_pessoa,trunc(b.fim),coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1,'U') - trunc(sysdate)<0) then 'S' else 'N' end as atraso_pc
           from siw_menu                                a
                inner         join eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                inner         join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                inner         join pd_parametro         a5 on (a.sq_pessoa                = a5.cliente)
                inner         join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                  inner       join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                  inner       join gd_demanda           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                    inner     join pd_missao            d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                      inner   join siw_solicitacao     d11 on (d1.sq_siw_solicitacao      = d11.sq_siw_solicitacao)
                      inner   join co_pessoa            d2 on (d1.sq_pessoa               = d2.sq_pessoa)
                        inner join co_tipo_vinculo      d3 on (d2.sq_tipo_vinculo         = d3.sq_tipo_vinculo)
                        inner join (select x.sq_unidade, 
                                           coalesce(y.limite_passagem,0) as limite_passagem, 
                                           coalesce(y.limite_diaria,0)   as limite_diaria
                                      from pd_unidade                  x
                                           left join pd_unidade_limite y on (x.sq_unidade = y.sq_unidade and
                                                                             y.ano        = coalesce(p_sq_orprior,y.ano)
                                                                            )
                                   )                    d5 on (d.sq_unidade_resp          = d5.sq_unidade)
                      inner   join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                    inner     join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                      inner   join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                        inner join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                  inner       join (select x.sq_siw_solicitacao, max(x.sq_siw_solic_log) as chave 
                                      from siw_solic_log              x
                                           inner join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                     where y.sq_menu = p_menu
                                    group by x.sq_siw_solicitacao
                                   )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                  inner       join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) as acesso
                                      from siw_solicitacao
                                   )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                        left  join co_pessoa_fisica     d4 on (d2.sq_pessoa               = d4.sq_pessoa)
                      left    join pd_categoria_diaria  d6 on (d1.diaria                  = d6.sq_categoria_diaria)
                  left           join pe_plano          b3 on (b.sq_plano                 = b3.sq_plano)
                  left        join eo_unidade_resp      a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                               a3.tipo_respons            = 'T'           and
                                                               a3.fim                     is null)
                  left        join eo_unidade_resp      a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                               a4.tipo_respons            = 'S'           and
                                                               a4.fim                     is null)
                        left  join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                               e1.tipo_respons            = 'T'           and
                                                               e1.fim                     is null)
                        left  join eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                               e2.tipo_respons            = 'S'           and
                                                               e2.fim                     is null)
                    left      join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                    left      join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                    left      join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                      left    join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
                 left         join (select x.sq_siw_solicitacao, sum((y.quantidade*y.valor)) as valor_diaria
                                      from siw_solicitacao         x
                                           inner join pd_diaria  y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                    group by x.sq_siw_solicitacao
                                   )                    n1 on (b.sq_siw_solicitacao       = n1.sq_siw_solicitacao)
                 left         join (select x.sq_siw_solicitacao, sum(y.valor_trecho) as valor_trecho
                                      from siw_solicitacao              x
                                           inner join pd_deslocamento   y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                     where y.tipo = 'S'
                                    group by x.sq_siw_solicitacao
                                   )                    q  on (b.sq_siw_solicitacao       = q.sq_siw_solicitacao)
                 left         join (select x.sq_siw_solicitacao, min(y.saida) as saida, max(y.chegada) as chegada
                                      from siw_solicitacao            x
                                           inner join pd_deslocamento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                     where x.sq_menu = p_menu
                                       and y.tipo    = 'S'
                                    group by x.sq_siw_solicitacao
                                   )                    r  on (b.sq_siw_solicitacao       = r.sq_siw_solicitacao)
          where a.sq_menu         = p_menu
            and (p_projeto        is null or (p_projeto     is not null and (b.sq_solic_pai = p_projeto or 0 < (select count(distinct(x1.sq_siw_solicitacao)) from pd_missao_solic x1 , siw_solicitacao y1 where x1.sq_siw_solicitacao = y1.sq_siw_solicitacao and y1.sq_solic_pai = p_projeto and x1.sq_solic_missao = b.sq_siw_solicitacao))))
            and (p_atividade      is null or (p_atividade   is not null and 0 < (select count(distinct(x2.sq_siw_solicitacao)) from pd_missao_solic x2 join pj_etapa_demanda x3 on (x2.sq_siw_solicitacao = x3.sq_siw_solicitacao and x3.sq_projeto_etapa = p_atividade) where x2.sq_solic_missao = b.sq_siw_solicitacao)))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d11.codigo_interno like '%'||p_sq_acao_ppa||'%'))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.descricao,null) like '%'||acentos(p_assunto,null)||'%'))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante        = p_solicitante))
            and (p_unidade        is null or (p_unidade     is not null and d.sq_unidade_resp    = p_unidade))
            and (p_proponente     is null or (p_proponente  is not null and (acentos(d2.nome,null)          like '%'||acentos(p_proponente,null)||'%') or
                                                                            (acentos(d2.nome_resumido,null) like '%'||acentos(p_proponente,null)||'%')
                                             )
                )
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d1.sq_pessoa         = p_sq_orprior))
            and (p_palavra        is null or (p_palavra     is not null and d4.cpf = p_palavra))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_pais = p_pais and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_regiao         is null or (p_regiao      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.sq_regiao = p_regiao and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_uf             is null or (p_uf          is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.tipo = 'S' and x.destino = y.sq_cidade and y.co_uf = p_uf and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_cidade         is null or (p_cidade      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.tipo = 'S' and x.destino = p_cidade and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (coalesce(p_ativo,'N') = 'N' or (p_ativo    = 'S' and (b.justificativa is not null)))
            and (p_usu_resp       is null or (p_usu_resp    is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.tipo = 'S' and x.sq_cia_transporte = p_usu_resp and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (p_ini_i          is null or (p_ini_i       is not null and ((b.inicio           between p_ini_i  and p_ini_f) or
                                                                             (b.fim              between p_ini_i  and p_ini_f) or
                                                                             (p_ini_i            between b.inicio and b.fim)   or
                                                                             (p_ini_f            between b.inicio and b.fim)
                                                                            )
                                             )
                )
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso  = 'S'       and b1.sigla in ('PC','AP','VP') and soma_dias(a.sq_pessoa,trunc(b.fim),coalesce(d6.dias_prestacao_contas, a5.dias_prestacao_contas) + 1,'U') - trunc(sysdate)<0))
            and ((p_tipo         = 1     and b1.sigla = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and b1.sigla <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and b1.sigla <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and b1.sigla <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                );
   End If;
end SP_GetSolicPD;
/
