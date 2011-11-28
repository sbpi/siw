create or replace procedure SP_GetSolicGP
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
         l_fase := substr(l_fase,Instr(l_fase,',')+1,200);
         Exit when length(l_fase)=0 or l_fase is null;
      End Loop;
      x_fase := substr(x_fase,2,200);
   End If;
   
   -- Monta uma string com todas as unidades subordinadas à que o usuário é responsável
   for crec in c_unidades_resp loop
     l_resp_unid := l_resp_unid ||','''||crec.sq_unidade||'''';
   end loop;
   
 if substr(p_restricao,1,2) = 'GP' Then
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
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a2.sq_tipo_unidade as tp_exec, a2.nome as nm_unidade_exec, a2.informal as informal_exec,
                a2.vinculada as vinc_exec,a2.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      coalesce(b.fim, trunc(sysdate)) as fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,                     b.palavra_chave,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                coalesce(b.codigo_interno, to_char(b.sq_siw_solicitacao)) as codigo_interno,
                b.codigo_externo,     b.titulo,                      acentos(b.titulo) as ac_titulo,
                b.sq_plano,           b.sq_cc,                       b.observacao,
                b.protocolo_siw,      b.recebedor,
                to_char(b.inclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inclusao,
                to_char(b.conclusao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_conclusao,
                b1.nome as nm_tramite,   b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                b2.acesso,
                d.inicio_data,        d.inicio_periodo,              d.fim_data, 
                d.fim_periodo,        d.gozo_previsto,               d.gozo_efetivo,
                d.inicio_aquisitivo,  d.fim_aquisitivo,              d.abono_pecuniario,
                case d.inicio_periodo   when 'M' then 'Manhã' else 'Tarde' end as nm_inicio_periodo,
                case d.fim_periodo      when 'M' then 'Manhã' else 'Tarde' end as nm_fim_periodo,
                case d.abono_pecuniario when 'S' then 'Sim' else 'Não'       end as nm_abono_pecuniario,
                cast(b.fim as date)-3 as aviso,
                d1.sq_contrato_colaborador, d1.matricula, d1.inicio as inicio_contrato,
                d1.fim as fim_contrato, d1.vale_transporte, d1.vale_refeicao,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,        e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,     e.sigla sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                n.sq_cc,              n.nome as nm_cc,                  n.sigla as sg_cc,
                o.nome_resumido as nm_solic, o.nome_resumido_ind as nm_solic_ind
           from siw_menu                                        a 
                inner        join eo_unidade                    a2 on (a.sq_unid_executora        = a2.sq_unidade)
                  left       join eo_unidade_resp               a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                       a3.tipo_respons            = 'T'           and
                                                                       a3.fim                     is null
                                                                      )
                  left       join eo_unidade_resp               a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                       a4.tipo_respons            = 'S'           and
                                                                       a4.fim                     is null
                                                                      )
                inner             join siw_modulo               a1 on (a.sq_modulo                = a1.sq_modulo)
                inner             join siw_solicitacao          b  on (a.sq_menu                  = b.sq_menu)
                   inner          join siw_tramite              b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                   inner          join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_pessoa) as acesso
                                          from siw_solicitacao
                                       )                        b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                   inner          join gp_ferias                d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                     inner        join gp_contrato_colaborador  d1 on (d.sq_contrato_colaborador  = d1.sq_contrato_colaborador)
                       inner      join co_pessoa                d2 on (d1.sq_pessoa               = d2.sq_pessoa)
                       inner      join siw_solicitacao          d4 on (d1.centro_custo            = d4.sq_siw_solicitacao)
                     inner        join eo_unidade               e  on (b.sq_unidade               = e.sq_unidade)
                       left       join eo_unidade_resp          e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                       e1.tipo_respons            = 'T'           and
                                                                       e1.fim                     is null
                                                                      )
                       left       join eo_unidade_resp          e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                       e2.tipo_respons            = 'S'           and
                                                                       e2.fim                     is null
                                                                      )
                   inner          join co_cidade                f  on (b.sq_cidade_origem         = f.sq_cidade)
                   left           join ct_cc                    n  on (b.sq_cc                    = n.sq_cc)
                   left           join co_pessoa                o  on (b.solicitante              = o.sq_pessoa)
                inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) as chave 
                                          from siw_solic_log
                                        group by sq_siw_solicitacao
                                       )                        j  on (b.sq_siw_solicitacao        = j.sq_siw_solicitacao)
          where (p_menu           is null or (p_menu        is not null and a.sq_menu              = p_menu))
            and (p_chave          is null or (p_chave       is not null and b.sq_siw_solicitacao   = p_chave))
            --and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and d.sq_modalidade_artigo = p_sq_acao_ppa))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and b.sq_plano             = p_sq_orprior))
            and (p_pais           is null or (p_pais        is not null and 0 < (select count(*) from cl_solicitacao_item x inner join cl_material y on (x.sq_material = y.sq_material) where x.sq_siw_solicitacao = b.sq_siw_solicitacao and y.sq_tipo_material in (select sq_tipo_material from cl_tipo_material connect by prior sq_tipo_material = sq_tipo_pai start with sq_tipo_material=p_pais))))
            --and (p_regiao         is null or (p_regiao      is not null and d.processo           like '%'||p_regiao||'%'))
            --and (p_cidade         is null or (p_cidade      is not null and d.processo           like '%'||p_cidade||'%'))
            --and (p_usu_resp       is null or (p_usu_resp    is not null and d4.sq_lcmodalidade   = p_usu_resp))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and coalesce(b1.sigla,'-') <> 'AT' and e.sq_unidade = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc                = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and b.sq_solic_pai         = p_projeto))
            --and (p_processo       is null or (p_processo    = 'CLASSIF' and b.sq_cc is not null) or (p_processo <> 'CLASSIF' and m1.sq_menu = to_number(p_processo)))
            --and (p_uf             is null or (p_uf          is not null and d6.sq_lcsituacao       = p_uf))
            and (p_proponente     is null or (p_proponente  is not null and 0 < (select count(*) from cl_solicitacao_item x inner join cl_material y on (x.sq_material = y.sq_material) where x.sq_siw_solicitacao = b.sq_siw_solicitacao and acentos(y.nome,null) like '%'||acentos(p_proponente,null)||'%')))
            and (p_assunto        is null or (p_assunto     is not null and acentos(b.titulo,null) like '%'||acentos(p_assunto,null)||'%'))
            --and (p_palavra        is null or (p_palavra     is not null and acentos(d.numero_certame,null) like '%'||acentos(p_palavra,null)||'%'))
            and (p_empenho        is null or (p_empenho     is not null and acentos(b.codigo_interno,null) like '%'||acentos(p_empenho,null)||'%'))
            --and (p_prioridade     is null or (p_prioridade  is not null and d.prioridade         = p_prioridade))
            and (coalesce(p_ativo,'N') = 'N' or (p_ativo = 'S' and d.abono_pecuniario = p_ativo))
            and (p_fase           is null or (p_fase        is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0))
            and (p_prazo          is null or (p_prazo       is not null and coalesce(b1.sigla,'-') <> 'AT' and cast(cast(b.fim as date)-cast(sysdate as date) as integer)+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and d.inicio_data between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and coalesce(d.inicio_aquisitivo, b.conclusao) between p_fim_i and p_fim_f))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso = 'S' and coalesce(b1.sigla,'-') <> 'AT' and b.fim+1-sysdate<0))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade           = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and b.solicitante          = p_solicitante))
            and ((p_tipo         = 1     and coalesce(b1.sigla,'-') = 'CI'   and b.cadastrador        = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b.executor = p_pessoa and b.conclusao is null) or
                 (p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (p_tipo         = 3     and b2.acesso > 0) or
                 (p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and coalesce(b1.sigla,'-') <> 'CA') or
                 (p_tipo         = 5) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0 and b1.sigla <> 'CI')
                );
   Else -- Trata a vinculação entre serviços
      -- Recupera as solicitações que o usuário pode ver
      open p_result for 
         select b.sq_siw_solicitacao, b.codigo_interno,
                case when d.sq_siw_solicitacao is not null 
                     then b.titulo
                     else case when e.sq_siw_solicitacao is not null
                               then e.titulo
                               else case when f.sq_siw_solicitacao is not null
                                         then f1.titulo
                                         else null
                                    end
                          end
                end as titulo
           from siw_menu                     a
                inner join siw_modulo        a1 on (a.sq_modulo          = a1.sq_modulo)
                inner join siw_menu_relac    a2 on (a.sq_menu            = a2.servico_cliente and
                                                    a2.servico_cliente   = to_number(p_restricao)
                                                   )
                inner join siw_solicitacao   b  on (a2.servico_fornecedor= b.sq_menu and
                                                    a2.sq_siw_tramite    = b.sq_siw_tramite and
                                                    b.sq_menu            = coalesce(p_menu, b.sq_menu)
                                                   )
                inner   join siw_menu        b2 on (b.sq_menu            = b2.sq_menu)
                  inner join siw_modulo      b3 on (b2.sq_modulo         = b3.sq_modulo)
                left    join cl_solicitacao  d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
                left    join (select x.sq_siw_solicitacao, y.codigo_interno, 
                                     case when y.titulo is not null
                                          then y.titulo
                                          else w.nome_resumido||' - '||case when z.sq_cc is not null then z.nome else k1.titulo end||' ('||to_char(y.inicio,'dd/mm/yyyy')||'-'||to_char(y.fim,'dd/mm/yyyy')||')' end as titulo
                                from ac_acordo                     x
                                     left join     co_pessoa       w  on x.outra_parte         = w.sq_pessoa
                                     join          siw_solicitacao y  on x.sq_siw_solicitacao  = y.sq_siw_solicitacao
                                       left   join ct_cc           z  on y.sq_cc               = z.sq_cc
                                       left   join cl_solicitacao  k  on y.sq_solic_pai        = k.sq_siw_solicitacao
                                         left join siw_solicitacao k1 on (k.sq_siw_solicitacao = k1.sq_siw_solicitacao)
                             )               e  on (b.sq_siw_solicitacao = e.sq_siw_solicitacao)
                left    join pe_programa     f  on (b.sq_siw_solicitacao = f.sq_siw_solicitacao)
                  left  join siw_solicitacao f1 on (f.sq_siw_solicitacao = f1.sq_siw_solicitacao)
          where a.sq_menu        = to_number(p_restricao)
            and b.sq_menu        = coalesce(p_menu, b.sq_menu)
            and (acesso(b.sq_siw_solicitacao,p_pessoa) > 0 or
                 InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0
                )
         order by titulo;
   End If;
end SP_GetSolicGP;
/
