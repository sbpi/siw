SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO

create procedure dbo.SP_GetSolicGRA
   (@p_menu         int,
    @p_pessoa       int,
    @p_restricao    varchar(50) = null,
    @p_tipo         int,
    @p_ini_i        DateTime    = null,
    @p_ini_f        DateTime    = null,
    @p_fim_i        DateTime    = null,
    @p_fim_f        DateTime    = null,
    @p_atraso       varchar(1)  = null,
    @p_solicitante  int         = null,
    @p_unidade      int         = null,
    @p_prioridade   int         = null,
    @p_ativo        varchar(1)  = null,
    @p_proponente   varchar(90) = null,
    @p_chave        int         = null,
    @p_assunto      varchar(90) = null,
    @p_pais         int         = null,
    @p_regiao       int         = null,
    @p_uf           varchar(90) = null,
    @p_cidade       int         = null,
    @p_usu_resp     int         = null,
    @p_uorg_resp    int         = null,
    @p_palavra      varchar(90) = null,
    @p_prazo        int         = null,
    @p_fase         varchar(200)= null,
    @p_sqcc         int         = null,
    @p_projeto      int         = null,
    @p_atividade    int         = null
   ) as
begin
   Declare @l_item       varchar(18)
   Declare @l_fase       varchar(200)
   Declare @x_fase       varchar(200)
   
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

   If @p_restricao = 'GDCAD'  or @p_restricao = 'GDACOMP'  or
      @p_restricao = 'GDPCAD' or @p_restricao = 'GDPACOMP' or
      Substring(@p_restricao,1,4) = 'GRDM' Begin
      -- Recupera as demandas que o usuário pode ver
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.data_hora,                   b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.assunto,                     d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end nm_prioridade,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                d2.nome nm_envolv,
                m.titulo nm_projeto,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic,
                p.nome_resumido nm_exec
           from siw_menu             a 
                   inner      join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner      join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                      inner   join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner   join gd_demanda           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        inner join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                        inner join gd_demanda_envolv    d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                          inner join eo_unidade         d2 on (d1.sq_unidade              = d2.sq_unidade)
                      inner   join co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                      left outer join pj_projeto        m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)
                      left outer join ct_cc             n  on (b.sq_cc                    = n.sq_cc)
                      left outer join co_pessoa         o  on (b.solicitante              = o.sq_pessoa)
                      left outer join co_pessoa         p  on (b.executor                 = p.sq_pessoa)
                   left outer join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                   left outer join pj_etapa_demanda     i  on (b.sq_siw_solicitacao       = i.sq_siw_solicitacao)
                   inner      join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                      from siw_solic_log
                                    group by sq_siw_solicitacao
                                   )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left outer join gd_demanda_log     k  on (j.chave                    = k.sq_siw_solic_log)
                       left outer join sg_autenticacao  l  on (k.destinatario             = l.sq_pessoa)
          where a.sq_menu        = @p_menu
            and (@p_chave          is null or (@p_chave       is not null and b.sq_siw_solicitacao = @p_chave))
            and (@p_pais           is null or (@p_pais        is not null and f.sq_pais            = @p_pais))
            and (@p_regiao         is null or (@p_regiao      is not null and f.sq_regiao          = @p_regiao))
            and (@p_cidade         is null or (@p_cidade      is not null and f.sq_cidade          = @p_cidade))
            and (@p_usu_resp       is null or (@p_usu_resp    is not null and b.executor           = @p_usu_resp))
            and (@p_uorg_resp      is null or (@p_uorg_resp   is not null and d.concluida          = 'N' and l.sq_unidade = @p_uorg_resp))
            and (@p_sqcc           is null or (@p_sqcc        is not null and b.sq_cc              = @p_sqcc))
            and (@p_projeto        is null or (@p_projeto     is not null and b.sq_solic_pai       = @p_projeto))
            and (@p_atividade      is null or (@p_atividade   is not null and i.sq_projeto_etapa   = @p_atividade))
            and (@p_uf             is null or (@p_uf          is not null and f.co_uf              = @p_uf))
            and (@p_assunto        is null or (@p_assunto     is not null and siw.acentos(d.assunto,null) like '%'+siw.acentos(@p_assunto,null)+'%'))
            and (@p_palavra        is null or (@p_palavra     is not null and siw.acentos(b.palavra_chave,null) like '%'+siw.acentos(@p_palavra,null)+'%'))
            and (@p_fase           is null or (@p_fase        is not null and CharIndex(''''+cast(b.sq_siw_tramite as varchar)+'''',@x_fase) > 0))
            and (@p_prazo          is null or (@p_prazo       is not null and d.concluida          = 'N' and b.fim-getdate()+1 <=@p_prazo))
            and (@p_ini_i          is null or (@p_ini_i       is not null and b.inicio             between @p_ini_i and @p_ini_f))
            and (@p_fim_i          is null or (@p_fim_i       is not null and b.fim                between @p_fim_i and @p_fim_f))
            and (IsNull(@p_atraso,'N') = 'N' or (@p_atraso    = 'S'       and d.concluida          = 'N' and b.fim+1-getdate()<0))
            and (@p_proponente     is null or (@p_proponente  is not null and siw.acentos(d.proponente,null) like '%'+siw.acentos(@p_proponente,null)+'%'))
            and (@p_unidade        is null or (@p_unidade     is not null and d.sq_unidade_resp    = @p_unidade))
            and (@p_prioridade     is null or (@p_prioridade  is not null and d.prioridade  = @p_prioridade))
            and (@p_solicitante    is null or (@p_solicitante is not null and b.solicitante = @p_solicitante))
            and ((@p_tipo         = 1     and IsNull(b1.sigla,'-') = 'CI'   and b.cadastrador        = @p_pessoa) or
                 (@p_tipo         = 2     and IsNull(b1.sigla,'-') <> 'CI'  and b.executor           = @p_pessoa and d.concluida = 'N') or
                 (@p_tipo         = 3     and siw.acesso(b.sq_siw_solicitacao,@p_pessoa) > 0) or
                 (@p_tipo         = 4     and IsNull(b1.sigla,'-') <> 'CA'  and siw.acesso(b.sq_siw_solicitacao,@p_pessoa) > 0)
                )
   End Else If @p_restricao = 'PJCAD' or @p_restricao = 'PJACOMP' or
         Substring(@p_restricao,1,4) = 'GRPR' Begin
      -- Recupera as demandas que o usuário pode ver
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.como_funciona,               a.acompanha_fases,
                a.sq_unid_executora,  a.finalidade,                  a.arquivo_proced,
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.data_hora,          a.envia_dia_util,              a.descricao,
                a.justificativa,
                a1.nome nm_modulo,    a1.sigla sg_modulo,            a1.objetivo_geral,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.data_hora,                   b.opiniao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,
                b1.sq_siw_tramite,    b1.nome nm_tramite,            b1.ordem or_tramite,
                b1.sigla sg_tramite,  b1.ativo,
                c.sq_tipo_unidade,    c.nome nm_unidade_exec,        c.informal,
                c.vinculada,          c.adm_central,
                d.sq_unidade_resp,    d.titulo,                      d.prioridade,
                d.aviso_prox_conc,    d.dias_aviso,                  d.inicio_real,
                d.fim_real,           d.concluida,                   d.data_conclusao,
                d.nota_conclusao,     d.custo_real,                  d.proponente,
                case d.prioridade when 0 then 'Alta' when 1 then 'Média' else 'Normal' end nm_prioridade,
                b.fim-d.dias_aviso aviso,
                e.sq_tipo_unidade,    e.nome nm_unidade_resp,        e.informal informal_resp,
                e.vinculada vinc_resp,e.adm_central adm_resp,
                f.sq_pais,            f.sq_regiao,                   f.co_uf,
                d2.nome nm_envolv,
                m.titulo nm_projeto,
                n.sq_cc,              n.nome nm_cc,                  n.sigla sg_cc,
                o.nome_resumido nm_solic,
                p.nome_resumido nm_exec
           from siw_menu             a 
                   inner      join siw_modulo           a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner      join siw_solicitacao      b  on (a.sq_menu                  = b.sq_menu)
                      inner   join siw_tramite          b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite)
                      inner   join pj_projeto           d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao)
                        inner join eo_unidade           e  on (d.sq_unidade_resp          = e.sq_unidade)
                        inner join pj_projeto_envolv    d1 on (d.sq_siw_solicitacao       = d1.sq_siw_solicitacao)
                          inner join eo_unidade         d2 on (d1.sq_unidade              = d2.sq_unidade)
                      inner   join co_cidade            f  on (b.sq_cidade_origem         = f.sq_cidade)
                      left outer join pj_projeto        m  on (b.sq_solic_pai             = m.sq_siw_solicitacao)
                      left outer join ct_cc             n  on (b.sq_cc                    = n.sq_cc)
                      left outer join co_pessoa         o  on (b.solicitante              = o.sq_pessoa)
                      left outer join co_pessoa         p  on (b.executor                 = p.sq_pessoa)
                   left outer join eo_unidade           c  on (a.sq_unid_executora        = c.sq_unidade)
                   inner      join (select sq_siw_solicitacao, max(sq_siw_solic_log) chave 
                                      from siw_solic_log
                                    group by sq_siw_solicitacao
                                   )                    j  on (b.sq_siw_solicitacao       = j.sq_siw_solicitacao)
                     left outer join pj_projeto_log     k  on (j.chave                    = k.sq_siw_solic_log)
                       left outer join sg_autenticacao  l  on (k.destinatario             = l.sq_pessoa)
          where a.sq_menu        = @p_menu
            and (@p_chave          is null or (@p_chave       is not null and b.sq_siw_solicitacao = @p_chave))
            and (@p_pais           is null or (@p_pais        is not null and f.sq_pais            = @p_pais))
            and (@p_regiao         is null or (@p_regiao      is not null and f.sq_regiao          = @p_regiao))
            and (@p_cidade         is null or (@p_cidade      is not null and f.sq_cidade          = @p_cidade))
            and (@p_usu_resp       is null or (@p_usu_resp    is not null and b.executor           = @p_usu_resp))
            and (@p_uorg_resp      is null or (@p_uorg_resp   is not null and d.concluida          = 'N' and l.sq_unidade = @p_uorg_resp))
            and (@p_sqcc           is null or (@p_sqcc        is not null and b.sq_cc              = @p_sqcc))
            and (@p_projeto        is null or (@p_projeto     is not null and b.sq_solic_pai       = @p_projeto))
            and (@p_uf             is null or (@p_uf          is not null and f.co_uf              = @p_uf))
            and (@p_assunto        is null or (@p_assunto     is not null and siw.acentos(d.titulo,null) like '%'+siw.acentos(@p_assunto,null)+'%'))
            and (@p_palavra        is null or (@p_palavra     is not null and siw.acentos(b.palavra_chave,null) like '%'+siw.acentos(@p_palavra,null)+'%'))
            and (@p_fase           is null or (@p_fase        is not null and CharIndex(''''+cast(b.sq_siw_tramite as varchar)+'''',@x_fase) > 0))
            and (@p_prazo          is null or (@p_prazo       is not null and d.concluida          = 'N' and b.fim-getdate()+1 <=@p_prazo))
            and (@p_ini_i          is null or (@p_ini_i       is not null and b.inicio             between @p_ini_i and @p_ini_f))
            and (@p_fim_i          is null or (@p_fim_i       is not null and b.fim                between @p_fim_i and @p_fim_f))
            and (IsNull(@p_atraso,'N') = 'N' or (@p_atraso    = 'S'       and d.concluida          = 'N'  and b.fim+1-getdate()<0))
            and (@p_proponente     is null or (@p_proponente  is not null and siw.acentos(d.proponente,null) like '%'+siw.acentos(@p_proponente,null)+'%'))
            and (@p_unidade        is null or (@p_unidade     is not null and d.sq_unidade_resp    = @p_unidade))
            and (@p_prioridade     is null or (@p_prioridade  is not null and d.prioridade         = @p_prioridade))
            and (@p_solicitante    is null or (@p_solicitante is not null and b.solicitante        = @p_solicitante))
            and ((@p_tipo         = 1     and IsNull(b1.sigla,'-') = 'CI'   and b.cadastrador        = @p_pessoa) or
                 (@p_tipo         = 2     and IsNull(b1.sigla,'-') <> 'CI'  and b.executor           = @p_pessoa and d.concluida = 'N') or
                 (@p_tipo         = 3     and siw.acesso(b.sq_siw_solicitacao,@p_pessoa) > 0) or
                 (@p_tipo         = 4     and IsNull(b1.sigla,'-') <> 'CA'  and siw.acesso(b.sq_siw_solicitacao,@p_pessoa) > 0)
                )
   End
End

















GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

