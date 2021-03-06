alter procedure dbo.SP_GetSolicList
   (@p_menu         int,
    @p_pessoa       int,
    @p_restricao    varchar(20) = null,
    @p_tipo         int         = null,
    @p_ini_i        DateTime    = null,
    @p_ini_f        DateTime    = null,
    @p_fim_i        DateTime    = null,
    @p_fim_f        DateTime    = null,
    @p_atraso       varchar(90) = null,
    @p_solicitante  int         = null,
    @p_unidade      int         = null,
    @p_prioridade   int         = null,
    @p_ativo        varchar(10) = null,
    @p_proponente   varchar(90) = null,
    @p_chave        int         = null,
    @p_assunto      varchar(90) = null,
    @p_pais         int         = null,
    @p_regiao       int         = null,
    @p_uf           varchar( 2) = null,
    @p_cidade       int         = null,
    @p_usu_resp     int         = null,
    @p_uorg_resp    int         = null,
    @p_palavra      varchar(90) = null,
    @p_prazo        int         = null,
    @p_fase         varchar(200)= null,
    @p_sqcc         int         = null,
    @p_projeto      int         = null,
    @p_atividade    int         = null,
    @p_sq_acao_ppa  int         = null,
    @p_sq_orprior   int         = null,
    @p_empenho      varchar(30) = null,
    @p_processo     varchar(30) = null
   ) as
begin
   Declare @l_item       varchar(18)
   Declare @l_fase       varchar(200)
   Declare @x_fase       varchar(200)
   Declare @l_resp_unid  varchar(1000)
   Set @l_resp_unid ='';
   
   Set @l_fase = @p_fase + ','
   Set @x_fase = ''

   If @p_fase is not null Begin
      While len(IsNull(@l_fase,'')) > 0 Begin
         Set @l_item  = lTrim(rTrim(substring(@l_fase,1,CharIndex(',',@l_fase)-1)))
         If Len(IsNull(@l_item,'')) > 0 Set @x_fase = @x_fase + ',''' + @l_item + ''''
         Set @l_fase = substring(@l_fase,CharIndex(',',@l_fase)+1,200)
      End
      Set @x_fase = substring(@x_fase,2,200)
   End

   If @p_restricao = 'ESTRUTURA' Begin
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.ordem as or_servico,         a.sq_unid_executora,  
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome as nm_modulo,    a1.sigla as sg_modulo,      a1.ordem as or_modulo,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.sq_plano,                    dbo.dados_solic(b.sq_siw_solicitacao) as dados_solic,
                dbo.SolicRestricao(b.sq_siw_solicitacao,null) as restricao,
                coalesce(b.codigo_interno,cast(b.sq_siw_solicitacao as varchar)) as codigo_interno,
                b.titulo,
                b.titulo as ac_titulo,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail,
                dbo.calculaIGE(b.sq_siw_solicitacao) as ige, dbo.calculaIDE(b.sq_siw_solicitacao,null,null)  as ide,
                dbo.calculaIGC(b.sq_siw_solicitacao) as igc, dbo.calculaIDC(b.sq_siw_solicitacao,null,null)  as idc,
                coalesce(c.aviso_prox_conc, d.aviso_prox_conc) as aviso_prox_conc,
                coalesce(c.inicio_real,     d.inicio_real)     as inicio_real,
                coalesce(c.fim_real,        d.fim_real)        as fim_real,
                coalesce(c.custo_real,      d.custo_real)      as custo_real,
                cast(b.fim as datetime)-cast(coalesce(c.dias_aviso,d.dias_aviso) as integer) as aviso,
                o.nome_resumido as nm_solic, o.nome_resumido_ind as nm_solic_ind, 
                (select count(x.sq_siw_solicitacao) 
                   from siw_solicitacao x
                        inner   join siw_menu   y on (x.sq_menu   = y.sq_menu)
                          inner join siw_modulo z on (y.sq_modulo = z.sq_modulo)
                  where x.sq_solic_pai = b.sq_siw_solicitacao
                    and 'GD'           <> z.sigla
                    and 'GDP'          <> substring(y.sigla,1,3)
                    and (@p_tipo        <> 7 or (@p_tipo = 7 and z.sigla in ('PE','PR')))
                ) as qt_filho,
                (select count(*) from dbo.SP_fGetSolic(@p_chave,'DOWN')) as level
           from siw_menu                                      a
                inner          join siw_modulo                a1 on (a.sq_modulo           = a1.sq_modulo)
                inner          join siw_solicitacao           b  on (a.sq_menu             = b.sq_menu)
                  inner        join siw_tramite               b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite)
                    left       join co_pessoa                 o  on (b.solicitante         = o.sq_pessoa)
                      inner    join sg_autenticacao           o1 on (o.sq_pessoa           = o1.sq_pessoa)
                        inner  join eo_unidade                o2 on (o1.sq_unidade         = o2.sq_unidade)
                  left         join pe_programa               c  on (b.sq_siw_solicitacao  = c.sq_siw_solicitacao)
                  left         join pj_projeto                d  on (b.sq_siw_solicitacao  = d.sq_siw_solicitacao)
          where 'S'            = b1.ativo
            and 'GD'           <> a1.sigla
            and 'GDP'          <> substring(a.sigla,1,3)
            and (@p_tipo        <> 7    or (@p_tipo = 7 and a1.sigla in ('PE','PR')))
            and (@p_sq_orprior  is null or (@p_sq_orprior is not null and (b.sq_plano = @p_sq_orprior)))
            and (@p_sq_acao_ppa is null or (@p_sq_acao_ppa is not null and (0         < (select count(y.sq_siw_solicitacao)
                                                                                         from siw_solicitacao_objetivo y
                                                                                        where y.sq_siw_solicitacao = b.sq_siw_solicitacao
                                                                                           and y.sq_peobjetivo      = @p_sq_acao_ppa
                                                                                      )
                                                                         )
                                          )
                )
            and 0 < dbo.acesso(b.sq_siw_solicitacao, @p_pessoa, null)
            and b.sq_siw_solicitacao in (select chave from dbo.SP_fGetSolic(@p_chave,'DOWN'))
   End Else If @p_restricao = 'FILHOS' Begin
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.ordem as or_servico,         a.sq_unid_executora,  
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome as nm_modulo,    a1.sigla as sg_modulo,            a1.ordem as or_modulo,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      
                coalesce(b.codigo_interno,cast(b.sq_siw_solicitacao as varchar)) as codigo_interno,
                coalesce(b.codigo_interno,b.titulo,cast(b.sq_siw_solicitacao as varchar)) as titulo,
                b.titulo as ac_titulo,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail,
                dbo.calculaIGE(b.sq_siw_solicitacao) as ige, dbo.calculaIDE(b.sq_siw_solicitacao,null,null)  as ide,
                dbo.calculaIGC(b.sq_siw_solicitacao) as igc, dbo.calculaIDC(b.sq_siw_solicitacao,null,null)  as idc,
                o.nome_resumido as nm_solic, o.nome_resumido_ind as nm_solic_ind
           from siw_menu                                      a
                inner          join siw_modulo                a1 on (a.sq_modulo           = a1.sq_modulo)
                inner          join siw_solicitacao           b  on (a.sq_menu             = b.sq_menu)
                  inner        join siw_tramite               b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite)
                    left       join co_pessoa                 o  on (b.solicitante         = o.sq_pessoa)
                      inner    join sg_autenticacao           o1 on (o.sq_pessoa           = o1.sq_pessoa)
                        inner  join eo_unidade                o2 on (o1.sq_unidade         = o2.sq_unidade)
          where b1.ativo            = 'S'
            and a1.sigla            <> 'GD'
            and substring(a.sigla,1,3) <> 'GDP'
            and b.sq_solic_pai      =  @p_chave;
   End Else If Substring(@p_restricao,1,2) = 'GD' or
      Substring(@p_restricao,1,4) = 'GRDM' Begin
      -- Recupera as demandas que o usuário pode ver
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
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.sq_plano,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when n.sq_cc is null
                                         then '???'
                                         else 'Classif: '+n.nome 
                                    end
                               else ' Plano: '+b3.titulo
                          end
                     else dbo.dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite, b1.ativo,                    b1.envia_mail,
                c.sq_tipo_unidade,    c.nome as nm_unidade_exec,     c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                d.sq_siw_restricao,   d.ordem,
                d.recebimento,        d.limite_conclusao,            d.responsavel,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end as nm_prioridade,
                cast(b.fim as datetime)-cast(d.dias_aviso as integer) as aviso,
                d1.sq_demanda_tipo,   d1.reuniao,                    d1.nome as nm_demanda_tipo,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,  e.sigla as sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                m1.titulo as nm_projeto, dbo.acentos(m1.titulo) as ac_titulo,
                n.sq_cc,              n.nome as nm_cc,               n.sigla as sg_cc,
                o.nome_resumido as nm_solic, o.nome_resumido+' ('+o2.sigla+')' as nm_resp,
                o.nome_resumido_ind as nm_solic_ind,
                p.nome_resumido as nm_exec, p.nome_resumido_ind as nm_exec_ind,
                q.sq_projeto_etapa, q.titulo as nm_etapa,
                dbo.montaOrdem(q.sq_projeto_etapa,null) as cd_ordem,
                0 as resp_etapa,
                0 as sq_acao_ppa, 0 as sq_orprioridade
           from siw_menu                                       a 
                   inner        join eo_unidade                a2 on (a.sq_unid_executora          = a2.sq_unidade)
                     left       join eo_unidade_resp           a3 on (a2.sq_unidade                = a3.sq_unidade and
                                                                      a3.tipo_respons              = 'T'           and
                                                                      a3.fim                       is null
                                                                     )
                     left       join eo_unidade_resp           a4 on (a2.sq_unidade                = a4.sq_unidade and
                                                                      a4.tipo_respons              = 'S'           and
                                                                      a4.fim                       is null
                                                                     )
                   inner             join siw_modulo           a1 on (a.sq_modulo                  = a1.sq_modulo)
                   inner             join siw_solicitacao      b  on (a.sq_menu                    = b.sq_menu)
                      inner          join siw_tramite          b1 on (b.sq_siw_tramite             = b1.sq_siw_tramite)
                      inner          join (select sq_siw_solicitacao, dbo.acesso(sq_siw_solicitacao, @p_pessoa, null) as acesso
                                             from siw_solicitacao
                                          )                    b2 on (b.sq_siw_solicitacao         = b2.sq_siw_solicitacao)
                      left           join pe_plano             b3 on (b.sq_plano                   = b3.sq_plano)
                      inner          join gd_demanda           d  on (b.sq_siw_solicitacao         = d.sq_siw_solicitacao)
                      left           join gd_demanda_tipo      d1 on (d.sq_demanda_tipo            = d1.sq_demanda_tipo)
                        left         join eo_unidade           e  on (d.sq_unidade_resp            = e.sq_unidade)
                          left       join eo_unidade_resp      e1 on (e.sq_unidade                 = e1.sq_unidade and
                                                                      e1.tipo_respons              = 'T'           and
                                                                      e1.fim                       is null
                                                                     )
                          left       join eo_unidade_resp      e2 on (e.sq_unidade                 = e2.sq_unidade and
                                                                      e2.tipo_respons              = 'S'           and
                                                                      e2.fim                       is null
                                                                     )
                      inner          join co_cidade            f  on (b.sq_cidade_origem           = f.sq_cidade)
                      left           join pj_projeto           m  on (b.sq_solic_pai               = m.sq_siw_solicitacao)
                        left         join siw_solicitacao      m1 on (m.sq_siw_solicitacao         = m1.sq_siw_solicitacao)
                      left           join ct_cc                n  on (b.sq_cc                      = n.sq_cc)
                      left           join co_pessoa            o  on (b.solicitante                = o.sq_pessoa)
                        inner        join sg_autenticacao      o1 on (o.sq_pessoa                  = o1.sq_pessoa)
                          inner      join eo_unidade           o2 on (o1.sq_unidade                = o2.sq_unidade)
                      left           join co_pessoa            p  on (b.executor                   = p.sq_pessoa)
                   left              join eo_unidade           c  on (a.sq_unid_executora          = c.sq_unidade)
                   left              join pj_etapa_demanda     i  on (b.sq_siw_solicitacao        = i.sq_siw_solicitacao)
                      left           join pj_projeto_etapa     q  on (i.sq_projeto_etapa           = q.sq_projeto_etapa)
                   inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) as chave 
                                             from siw_solic_log
                                           group by sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao         = j.sq_siw_solicitacao)
                     left            join gd_demanda_log       k  on (j.chave                      = k.sq_siw_solic_log)
                       left          join sg_autenticacao      l  on (k.destinatario               = l.sq_pessoa)
          where a.sq_menu        = @p_menu
            and (@p_chave          is null or (@p_chave       is not null and b.sq_siw_solicitacao   = @p_chave))
            and (@p_pais           is null or (@p_pais        is not null and f.sq_pais              = @p_pais))
            and (@p_regiao         is null or (@p_regiao      is not null and f.sq_regiao            = @p_regiao))
            and (@p_cidade         is null or (@p_cidade      is not null and f.sq_cidade            = @p_cidade))
            and (@p_usu_resp       is null or (@p_usu_resp    is not null and (b.executor            = @p_usu_resp or 0 < (select count(*) from gd_demanda_log where destinatario = @p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (@p_uorg_resp      is null or (@p_uorg_resp   is not null and d.concluida            = 'N' and l.sq_unidade = @p_uorg_resp))
            and (@p_sqcc           is null or (@p_sqcc        is not null and b.sq_cc                = @p_sqcc))
            and (@p_projeto        is null or (@p_projeto     is not null and b.sq_solic_pai         = @p_projeto))
            and (@p_atividade      is null or (@p_atividade   is not null and i.sq_projeto_etapa     = @p_atividade))
            and (@p_uf             is null or (@p_uf          is not null and f.co_uf                = @p_uf))
            and (@p_assunto        is null or (@p_assunto     is not null and dbo.acentos(d.assunto) like '%'+dbo.acentos(@p_assunto)+'%'))
            and (@p_palavra        is null or (@p_palavra     is not null and dbo.acentos(b.palavra_chave) like '%'+dbo.acentos(@p_palavra)+'%'))
            and (@p_fase           is null or (@p_fase        is not null and charIndex(cast(b.sq_siw_tramite as varchar),@x_fase) > 0))
            and (@p_prazo          is null or (@p_prazo       is not null and d.concluida            = 'N' and cast(cast(b.fim as datetime)-cast(getDate() as datetime) as integer)+1 <=@p_prazo))
            and (@p_prioridade     is null or (@p_prioridade  is not null and d.prioridade           = @p_prioridade))
            and (@p_ini_i          is null or (@p_ini_i       is not null and (coalesce(b1.sigla,'-')     <> 'AT' and b.inicio between @p_ini_i and @p_ini_f) or (coalesce(b1.sigla,'-') = 'AT' and d.inicio_real between @p_ini_i and @p_ini_f)))
            and (@p_fim_i          is null or (@p_fim_i       is not null and (coalesce(b1.sigla,'-')     <> 'AT' and b.fim                between @p_fim_i and @p_fim_f) or (coalesce(b1.sigla,'-') = 'AT' and d.fim_real between @p_fim_i and @p_fim_f)))
            and (coalesce(@p_atraso,'N') = 'N'  or (@p_atraso      = 'S'       and d.concluida            = 'N' and b.fim+1-getDate()<0))
            and (@p_proponente     is null or (@p_proponente  is not null and dbo.acentos(d.proponente) like '%'+dbo.acentos(@p_proponente)+'%'))
            and (@p_unidade        is null or (@p_unidade     is not null and d.sq_unidade_resp      = @p_unidade))
            and (@p_prioridade     is null or (@p_prioridade  is not null and d.prioridade           = @p_prioridade))
            and (@p_solicitante    is null or (@p_solicitante is not null and b.solicitante          = @p_solicitante))
            and (@p_sq_acao_ppa    is null or (@p_sq_acao_ppa is not null and d.sq_demanda_pai       = @p_sq_acao_ppa))
            and (@p_sq_orprior     is null or (@p_sq_orprior  is not null and d.sq_siw_restricao     = @p_sq_orprior))            
            and (@p_empenho        is null or (@p_empenho     is not null and d1.sq_demanda_tipo     = @p_empenho))            
            and ((@p_tipo         = 1     and coalesce(b1.sigla,'-') = 'CI'   and b.cadastrador          = @p_pessoa) or
                 (@p_tipo         = 2     and coalesce(b1.sigla,'-') <> 'CI'  and b.executor             = @p_pessoa and d.concluida = 'N') or
                 --(@p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (@p_tipo         = 3     and b2.acesso > 0) or
                 (@p_tipo         = 3     and charIndex(''''+cast(b.sq_unidade as varchar)+'''',@l_resp_unid) > 0) or
                 (@p_tipo         = 4     and coalesce(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (@p_tipo         = 4     and coalesce(b1.sigla,'-') <> 'CA'  and charIndex(''''+cast(b.sq_unidade as varchar)+'''',@l_resp_unid) > 0) or
                 (@p_tipo         = 5     and coalesce(b1.sigla,'-') <> 'CA') or
                 (@p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                )
             and ((@p_restricao <> 'GRDMETAPA'    and @p_restricao <> 'GRDMPROP' and
                   @p_restricao <> 'GRDMRESPATU'  and @p_restricao <> 'GDPCADET'
                  ) or
                  ((@p_restricao = 'GRDMETAPA'    and dbo.montaOrdem(q.sq_projeto_etapa,null)  is not null) or
                   (@p_restricao = 'GRDMPROP'     and d.proponente                    is not null) or
                   (@p_restricao = 'GRDMRESPATU'  and b.executor                      is not null) or
                   (@p_restricao = 'GDPCADET'     and q.sq_projeto_etapa              is null and d.sq_siw_restricao is null)
                  )
                 );
   End Else If @p_restricao = 'PJCAD' or @p_restricao = 'PJACOMP' or
         Substring(@p_restricao,1,4) = 'GRPR' Begin
      -- Recupera as demandas que o usuário pode ver
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
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,                     b.palavra_chave,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                coalesce(b.codigo_interno, cast(b.sq_siw_solicitacao as varchar)) as codigo_interno,
                b.codigo_externo,     b.titulo,                      dbo.acentos(b.titulo) as ac_titulo,
                b.sq_plano,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when n.sq_cc is null
                                         then '???'
                                         else 'Classif: '+n.nome 
                                    end
                               else ' Plano: '+b3.titulo
                          end
                     else dbo.dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                b2.acesso,
                bb.sq_siw_coordenada, bb.nome as nm_coordenada,
                bb.latitude, bb.longitude, bb.icone, bb.tipo,
                c.sq_tipo_unidade,    c.nome as nm_unidade_exec,     c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                d.vincula_contrato,   d.vincula_viagem,              d.outra_parte,
                d.sq_tipo_pessoa,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end as nm_prioridade,
                d1.nome as nm_prop,   d1.nome_resumido as nm_prop_res,
                d2.orc_previsto as orc_previsto, d2.orc_real as orc_real, 
                cast(b.fim as datetime)-cast(d.dias_aviso as integer) as aviso,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,        e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp, e.sigla as sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                f.nome+', '+f1.nome+', '+f2.nome as google,
                m1.sq_menu as sq_menu_pai,
                n.sq_cc,              n.nome as nm_cc,                  n.sigla as sg_cc,
                o.nome_resumido as nm_solic, o.nome_resumido_ind as nm_solic_ind,
                p.nome_resumido as nm_exec,  p.nome_resumido_ind as nm_exec_ind,
                coalesce(q.existe,0)  as resp_etapa,
                coalesce(q1.existe,0) as resp_risco,
                coalesce(q2.existe,0) as resp_problema,
                coalesce(q3.existe,0) as resp_meta,
                coalesce(q4.existe,0) as qtd_meta,
                coalesce(q5.existe,0) as qtd_cron_rubrica,
                r.sq_acao_ppa, r.sq_orprioridade,
                dbo.SolicRestricao(b.sq_siw_solicitacao,null) as restricao,
                dbo.calculaIGE(d.sq_siw_solicitacao) as ige, dbo.calculaIDE(d.sq_siw_solicitacao,null,null)  as ide,
                dbo.calculaIGC(d.sq_siw_solicitacao) as igc, dbo.calculaIDC(d.sq_siw_solicitacao,null,null)  as idc
           from siw_menu                                       a 
                   inner       join eo_unidade                 a2 on (a.sq_unid_executora        = a2.sq_unidade)
                     left      join eo_unidade_resp            a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                      a3.tipo_respons            = 'T'           and
                                                                      a3.fim                     is null
                                                                     )
                     left      join eo_unidade_resp            a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                      a4.tipo_respons            = 'S'           and
                                                                      a4.fim                     is null
                                                                     )
                   inner       join siw_modulo                 a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner       join siw_solicitacao            b  on (a.sq_menu                  = b.sq_menu)
                      inner    join siw_tramite                b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner    join (select sq_siw_solicitacao, dbo.acesso(sq_siw_solicitacao, @p_pessoa, null) as acesso
                                             from siw_solicitacao
                                          )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                      left     join pe_plano                   b3 on (b.sq_plano                 = b3.sq_plano)
                      left     join siw_coordenada_solicitacao ba on (b.sq_siw_solicitacao       = ba.sq_siw_solicitacao)
                      left     join siw_coordenada             bb on (ba.sq_siw_coordenada       = bb.sq_siw_coordenada)
                      inner    join pj_projeto                 d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        left   join co_pessoa                  d1 on (d.outra_parte              = d1.sq_pessoa)
                        left   join (select y.sq_siw_solicitacao, sum(x.valor_previsto) as orc_previsto, sum(x.valor_real) as orc_real
                                             from pj_rubrica_cronograma x
                                                  inner join pj_rubrica y on (x.sq_projeto_rubrica = y.sq_projeto_rubrica)
                                            where y.ativo = 'S'
                                           group by y.sq_siw_solicitacao
                                          )                    d2 on (d.sq_siw_solicitacao       = d2.sq_siw_solicitacao)
                        inner  join eo_unidade                 e  on (d.sq_unidade_resp          = e.sq_unidade)
                          left join eo_unidade_resp            e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                      e1.tipo_respons            = 'T'           and
                                                                      e1.fim                     is null
                                                                     )
                          left join eo_unidade_resp            e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                      e2.tipo_respons            = 'S'           and
                                                                      e2.fim                     is null
                                                                     )
                        left   join or_acao                    r  on (d.sq_siw_solicitacao       = r.sq_siw_solicitacao)
                      inner    join co_cidade                  f  on (b.sq_cidade_origem         = f.sq_cidade)
                        inner  join co_uf                      f1 on (f.sq_pais                  = f1.sq_pais and f.co_uf = f1.co_uf)
                         inner join co_pais                    f2 on (f.sq_pais                  = f2.sq_pais)
                      left     join siw_solicitacao            m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)
                        left   join siw_menu                   m1 on (m.sq_menu                  = m1.sq_menu)
                      left     join ct_cc                      n  on (b.sq_cc                    = n.sq_cc)
                      left     join co_pessoa                  o  on (b.solicitante              = o.sq_pessoa)
                      left     join co_pessoa                  p  on (b.executor                 = p.sq_pessoa)
                      left     join (select sq_siw_solicitacao, count(a.sq_projeto_etapa) as existe
                                       from pj_projeto_etapa                a
                                            left       join eo_unidade_resp b on (a.sq_unidade = b.sq_unidade and
                                                                                  b.fim        is null        and
                                                                                  b.sq_pessoa  = @p_pessoa
                                                                                 )
                                      where (a.sq_pessoa         = @p_pessoa or
                                             b.sq_unidade_resp   is not null)
                                     group  by a.sq_siw_solicitacao
                                    )                          q  on (b.sq_siw_solicitacao       = q.sq_siw_solicitacao)
                      left     join (select sq_siw_solicitacao, count(a.sq_siw_restricao) as existe
                                       from siw_restricao a
                                      where a.sq_pessoa = @p_pessoa
                                        and a.risco     = 'S'
                                     group  by a.sq_siw_solicitacao
                                    )                          q1 on (b.sq_siw_solicitacao       = q1.sq_siw_solicitacao)
                      left     join (select sq_siw_solicitacao, count(a.sq_siw_restricao) as existe
                                       from siw_restricao a
                                      where a.sq_pessoa = @p_pessoa
                                        and a.problema  = 'S'
                                     group  by a.sq_siw_solicitacao
                                    )                          q2 on (b.sq_siw_solicitacao       = q2.sq_siw_solicitacao)
                      left     join (select sq_siw_solicitacao, count(a.sq_solic_meta) as existe
                                       from siw_solic_meta a
                                      where a.sq_pessoa          = @p_pessoa
                                        and a.sq_siw_solicitacao is not null
                                     group  by a.sq_siw_solicitacao
                                    )                          q3 on (b.sq_siw_solicitacao       = q3.sq_siw_solicitacao)
                      left     join (select sq_siw_solicitacao, count(a.sq_solic_meta) as existe
                                       from siw_solic_meta a
                                      where a.sq_siw_solicitacao is not null
                                     group  by a.sq_siw_solicitacao
                                    )                          q4 on (b.sq_siw_solicitacao       = q4.sq_siw_solicitacao)
                      left     join (select sq_siw_solicitacao, count(a.sq_projeto_rubrica) as existe
                                       from pj_rubrica                       a
                                            inner join pj_rubrica_cronograma b on (a.sq_projeto_rubrica = b.sq_projeto_rubrica)
                                     group  by a.sq_siw_solicitacao
                                    )                          q5 on (b.sq_siw_solicitacao       = q5.sq_siw_solicitacao)
                   left        join eo_unidade                 c   on (a.sq_unid_executora       = c.sq_unidade)
                   inner       join (select sq_siw_solicitacao, max(sq_siw_solic_log) as chave 
                                       from siw_solic_log
                                     group by sq_siw_solicitacao
                                    )                          j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left      join pj_projeto_log             k  on (j.chave                    = k.sq_siw_solic_log)
                       left    join sg_autenticacao            l  on (k.destinatario             = l.sq_pessoa)
          where a.sq_menu         = @p_menu
            and (@p_chave          is null or (@p_chave       is not null and b.sq_siw_solicitacao = @p_chave))
/*
            and (@p_sq_acao_ppa    is null or (@p_sq_acao_ppa is not null and (r.sq_acao_ppa       = @p_sq_acao_ppa or
                                                                             0                   < (select count(x.sq_siw_solicitacao)
                                                                                                      from siw_solicitacao                     x
                                                                                                           inner join siw_solicitacao_objetivo y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                                                                     where y.sq_peobjetivo      = @p_sq_acao_ppa
                                                                                                       and x.sq_siw_solicitacao in (select chave from dbo.sp_fGetSolic(b.sq_siw_solicitacao,'UP'))
                                                                                                   )
                                                                            )
                                           )
                )
            and (@p_sq_orprior     is null or (@p_sq_orprior is not null and (b.sq_plano           = @p_sq_orprior or 
                                                                            0                    < (select count(*)
                                                                                                      from siw_solicitacao
                                                                                                     where sq_plano = @p_sq_orprior
                                                                                                       and x.sq_siw_solicitacao in (select chave from dbo.sp_fGetSolic(b.sq_siw_solicitacao,'UP'))
                                                                                                   )
                                                                           )
                                             )
                )
            and (@p_projeto        is null or (@p_projeto     is not null and (@p_projeto           in (select x.sq_siw_solicitacao
                                                                                                        from siw_solicitacao                     x
                                                                                                       where x.sq_siw_solicitacao in (select chave from dbo.sp_fGetSolic(b.sq_siw_solicitacao,'UP'))
                                                                                                     )
                                                                            )
                                             )
                )
*/
            and (@p_pais           is null or (@p_pais        is not null and f.sq_pais            = @p_pais))
            and (@p_regiao         is null or (@p_regiao      is not null and f.sq_regiao          = @p_regiao))
            and (@p_cidade         is null or (@p_cidade      is not null and f.sq_cidade          = @p_cidade))
            and (@p_usu_resp       is null or (@p_usu_resp    is not null and (b.executor          = @p_usu_resp or 0 < (select count(*) from pj_projeto_log where destinatario = @p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (@p_uorg_resp      is null or (@p_uorg_resp   is not null and d.concluida          = 'N' and l.sq_unidade = @p_uorg_resp))
            and (@p_sqcc           is null or (@p_sqcc        is not null and b.sq_cc              = @p_sqcc))
            and (@p_processo       is null or (@p_processo    = 'CLASSIF' and b.sq_cc is not null) or (@p_processo <> 'CLASSIF' and m1.sq_menu = cast(@p_processo as int)))
            and (@p_uf             is null or (@p_uf          is not null and f.co_uf              = @p_uf))
            and (@p_assunto        is null or (@p_assunto     is not null and dbo.acentos(b.titulo) like '%'+dbo.acentos(@p_assunto)+'%'))
            and (@p_palavra        is null or (@p_palavra     is not null and dbo.acentos(b.palavra_chave) like '%'+dbo.acentos(@p_palavra)+'%'))
            and (@p_fase           is null or (@p_fase        is not null and charIndex(''''+cast(b.sq_siw_tramite as varchar)+'''',@x_fase) > 0))
            and (@p_prazo          is null or (@p_prazo       is not null and d.concluida          = 'N' and cast(cast(b.fim as datetime)-cast(getDate() as datetime) as integer)+1 <=@p_prazo))
            and (@p_ini_i          is null or (@p_ini_i       is not null and (coalesce(b1.sigla,'-')   <> 'AT' and b.inicio between @p_ini_i and @p_ini_f) or (coalesce(b1.sigla,'-') = 'AT' and d.inicio_real between @p_ini_i and @p_ini_f)))
            and (@p_fim_i          is null or (@p_fim_i       is not null and (coalesce(b1.sigla,'-')   <> 'AT' and b.fim                between @p_fim_i and @p_fim_f) or (coalesce(b1.sigla,'-') = 'AT' and d.fim_real between @p_fim_i and @p_fim_f)))
            and (coalesce(@p_atraso,'N') = 'N'  or (@p_atraso      = 'S'       and d.concluida          = 'N' and b.fim+1-getDate()<0))
            and (@p_proponente     is null or (@p_proponente  is not null and (dbo.acentos(d.proponente)     like '%'+dbo.acentos(@p_proponente)+'%') or 
                                                                            (dbo.acentos(d1.nome)          like '%'+dbo.acentos(@p_proponente)+'%') or 
                                                                            (dbo.acentos(d1.nome_resumido) like '%'+dbo.acentos(@p_proponente)+'%')
                                             )
                )
            and (@p_unidade        is null or (@p_unidade     is not null and d.sq_unidade_resp    = @p_unidade))
            and (@p_prioridade     is null or (@p_prioridade  is not null and d.prioridade         = @p_prioridade))
            and (@p_solicitante    is null or (@p_solicitante is not null and b.solicitante        = @p_solicitante))
            and ((@p_tipo          = 1     and coalesce(b1.sigla,'-') = 'CI'   and b.cadastrador        = @p_pessoa) or
                 (@p_tipo          = 2     and coalesce(b1.sigla,'-') <> 'CI'  and d.concluida          = 'N' and ((a.sigla <> 'PJCAD' and b.executor = @p_pessoa) or
                                                                                                                  (a.sigla =  'PJCAD' and b2.acesso  >= 8)
                                                                                                                 )
                 ) or
                 (@p_tipo          = 3     and b2.acesso > 0) or
                 (@p_tipo          = 3     and charIndex(''''+cast(b.sq_unidade as varchar)+'''',@l_resp_unid) > 0) or
                 (@p_tipo          = 4     and coalesce(b1.sigla,'-') <> 'CA'  and b2.acesso > 0) or
                 (@p_tipo          = 4     and coalesce(b1.sigla,'-') <> 'CA'  and charIndex(''''+cast(b.sq_unidade as varchar)+'''',@l_resp_unid) > 0) or
                 (@p_tipo          = 5     and coalesce(b1.sigla,'-') <> 'CA') or
                 (@p_tipo          = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                )
            and ((@p_restricao <> 'GRPRPROP'    and @p_restricao <> 'GRPRRESPATU' and @p_restricao <> 'GRPRCC' and @p_restricao <> 'GRPRVINC') or 
                 ((@p_restricao = 'GRPRCC'      and b.sq_cc        is not null)   or 
                  (@p_restricao = 'GRPRPROP'    and d.proponente   is not null)   or 
                  (@p_restricao = 'GRPRRESPATU' and b.executor     is not null)   or
                  (@p_restricao = 'GRPRVINC'    and b.sq_solic_pai is not null)
                 )
                );
   End Else If substring(@p_restricao,1,4) = 'PEPR' Begin
      -- Recupera os programas que o usuário pode ver
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.ordem as or_servico,
                a.sq_unid_executora,  a.finalidade,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.objetivo_geral,
                a1.ordem as or_modulo,
                a2.sq_tipo_unidade as tp_exec, a2.nome as nm_unidade_exec, a2.informal as informal_exec,
                a2.vinculada as vinc_exec,a2.adm_central as adm_exec,
                a3.sq_pessoa as tit_exec,a4.sq_pessoa as subst_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      dbo.acentos(b.titulo) as ac_titulo,b.titulo,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when n.sq_cc is null
                                         then '???'
                                         else 'Classif: '+n.nome 
                                    end
                               else ' Plano: '+b2.titulo
                          end
                     else dbo.dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                b2.sq_plano,          b2.sq_plano_pai,               b2.titulo as nm_plano,
                b2.missao,            b2.valores,                    b2.visao_presente,
                b2.visao_futuro,      b2.inicio as inicio_plano,     b2.fim as fim_plano,
                b2.ativo as st_plano,
                c.sq_tipo_unidade,    c.nome as nm_unidade_exec,     c.informal,
                c.vinculada,          c.adm_central,
                b.codigo_interno,
                b.codigo_interno as cd_programa,                     d.ln_programa,
                d.exequivel,          d.inicio_real,                 d.fim_real,
                d.custo_real,
                d1.nome as nm_horizonte, d1.ativo as st_horizonte, 
                d7.nome as nm_natureza, d7.ativo as st_natureza,
                cast(b.fim as datetime)-cast(d.dias_aviso as integer) as aviso,
                e.sq_unidade as sq_unidade_resp,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,  e.sigla as sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                n.sq_cc,              n.nome as nm_cc,               n.sigla as sg_cc,
                o.nome_resumido as nm_solic, o.nome_resumido+' ('+o2.sigla+')' as nm_resp,
                p.nome_resumido as nm_exec,
                coalesce(q3.existe,0) as resp_meta,
                coalesce(q4.existe,0) as qtd_meta
           from siw_menu                                       a 
                   inner             join eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                     left            join eo_unidade_resp      a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                                      a3.tipo_respons            = 'T'           and
                                                                      a3.fim                     is null
                                                                     )
                     left            join eo_unidade_resp      a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                                      a4.tipo_respons            = 'S'           and
                                                                      a4.fim                     is null
                                                                     )
                   inner             join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner             join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                      inner          join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      left           join pe_plano             b2 on (b.sq_plano                 = b2.sq_plano)
                      inner          join (select sq_siw_solicitacao, dbo.acesso(sq_siw_solicitacao, @p_pessoa, null) as acesso
                                             from siw_solicitacao
                                          )                    b4 on (b.sq_siw_solicitacao       = b4.sq_siw_solicitacao)
                      inner          join pe_programa          d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        inner        join pe_horizonte         d1 on (d.sq_pehorizonte           = d1.sq_pehorizonte)
                        inner        join pe_natureza          d7 on (d.sq_penatureza            = d7.sq_penatureza)
                        inner        join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                          left       join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                                      e1.tipo_respons            = 'T'           and
                                                                      e1.fim                     is null
                                                                     )
                          left       join eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                                      e2.tipo_respons            = 'S'           and
                                                                      e2.fim                     is null
                                                                     )
                      inner          join co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                      left           join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                      left           join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                        inner        join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                          inner      join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                      left           join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                      left           join (select sq_siw_solicitacao, count(a.sq_solic_meta) as existe
                                             from siw_solic_meta a
                                            where a.sq_pessoa          = @p_pessoa
                                              and a.sq_siw_solicitacao is not null
                                           group  by a.sq_siw_solicitacao
                                          )                    q3 on (b.sq_siw_solicitacao       = q3.sq_siw_solicitacao)
                      left           join (select sq_siw_solicitacao, count(a.sq_solic_meta) as existe
                                             from siw_solic_meta a
                                            where a.sq_siw_solicitacao is not null
                                           group  by a.sq_siw_solicitacao
                                          )                    q4 on (b.sq_siw_solicitacao       = q4.sq_siw_solicitacao)
                   left              join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                   inner             join (select sq_siw_solicitacao, max(sq_siw_solic_log) as chave 
                                             from siw_solic_log
                                           group by sq_siw_solicitacao
                                          )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left            join pe_programa_log      k  on (j.chave                    = k.sq_siw_solic_log)
                       left          join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
          where a.sq_menu        = @p_menu
            and (@p_chave          is null or (@p_chave       is not null and b.sq_siw_solicitacao = @p_chave))
            and (@p_pais           is null or (@p_pais        is not null and f.sq_pais            = @p_pais))
            and (@p_regiao         is null or (@p_regiao      is not null and f.sq_regiao          = @p_regiao))
            and (@p_cidade         is null or (@p_cidade      is not null and f.sq_cidade          = @p_cidade))
            and (@p_usu_resp       is null or (@p_usu_resp    is not null and (b.executor          = @p_usu_resp or 0 < (select count(*) from ac_acordo_log where destinatario = @p_usu_resp and sq_siw_solicitacao = b.sq_siw_solicitacao))))
            and (@p_uorg_resp      is null or (@p_uorg_resp   is not null and b.conclusao          is null and l.sq_unidade = @p_uorg_resp))
            and (@p_sqcc           is null or (@p_sqcc        is not null and b.sq_cc              = @p_sqcc))
            and (@p_projeto        is null or (@p_projeto     is not null and b.sq_solic_pai       = @p_projeto))
/*
            and (@p_sq_acao_ppa   is null  or (@p_sq_acao_ppa is not null and 0                    < (select count(x.sq_siw_solicitacao)
                                                                                                      from siw_solicitacao                     x
                                                                                                           left  join siw_solicitacao_objetivo y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                                                                     where y.sq_siw_solicitacao is not null
                                                                                                       and y.sq_peobjetivo      = @p_sq_acao_ppa
                                                                                                    connect by prior x.sq_solic_pai = x.sq_siw_solicitacao
                                                                                                    start with x.sq_siw_solicitacao = b.sq_siw_solicitacao
                                                                                                   )
                                           )
                )
            and (@p_sq_orprior     is null or (@p_sq_orprior is not null and (b2.sq_plano          = @p_sq_orprior or 
                                                                            0                    < (select count(*)
                                                                                                      from siw_solicitacao
                                                                                                     where sq_plano = @p_sq_orprior
                                                                                                    connect by prior sq_solic_pai = sq_siw_solicitacao
                                                                                                    start with sq_siw_solicitacao = b.sq_siw_solicitacao
                                                                                                   )
                                                                           )
                                             )
                )
*/
            --and (@p_atividade      is null or (@p_atividade   is not null and i.sq_projeto_etapa   = @p_atividade))
            and (@p_uf             is null or (@p_uf          is not null and f.co_uf              = @p_uf))
            and (@p_assunto        is null or (@p_assunto     is not null and dbo.acentos(b.titulo) like '%'+dbo.acentos(@p_assunto)+'%'))
            and (@p_fase           is null or (@p_fase        is not null and charIndex(''''+ cast(b.sq_siw_tramite as varchar)+'''',@x_fase) > 0))
            and (@p_prazo          is null or (@p_prazo       is not null and b.conclusao          is null and cast(cast(b.fim as datetime)-cast(getDate() as datetime) as integer)+1 <=@p_prazo))
            and (@p_ini_i          is null or (@p_ini_i       is not null and b.inicio             between @p_ini_i and @p_ini_f))
            and (@p_fim_i          is null or (@p_fim_i       is not null and b.fim                between @p_fim_i and @p_fim_f))
            and (@p_unidade        is null or (@p_unidade     is not null and b.sq_unidade         = @p_unidade))
            and (@p_solicitante    is null or (@p_solicitante is not null and b.solicitante        = @p_solicitante))
            and (@p_palavra        is null or (@p_palavra     is not null and b.codigo_interno     like '%'+@p_palavra+'%'))
            and ((@p_tipo         = 1     and coalesce(b1.sigla,'-') = 'CI'   and b.cadastrador        = @p_pessoa) or
                 (@p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b.executor = @p_pessoa and b.conclusao is null) or
                 --(@p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b4.acesso > 15) or
                 (@p_tipo         = 3     and b4.acesso > 0) or
                 (@p_tipo         = 3     and charIndex(''''+cast(b.sq_unidade as varchar) +'''',@l_resp_unid) > 0) or
                 (@p_tipo         = 4     and coalesce(b1.sigla,'-') <> 'CA') or --  and b4.acesso > 0) or
                 --(@p_tipo         = 4     and coalesce(b1.sigla,'-') <> 'CA'  and charIndex(''''+b.sq_unidade+'''',@l_resp_unid) > 0) or
                 (@p_tipo         = 5) or
                 (@p_tipo         = 6     and b1.ativo          = 'S') -- and b4.acesso > 0)
                )
            and ((charIndex('PROJ',@p_restricao)    = 0 and
                  charIndex('ETAPA',@p_restricao)   = 0 and
                  charIndex('PROP',@p_restricao)    = 0 and
                  charIndex('RESPATU',@p_restricao) = 0 and
                  substring(@p_restricao,4,2)      <>'CC'
                 ) or 
                 ((charIndex('PROJ',@p_restricao)    > 0    and b.sq_solic_pai is not null) or
                  (charIndex('RESPATU',@p_restricao) > 0    and b.executor     is not null) or
                  (substring(@p_restricao,4,2)      ='CC'  and b.sq_cc        is not null)
                 )
                );
   End Else If substring(@p_restricao,1,2) = 'PD' or Substring(@p_restricao,1,4) = 'GRPD' Begin
      -- Recupera as viagens que o usuário pode ver
--      open @p_result for
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
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.opiniao,            b.sq_solic_pai,
                b.sq_unidade,         b.sq_cidade_origem,            b.palavra_chave,
                b.valor,              b.inicio-3 as aviso,
                b.sq_plano,
                case when b.sq_solic_pai is null 
                     then case when b.sq_plano is null
                               then case when n.sq_cc is null
                                         then '???'
                                         else 'Classif: '+n.nome 
                                    end
                               else ' Plano: '+b3.titulo
                          end
                     else dbo.dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                b1.sq_siw_tramite,    b1.nome as nm_tramite,         b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                c.sq_tipo_unidade,    c.nome as nm_unidade_exec,     c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end as nm_prioridade,
                d.ordem,
                d1.sq_pessoa as sq_prop, d1.tipo as tp_missao,       d11.codigo_interno,
                case d1.tipo when 'I' then 'Inicial' when 'P' then 'Prorrogação' else 'Complementação' end as nm_t_missao,
                d1.valor_adicional,   d1.desconto_alimentacao,       d1.desconto_transporte,
                d2.nome as nm_prop,   d2.nome_resumido as nm_prop_res,
                d3.sq_tipo_vinculo,   d3.nome as nm_tipo_vinculo,
                d4.sexo,              d4.cpf,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,  e.sigla as sg_unidade_resp,
                e1.sq_pessoa as titular, e2.sq_pessoa as substituto,
                o.nome_resumido as nm_solic, o.nome_resumido+' ('+o2.sigla+')' as nm_resp,
                p.nome_resumido as nm_exec,
                n1.valor_diaria, d1.valor_passagem as valor_trecho,
                d5.limite_passagem, d5.limite_diaria,
                dbo.to_char(r.saida,'dd/mm/yyyy, hh24:mi:ss') as phpdt_saida, dbo.to_char(r.chegada,'dd/mm/yyyy, hh24:mi:ss') as phpdt_chegada,
                dbo.pd_retornatrechos(b.sq_siw_solicitacao) as trechos
           from siw_menu                                a
                inner         join eo_unidade           a2 on (a.sq_unid_executora        = a2.sq_unidade)
                  left        join eo_unidade_resp      a3 on (a2.sq_unidade              = a3.sq_unidade and
                                                               a3.tipo_respons            = 'T'           and
                                                               a3.fim                     is null)
                  left        join eo_unidade_resp      a4 on (a2.sq_unidade              = a4.sq_unidade and
                                                               a4.tipo_respons            = 'S'           and
                                                               a4.fim                     is null)
                inner         join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                inner         join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                  inner       join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                  inner       join (select sq_siw_solicitacao, dbo.acesso(sq_siw_solicitacao, @p_pessoa, null) as acesso
                                      from siw_solicitacao
                                   )                    b2 on (b.sq_siw_solicitacao       = b2.sq_siw_solicitacao)
                  left           join pe_plano          b3 on (b.sq_plano                 = b3.sq_plano)
                  inner       join gd_demanda           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                    inner     join pd_missao            d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                      inner   join siw_solicitacao     d11 on (d1.sq_siw_solicitacao      = d11.sq_siw_solicitacao)
                      inner   join co_pessoa            d2 on (d1.sq_pessoa               = d2.sq_pessoa)
                        inner join co_tipo_vinculo      d3 on (d2.sq_tipo_vinculo         = d3.sq_tipo_vinculo)
                        inner join co_pessoa_fisica     d4 on (d2.sq_pessoa               = d4.sq_pessoa)
                        inner join (select x.sq_unidade, 
                                           coalesce(y.limite_passagem,0) as limite_passagem, 
                                           coalesce(y.limite_diaria,0)   as limite_diaria
                                      from pd_unidade                  x
                                           left join pd_unidade_limite y on (x.sq_unidade = y.sq_unidade and
                                                                             y.ano        = coalesce(@p_sq_orprior,y.ano)
                                                                            )
                                   )                    d5 on (d.sq_unidade_resp          = d5.sq_unidade)
                      inner   join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                        left  join eo_unidade_resp      e1 on (e.sq_unidade               = e1.sq_unidade and
                                                               e1.tipo_respons            = 'T'           and
                                                               e1.fim                     is null)
                        left  join eo_unidade_resp      e2 on (e.sq_unidade               = e2.sq_unidade and
                                                               e2.tipo_respons            = 'S'           and
                                                               e2.fim                     is null)
                    left      join ct_cc                n  on (b.sq_cc                    = n.sq_cc)
                    inner     join co_pessoa            o  on (b.solicitante              = o.sq_pessoa)
                      inner   join sg_autenticacao      o1 on (o.sq_pessoa                = o1.sq_pessoa)
                        inner join eo_unidade           o2 on (o1.sq_unidade              = o2.sq_unidade)
                    left      join co_pessoa            p  on (b.executor                 = p.sq_pessoa)
                  left        join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                  inner       join (select sq_siw_solicitacao, max(sq_siw_solic_log) as chave
                                      from siw_solic_log
                                    group by sq_siw_solicitacao
                                   )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                    left      join gd_demanda_log       k  on (j.chave                    = k.sq_siw_solic_log)
                      left    join sg_autenticacao      l  on (k.destinatario             = l.sq_pessoa)
                 left         join (select x.sq_siw_solicitacao, sum((y.quantidade*y.valor)) as valor_diaria
                                      from siw_solicitacao         x
                                           inner join pd_diaria  y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                    group by x.sq_siw_solicitacao
                                   )                    n1 on (b.sq_siw_solicitacao       = n1.sq_siw_solicitacao)
                 left         join (select x.sq_siw_solicitacao, sum(y.valor_trecho) as valor_trecho
                                      from siw_solicitacao              x
                                           inner join pd_deslocamento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                    group by x.sq_siw_solicitacao
                                   )                    q  on (b.sq_siw_solicitacao       = q.sq_siw_solicitacao)
                 left         join (select x.sq_siw_solicitacao, min(y.saida) as saida, max(y.chegada) as chegada
                                      from siw_solicitacao            x
                                           inner join pd_deslocamento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                    group by x.sq_siw_solicitacao
                                   )                    r  on (b.sq_siw_solicitacao       = r.sq_siw_solicitacao)
          where a.sq_menu         = @p_menu
            and (@p_sq_acao_ppa    is null or (@p_sq_acao_ppa is not null and d11.codigo_interno like '%'+cast(@p_sq_acao_ppa as varchar)+'%'))
            and (@p_assunto        is null or (@p_assunto     is not null and dbo.acentos(b.descricao) like '%'+dbo.acentos(@p_assunto)+'%'))
            and (@p_solicitante    is null or (@p_solicitante is not null and b.solicitante        = @p_solicitante))
            and (@p_unidade        is null or (@p_unidade     is not null and d.sq_unidade_resp    = @p_unidade))
            and (@p_proponente     is null or (@p_proponente  is not null and (dbo.acentos(d2.nome)          like '%'+dbo.acentos(@p_proponente)+'%') or
                                                                            (dbo.acentos(d2.nome_resumido) like '%'+dbo.acentos(@p_proponente)+'%')
                                             )
                )
            and (@p_ativo          is null or (@p_ativo       is not null and d1.tipo = @p_ativo))            
            and (@p_sq_orprior     is null or (@p_sq_orprior  is not null and d1.sq_pessoa         = @p_sq_orprior))
            and (@p_palavra        is null or (@p_palavra     is not null and d4.cpf = @p_palavra))
            and (@p_projeto        is null or (@p_projeto     is not null and 0 < (select count(distinct(x1.sq_siw_solicitacao)) from pd_missao_solic x1 , siw_solicitacao y1 where x1.sq_siw_solicitacao = y1.sq_siw_solicitacao and y1.sq_solic_pai = @p_projeto and x1.sq_solic_missao = b.sq_siw_solicitacao)))
            and (@p_atividade      is null or (@p_atividade   is not null and 0 < (select count(distinct(x2.sq_siw_solicitacao)) from pd_missao_solic x2 join pj_etapa_demanda x3 on (x2.sq_siw_solicitacao = x3.sq_siw_solicitacao and x3.sq_projeto_etapa = @p_atividade) where x2.sq_solic_missao = b.sq_siw_solicitacao)))
            and (@p_pais           is null or (@p_pais        is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.destino = y.sq_cidade and y.sq_pais = @p_pais and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (@p_regiao         is null or (@p_regiao      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.destino = y.sq_cidade and y.sq_regiao = @p_regiao and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (@p_uf             is null or (@p_uf          is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x, co_cidade y where x.destino = y.sq_cidade and y.co_uf = @p_uf and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (@p_cidade         is null or (@p_cidade      is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.destino = @p_cidade and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (@p_usu_resp       is null or (@p_usu_resp    is not null and 0 < (select count(distinct(sq_deslocamento)) from pd_deslocamento x where x.sq_cia_transporte = @p_usu_resp and x.sq_siw_solicitacao = b.sq_siw_solicitacao)))
            and (@p_ini_i          is null or (@p_ini_i       is not null and ((b.inicio           between @p_ini_i  and @p_ini_f) or
                                                                             (b.fim              between @p_ini_i  and @p_ini_f) or
                                                                             (@p_ini_i            between b.inicio and b.fim)   or
                                                                             (@p_fim_i            between b.inicio and b.fim)
                                                                            )
                                             )
                )
            and (@p_fase           is null or (@p_fase        is not null and charIndex(''''+cast(b.sq_siw_tramite as varchar)+'''',@x_fase) > 0))
            and (coalesce(@p_atraso,'N') = 'N'  or (@p_atraso      = 'S'       and d.concluida          = 'N' and cast(b.fim as datetime) + cast(1 as integer) - getDate()<0))
            and ((@p_tipo         = 1     and coalesce(b1.sigla,'-') = 'CI'   and b.cadastrador        = @p_pessoa) or
                 (@p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b.executor = @p_pessoa and b.conclusao is null) or
                 (@p_tipo         = 2     and b1.ativo = 'S' and coalesce(b1.sigla,'-') <> 'CI' and b2.acesso > 15) or
                 (@p_tipo         = 3     and b2.acesso > 0) or
                 (@p_tipo         = 3     and charIndex(''''+cast(b.sq_unidade as varchar)+'''', @l_resp_unid) > 0) or
                 (@p_tipo         = 4     and coalesce(b1.sigla,'-') <> 'CA') or
                 (@p_tipo         = 5) or
                 (@p_tipo         = 6     and b1.ativo          = 'S' and b2.acesso > 0)
                );
   End Else If @p_restricao = 'PJEXEC' Begin
      -- Recupera as demandas que o usuário pode ver
         select b.sq_siw_solicitacao, b.titulo, b.codigo_interno, b.codigo_externo
           from siw_solicitacao               b
                   inner   join siw_tramite   b1 on (b.sq_siw_tramite     = b1.sq_siw_tramite)
                   inner   join pj_projeto    d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
          where b.sq_menu        = @p_menu
            and coalesce(b1.sigla,'-') = 'EE' 
            and dbo.acesso(b.sq_siw_solicitacao,@p_pessoa, null) > 15;
   End Else If @p_restricao = 'PJLIST' Begin
      -- Recupera as demandas que o usuário pode ver
         select b.sq_siw_solicitacao, b.titulo, b.codigo_interno, b.codigo_externo
           from siw_solicitacao                b
                inner     join siw_tramite     b1 on (b.sq_siw_tramite     = b1.sq_siw_tramite and b1.sigla <> 'CA')
                inner     join pj_projeto      d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
          where b.sq_menu       = @p_menu
/*
            and (@p_projeto      is null or (@p_projeto     is not null and @p_projeto  in (select sq_siw_solicitacao
                                                                                              from siw_solicitacao
                                                                                            where sq_siw_solicitacao in (select chave from dbo.sp_fGetSolic(b.sq_siw_solicitacao,'UP'))
                                                                                        )
                                           )
                )
            and (@p_sq_acao_ppa  is null or (@p_sq_acao_ppa is not null and 0          < (select count(x.sq_siw_solicitacao)
                                                                                          from siw_solicitacao                     x
                                                                                               left  join siw_solicitacao_objetivo y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                                                         where y.sq_siw_solicitacao is not null
                                                                                           and y.sq_peobjetivo      = @p_sq_acao_ppa
                                                                                           and y.sq_siw_solicitacao in (select chave from dbo.sp_fGetSolic(b.sq_siw_solicitacao,'UP'))
                                                                                       )
                                           )
                )
            and (@p_sq_orprior   is null or (@p_sq_orprior is not null and (b.sq_plano= @p_sq_orprior or 
                                                                          0          < (select count(*)
                                                                                          from siw_solicitacao
                                                                                         where sq_plano = @p_sq_orprior
                                                                                           and sq_siw_solicitacao in (select chave from dbo.sp_fGetSolic(b.sq_siw_solicitacao,'UP'))
                                                                                       )
                                                                         )
                                           )
                )
*/
            and coalesce(b1.sigla,'-') <> 'CA' 
            and (dbo.acesso(b.sq_siw_solicitacao,@p_pessoa, null) > 0 or
                 charIndex(''''+cast(b.sq_unidade as varchar)+'''',@l_resp_unid) > 0
                );
   End Else If @p_restricao = 'PJLISTCAD' or @p_restricao = 'ORLISTCAD' Begin
      -- Recupera os projetos que o usuário pode ver
         select b.sq_siw_solicitacao, b.titulo, b.codigo_interno, b.codigo_externo
           from siw_solicitacao               b
                   inner   join siw_tramite   b1 on (b.sq_siw_tramite     = b1.sq_siw_tramite)
                   inner   join pj_projeto    d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
          where b.sq_menu         = @p_menu
            and coalesce(b1.sigla,'-') not in ('CA','AT')
            and (dbo.acesso(b.sq_siw_solicitacao,@p_pessoa, null) > 0 or
                 charIndex(''''+cast(b.sq_unidade as varchar)+'''',@l_resp_unid) > 0
                );
   End Else If @p_restricao = 'PELIST' Begin
      -- Recupera os programas para montagem da caixa de seleção
         select b.sq_siw_solicitacao, b.titulo, b.codigo_interno, b.codigo_externo, b.inicio, b.fim
           from siw_solicitacao            b
                inner   join siw_tramite   b1 on (b.sq_siw_tramite     = b1.sq_siw_tramite)
                inner   join pe_programa   c  on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
          where b.sq_menu         = @p_menu
            and coalesce(b1.sigla,'-') not in ('CA','AT')
            and ((@p_projeto      is null and b.sq_solic_pai is null) or (@p_projeto is not null and b.sq_solic_pai = @p_projeto))
/*
            and (@p_sq_acao_ppa   is null  or (@p_sq_acao_ppa is not null and 0                    < (select count(x.sq_siw_solicitacao)
                                                                                                      from siw_solicitacao                     x
                                                                                                           left  join siw_solicitacao_objetivo y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                                                                     where y.sq_siw_solicitacao is not null
                                                                                                       and y.sq_peobjetivo      = @p_sq_acao_ppa
                                                                                                    connect by prior x.sq_solic_pai = x.sq_siw_solicitacao
                                                                                                    start with x.sq_siw_solicitacao = b.sq_siw_solicitacao
                                                                                                   )
                                           )
                )
            and (@p_sq_orprior     is null or (@p_sq_orprior is not null and (b.sq_plano           = @p_sq_orprior or 
                                                                            0                    < (select count(*)
                                                                                                      from siw_solicitacao
                                                                                                     where sq_plano = @p_sq_orprior
                                                                                                    connect by prior sq_solic_pai = sq_siw_solicitacao
                                                                                                    start with sq_siw_solicitacao = b.sq_siw_solicitacao
                                                                                                   )
                                                                           )
                                             )
                );
*/
   End Else Begin -- Trata a vinculação entre serviços
      -- Recupera as solicitações que o usuário pode ver
         select b.sq_siw_solicitacao, b.codigo_interno,
                case when d.sq_siw_solicitacao is not null 
                     then b.titulo
                     else case when e.sq_siw_solicitacao is not null
                               then e.titulo
                               else case when f.sq_siw_solicitacao is not null
                                         then f1.titulo
                                         else case when h.sq_siw_solicitacao is not null
                                                   then b.codigo_interno
                                                   else case when i.sq_siw_solicitacao is not null
                                                             then cast(i.sq_siw_solicitacao as varchar)+' - '+
                                                                  case when len(i.assunto) > 50
                                                                       then substring(replace(i.assunto,char(13)+char(10),' '),1,50)+'...'
                                                                       else replace(i.assunto,char(13)+char(10),' ')
                                                                  end
                                                             else null
                                                        end
                                              end
                                    end
                          end
                end as titulo,
                coalesce(g.existe,0) as qtd_projeto
           from siw_menu                     a
                inner join siw_modulo        a1 on (a.sq_modulo          = a1.sq_modulo)
                inner join siw_menu_relac    a2 on (a.sq_menu            = a2.servico_cliente and
                                                    a2.servico_cliente   = cast(@p_restricao as int)
                                                   )
                inner join siw_solicitacao   b  on (a2.servico_fornecedor= b.sq_menu and
                                                    a2.sq_siw_tramite    = b.sq_siw_tramite and
                                                    b.sq_menu            = coalesce(@p_menu, b.sq_menu)
                                                   )
                inner   join siw_menu        b2 on (b.sq_menu            = b2.sq_menu)
                  inner join siw_modulo      b3 on (b2.sq_modulo         = b3.sq_modulo)
                left    join pj_projeto      d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
                left    join (select x.sq_siw_solicitacao, y.codigo_interno, x.vincula_demanda, 
                                     x.vincula_projeto, x.vincula_viagem,
                                     case when y.titulo is not null
                                          then y.titulo
                                          else w.nome_resumido+' - '+case when z.sq_cc is not null then z.nome else k1.titulo end+' ('+dbo.to_char(y.inicio,'dd/mm/yyyy')+'-'+dbo.to_char(y.fim,'dd/mm/yyyy')+')' end as titulo
                                from ac_acordo                     x
                                     left join     co_pessoa       w  on x.outra_parte         = w.sq_pessoa
                                     join          siw_solicitacao y  on x.sq_siw_solicitacao  = y.sq_siw_solicitacao
                                       left   join ct_cc           z  on y.sq_cc               = z.sq_cc
                                       left   join pj_projeto      k  on y.sq_solic_pai        = k.sq_siw_solicitacao
                                         left join siw_solicitacao k1 on (k.sq_siw_solicitacao = k1.sq_siw_solicitacao)
                             )               e  on (b.sq_siw_solicitacao = e.sq_siw_solicitacao)
                left    join pe_programa     f  on (b.sq_siw_solicitacao = f.sq_siw_solicitacao)
                  left  join siw_solicitacao f1 on (f.sq_siw_solicitacao = f1.sq_siw_solicitacao)
                left    join (select x1.sq_solic_pai, count(*) as existe
                                 from siw_solicitacao            x1
                                      inner join siw_menu        y1 on (x1.sq_menu = y1.sq_menu and
                                                                        y1.sigla   = 'PJCAD')
                               group by x1.sq_solic_pai
                              )              g on (b.sq_siw_solicitacao = g.sq_solic_pai)
                left    join cl_solicitacao  h on (b.sq_siw_solicitacao = h.sq_siw_solicitacao)
                left    join gd_demanda      i on (b.sq_siw_solicitacao = i.sq_siw_solicitacao)
          where a.sq_menu        = cast(@p_restricao as int)
            and b.sq_menu        = coalesce(@p_menu, b.sq_menu)
            and ((a1.sigla = 'DM' and b3.sigla = 'AC' and e.vincula_demanda  = 'S') or
                 (a1.sigla = 'PR' and b3.sigla = 'AC' and e.vincula_projeto  = 'S') or
                 (a1.sigla = 'PD' and b3.sigla = 'AC' and e.vincula_viagem   = 'S') or
                 (a1.sigla = 'AC' and b3.sigla = 'PR' and d.vincula_contrato = 'S') or
                 (a1.sigla = 'PD' and b3.sigla = 'PR' and d.vincula_viagem   = 'S') or
                 (a1.sigla = 'FN' and b3.sigla = 'AC') or
                 (a1.sigla = 'DM' and b3.sigla = 'PR') or
                 (a1.sigla = 'CO' and b3.sigla = 'PR') or
                 (a1.sigla = b3.sigla) or
                 (b3.sigla = 'PE') or
                 (b3.sigla = 'CO')
                )
            and (dbo.acesso(b.sq_siw_solicitacao,@p_pessoa, null) > 0 or
                 charIndex(''''+cast(b.sq_unidade as varchar)+'''',@l_resp_unid) > 0
                )
         order by titulo;
   End
End
