create or replace procedure SP_GetSolicDataSR
   (p_chave     in number,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor
   ) is
   w_menu siw_menu.sq_menu%type := 0;
   w_reg number(18);
begin
   If p_chave is not null Then
      -- Recupera o menu ao qual a solicitação está ligada   
      select count(*) into w_reg from siw_solicitacao where sq_siw_solicitacao = p_chave;
      If w_reg > 0 Then
        select sq_menu into w_menu from siw_solicitacao where sq_siw_solicitacao = p_chave;      
      Else
        w_menu := 0;
      End If;
  End If;
   
   If substr(p_restricao,1,2) = 'SR' Then
      -- Recupera as demandas que o usuário pode ver
      open p_result for 
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao as ds_menu,        a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                a2.sq_tipo_unidade tp_exec, a2.nome nm_unidade_exec, a2.informal informal_exec,
                a2.vinculada vinc_exec,a2.adm_central adm_exec,
                a3.sq_pessoa tit_exec,a4.sq_pessoa subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.observacao,                  b.recebedor,
                b.motivo_insatisfacao,
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss')  phpdt_inclusao,
                to_char(b.inicio,'dd/mm/yyyy, hh24:mi:ss')    phpdt_inicio,
                to_char(b.fim,'dd/mm/yyyy, hh24:mi:ss')       phpdt_fim,
                to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') phpdt_conclusao,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then '---'
                               else 'Plano: '||b4.titulo
                          end
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                case when b1.sigla = 'AT' then b.valor else 0 end as custo_real,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail,
                b2.nome  nm_opiniao,
                b3.sq_veiculo,        b3.qtd_pessoas,                b3.carga,
                b3.hodometro_saida,   b3.hodometro_chegada,          b3.destino,
                b3.parcial,           b3.procedimento,
                b5.inicio_real,       b5.fim_real,                   b5.pendencia,
                b5.acessorios_entregues,                             b5.acessorios_pendentes,
                b51.nome as nm_pais_cel,
                b52.sq_celular,       b52.numero_linha,              b52.marca,
                b52.modelo,           b52.sim_card,                  b52.imei,
                b52.acessorios,       b52.ativo as at_celular,       b52.bloqueado,
                b52.inicio_bloqueio,  b52.fim_bloqueio,              b52.motivo_bloqueio,
                to_char(b3.horario_saida,'dd/mm/yyyy, hh24:mi:ss')   phpdt_horario_saida,
                to_char(b3.horario_chegada,'dd/mm/yyyy, hh24:mi:ss') phpdt_horario_chegada,
                case b3.procedimento when 0 then 'Não Informado' 
                                     when 1 then 'Somente levar' 
                                     when 2 then 'Levar e aguardar' 
                                     when 3 then 'Somente buscar' 
                                     when 4 then 'Abastecimento (uso exclusivo do setor de tráfego)'
                end as nm_procedimento,
                e.sq_tipo_unidade,    e.nome nm_unidade_solic,        e.informal informal_solic,
                e.vinculada vinc_solic,e.adm_central adm_solic,       e.sigla as sg_unidade_solic,
                e1.sq_pessoa titular, e2.sq_pessoa substituto,
                f.nome nm_solicitante, f.nome_resumido nm_sol,
                coalesce(f1.ativo,'N') as st_sol,
                g.sq_cc,              g.nome cc_nome,                g.sigla cc_sigla,
                h.sq_pais,            h.sq_regiao,                   h.co_uf,
                h.nome nm_cidade,
                i1.nome_resumido nm_cad,
                i.nome_resumido nm_exec,
                j.nome_resumido nm_recebedor,
                case when l.placa is null 
                     then null 
                     else substr(l.placa,1,3)||'-'||substr(l.placa,4) ||' - '||l.marca||' '||l.modelo 
                end as nm_placa
           from siw_menu                                    a
                inner        join eo_unidade                a2 on (a.sq_unid_executora   = a2.sq_unidade)
                  left       join eo_unidade_resp           a3 on (a2.sq_unidade         = a3.sq_unidade and
                                                                   a3.tipo_respons       = 'T'           and
                                                                   a3.fim                is null
                                                                  )
                  left       join eo_unidade_resp           a4 on (a2.sq_unidade         = a4.sq_unidade and
                                                                   a4.tipo_respons       = 'S'           and
                                                                   a4.fim                is null
                                                                  ) 
                inner        join siw_modulo                a1 on (a.sq_modulo           = a1.sq_modulo)
                inner        join siw_solicitacao           b  on (a.sq_menu             = b.sq_menu)
                  inner      join siw_tramite               b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite)
                  left       join siw_opiniao               b2 on (b.opiniao             = b2.sq_siw_opiniao)
                  left       join sr_solicitacao_transporte b3 on (b.sq_siw_solicitacao  = b3.sq_siw_solicitacao)
                    left     join sr_veiculo                l  on (b3.sq_veiculo         = l.sq_veiculo)
                   left      join pe_plano                  b4 on (b.sq_plano            = b4.sq_plano)
                   left      join sr_solicitacao_celular    b5 on (b.sq_siw_solicitacao  = b5.sq_siw_solicitacao)
                     left    join co_pais                  b51 on (b5.sq_pais            = b51.sq_pais)
                     left    join sr_celular               b52 on (b5.sq_celular         = b52.sq_celular)
                  inner      join eo_unidade                e  on (b.sq_unidade          = e.sq_unidade)
                    left     join eo_unidade_resp           e1 on (e.sq_unidade          = e1.sq_unidade and
                                                                   e1.tipo_respons       = 'T'           and
                                                                   e1.fim                is null
                                                                  )
                    left     join eo_unidade_resp           e2 on (e.sq_unidade          = e2.sq_unidade and
                                                                   e2.tipo_respons       = 'S'           and
                                                                   e2.fim                is null
                                                                  )
                  inner      join co_pessoa                 f  on (b.solicitante         = f.sq_pessoa)
                    left     join sg_autenticacao           f1 on (f.sq_pessoa           = f1.sq_pessoa)
                  left       join co_pessoa                 i1 on (b.cadastrador         = i1.sq_pessoa)
                  left       join co_pessoa                 i  on (b.executor            = i.sq_pessoa)
                  left       join co_pessoa                 j  on (b.recebedor           = j.sq_pessoa)                  
                  inner      join co_cidade                 h  on (b.sq_cidade_origem    = h.sq_cidade)
                  left       join ct_cc                     g  on (b.sq_cc               = g.sq_cc)
          where b.sq_siw_solicitacao       = p_chave;
   End If;
end SP_GetSolicDataSR;
/
