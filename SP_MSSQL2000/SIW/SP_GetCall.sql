SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

setuser N'SIW'
GO



create procedure dbo.SP_GetCall
   (@p_chave     int         = null,
    @p_pessoa    int,
    @p_tipo      int,
    @p_restricao varchar(50) = null,
    @p_sq_cc     int         = null,
    @p_contato   varchar(50) = null,
    @p_numero    varchar(20) = null,
    @p_inicio    datetime    = null,
    @p_fim       datetime    = null,
    @p_ativo     varchar(1)  = null
   ) as
begin
   If @p_restricao is null Begin
      -- Recupera os contratos que o usuário pode ver
         select a.sq_ligacao, a.data, a.data ordem,  
                IsNull(a.numero,'---') numero, a.duracao, a.sq_usuario_central, a.trabalho,  
                case when a.trabalho is null then 0 else 1 end informada, c.codigo sq_ramal,e.numero sq_tronco,  
                case when a.trabalho is null then '-' when a.trabalho = 'S' then 'Sim' else 'Não' end d_trabalho,  
                IsNull(a.outra_parte_cont,'-') d_nome,  
                IsNull(g.sigla,'-') d_cc, IsNull(lower(h.localidade),'-') localidade,  
                case a.entrante when 'S' then case a.recebida when 'N' then 'NAT' else 'REC' end else 'ORI' end tipo,
                i.nome_resumido responsavel
           from tt_ligacao         a 
                   inner      join tt_usuario         b on (a.sq_usuario_central = b.sq_usuario_central) 
                      inner   join co_pessoa          i on (b.usuario            = i.sq_pessoa)
                   inner      join tt_ramal           c on (a.sq_ramal           = c.sq_ramal) 
                   inner      join tt_tronco          d on (a.sq_tronco          = d.sq_tronco) 
                      inner   join co_pessoa_telefone e on (d.sq_pessoa_telefone = e.sq_pessoa_telefone) 
                   left outer join ct_cc              g on (a.sq_cc              = g.sq_cc) 
                   left outer join tt_prefixos        h on (a.sq_prefixo         = h.sq_prefixo) 
          where ((@p_tipo           = 1 and b.usuario = @p_pessoa) or @p_tipo         <> 1)
            and (@p_sq_cc           is null or (@p_sq_cc   is not null and a.sq_cc   = @p_sq_cc))
            and (@p_contato         is null or (@p_contato is not null and siw.acentos(upper(a.outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
            and (@p_numero          is null or (@p_numero  is not null and a.numero like '%'+@p_numero+'%'))
            and (@p_inicio          is null or ((@p_inicio  is not null and @p_fim is not null and a.data between @p_inicio and @p_fim+1) or
                                               (@p_fim     is null                           and a.data >= @p_inicio)
                                              )
                )
            and ((@p_ativo          is null and a.trabalho is null) or
                 (@p_ativo          = 'A'   and a.trabalho is not null) or
                 (@p_ativo          in ('S','N') and a.trabalho = @p_ativo)
                )
         UNION 
         select a.sq_ligacao, a.data, a.data ordem,  
                IsNull(a.numero,'---') numero, a.duracao, a.sq_usuario_central, a.trabalho,  
                case when a.trabalho is null then 0 else 1 end informada, d.codigo sq_ramal,f.numero sq_tronco,  
                case when a.trabalho is null then '-' when a.trabalho = 'S' then 'Sim' else 'Não' end d_trabalho,  
                IsNull(a.outra_parte_cont,'-') d_nome,  
                IsNull(g.sigla,'-') d_cc, IsNull(lower(h.localidade),'-') localidade,  
                case a.entrante when 'S' then case a.recebida when 'N' then 'NAT' else 'REC' end else 'ORI' end tipo,
                null
           from tt_ligacao         a 
                   inner      join tt_ramal_usuario   c on (a.sq_ramal           = c.sq_ramal) 
                      inner   join tt_usuario         b on (c.sq_usuario_central = b.sq_usuario_central) 
                   inner      join tt_ramal           d on (a.sq_ramal           = d.sq_ramal) 
                   inner      join tt_tronco          e on (a.sq_tronco          = e.sq_tronco) 
                      inner   join co_pessoa_telefone f on (e.sq_pessoa_telefone = f.sq_pessoa_telefone) 
                   left outer join ct_cc              g on (a.sq_cc              = g.sq_cc) 
                   left outer join tt_prefixos        h on (a.sq_prefixo         = h.sq_prefixo) 
          where a.sq_usuario_central is null 
            and a.data        between c.inicio and IsNull(c.fim,getdate())
            and b.usuario            = @p_pessoa
            and (@p_sq_cc             is null or (@p_sq_cc   is not null and a.sq_cc   = @p_sq_cc))
            and (@p_contato           is null or (@p_contato is not null and siw.acentos(upper(a.outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
            and (@p_numero            is null or (@p_numero  is not null and a.numero like '%'+@p_numero+'%'))
            and (@p_inicio            is null or ((@p_inicio  is not null and @p_fim is not null and a.data between @p_inicio and @p_fim+1) or
                                                 (@p_fim     is null                           and a.data >= @p_inicio)
                                                )
                )
            and ((@p_ativo            is null and a.trabalho is null) or
                 (@p_ativo            = 'A'   and a.trabalho is not null) or
                 (@p_ativo            in ('S','N') and a.trabalho = @p_ativo)
                )
   End Else Begin
      If @p_restricao = 'REGISTRO' Begin
         -- Recupera os dados de uma ligação
            select a.*, c.nome_resumido responsavel
              from tt_ligacao a
                     left outer   join tt_usuario b on (a.sq_usuario_central = b.sq_usuario_central)
                       left outer join co_pessoa  c on (b.usuario            = c.sq_pessoa)
             where sq_ligacao = @p_chave
      End Else If @p_restricao = 'DADOS' Begin
         -- Recupera todos os dados de uma ligação
            select a.sq_ligacao, a.data, a.data ordem, 
                   IsNull(a.numero,'---') numero, a.duracao, a.valor, 
                   case when a.trabalho is null then 0 else 1 end informada, d.codigo sq_ramal,f.numero sq_tronco, 
                   case a.entrante when 'S' then case a.recebida when 'N' then 'Não atendida' else 'Recebida' end else 'Originada' end tipo 
              from tt_ligacao         a, 
                   tt_usuario         b,  
                   tt_ramal_usuario   c, 
                   tt_ramal           d,  
                   tt_tronco          e,  
                   co_pessoa_telefone f  
             where a.sq_ramal           = c.sq_ramal 
               and c.sq_usuario_central = b.sq_usuario_central 
               and a.sq_ramal           = d.sq_ramal 
               and a.sq_tronco          = e.sq_tronco 
               and e.sq_pessoa_telefone = f.sq_pessoa_telefone 
               and a.sq_ligacao         = @p_chave
      End Else If @p_restricao = 'HERANCA' Begin
         -- Recupera os dados da última ligação de um número
            select sq_cc, sq_acordo, assunto, imagem, fax, trabalho, outra_parte_cont 
              from tt_ligacao 
             where sq_ligacao = (select max(sq_ligacao) sq_ligacao 
                                   from tt_ligacao a inner join tt_usuario b on (a.sq_usuario_central = b.sq_usuario_central)
                                  where a.trabalho           is not null 
                                    and b.usuario            = @p_pessoa
                                    and a.numero             = @p_numero
                                )
      End Else If @p_restricao = 'LOG' Begin
         -- Recupera o histórico de transferências de uma ligação
            select a.data, a.observacao, 
                   d.nome_resumido origem, e.nome_resumido destino 
              from tt_ligacao_log a
                      inner    join tt_usuario     b on (a.usuario_origem  = b.sq_usuario_central)
                         inner join co_pessoa      d on (b.usuario         = d.sq_pessoa)
                      inner    join tt_usuario     c on (a.usuario_destino = c.sq_usuario_central)
                         inner join co_pessoa      e on (c.usuario         = e.sq_pessoa)
             where a.sq_ligacao      = @p_chave
      End Else If @p_restricao = 'PESSOAS' Begin
         -- Recupera o resumo de ligações particulares por pessoa
            select x.sq_usuario_central, y.nome_resumido, 'A trabalho' trabalho,  
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from tt_usuario x
                   inner      join co_pessoa y on (x.usuario = y.sq_pessoa)
                   left outer join (select sq_usuario_central, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and data between @p_inicio and @p_fim+1 and trabalho='S' and entrante='N' group by sq_usuario_central)                  tt_ori on (x.sq_usuario_central     = tt_ori.sq_usuario_central) 
                   left outer join (select sq_usuario_central, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and data between @p_inicio and @p_fim+1 and trabalho='S' and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec on (x.sq_usuario_central     = tt_rec.sq_usuario_central)
                   left outer join (select sq_usuario_central, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and data between @p_inicio and @p_fim+1 and trabalho='S' and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat on (x.sq_usuario_central     = tt_nat.sq_usuario_central)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            UNION 
            select x.sq_usuario_central, y.nome_resumido, 'Particular' trabalho,  
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from tt_usuario x
                   inner      join co_pessoa y on (x.usuario = y.sq_pessoa)
                   left outer join (select sq_usuario_central, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and data between @p_inicio and @p_fim+1 and trabalho='N' and entrante='N' group by sq_usuario_central)                  tt_ori on (x.sq_usuario_central     = tt_ori.sq_usuario_central)
                   left outer join (select sq_usuario_central, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and data between @p_inicio and @p_fim+1 and trabalho='N' and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec on (x.sq_usuario_central     = tt_rec.sq_usuario_central) 
                   left outer join (select sq_usuario_central, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and data between @p_inicio and @p_fim+1 and trabalho='N' and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat on (x.sq_usuario_central     = tt_nat.sq_usuario_central)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            UNION 
            select x.sq_usuario_central, y.nome_resumido, 'Total' trabalho, 
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from tt_usuario x
                   inner      join co_pessoa y on (x.usuario = y.sq_pessoa)
                   left outer join (select sq_usuario_central, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and data between @p_inicio and @p_fim+1 and trabalho is not null and entrante='N' group by sq_usuario_central)                  tt_ori on (x.sq_usuario_central     = tt_ori.sq_usuario_central)
                   left outer join (select sq_usuario_central, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and data between @p_inicio and @p_fim+1 and trabalho is not null and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec on (x.sq_usuario_central     = tt_rec.sq_usuario_central)
                   left outer join (select sq_usuario_central, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and data between @p_inicio and @p_fim+1 and trabalho is not null and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat on (x.sq_usuario_central     = tt_nat.sq_usuario_central)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            order by qtd_tot desc, dura_tot desc, nome_resumido, trabalho
      End Else If @p_restricao = 'GERAL' Begin
         -- Recupera o resumo total de ligações da pessoa informada
            select 'A trabalho' trabalho, 
                   tt_ori.qtd ori_qtd, tt_ori.dura ori_dura, 
                   tt_rec.qtd rec_qtd, tt_rec.dura rec_dura, 
                   tt_nat.qtd nat_qtd, tt_nat.dura nat_dura, 
                  (tt_ori.qtd+tt_rec.qtd+tt_nat.qtd) qtd_tot, 
                  (tt_ori.dura+tt_rec.dura+tt_nat.dura) dura_tot       
              from (select count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a 
                     where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='S' and entrante='N'
                       and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                       and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                       and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                   ) tt_ori, 
                   (select count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a 
                     where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='S' and entrante='S' and recebida='S'
                       and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                       and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                       and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                   ) tt_rec,
                   (select count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                     where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='S' and entrante='S' and recebida='N'
                       and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                       and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                       and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                   ) tt_nat
            UNION 
            select 'Particular' trabalho, 
                   tt_ori.qtd ori_qtd, tt_ori.dura ori_dura, 
                   tt_rec.qtd rec_qtd, tt_rec.dura rec_dura, 
                   tt_nat.qtd nat_qtd, tt_nat.dura nat_dura, 
                   (tt_ori.qtd+tt_rec.qtd+tt_nat.qtd) qtd_tot, 
                   (tt_ori.dura+tt_rec.dura+tt_nat.dura) dura_tot 
              from (select count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                     where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='N' and entrante='N'
                       and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                       and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                       and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                   ) tt_ori, 
                   (select count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                     where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='N' and entrante='S' and recebida='S'
                       and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                       and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                       and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                   ) tt_rec, 
                   (select count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                     where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='N' and entrante='S' and recebida='N'
                       and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                       and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                       and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                   ) tt_nat 
            UNION 
            select 'Total' trabalho, 
                   tt_ori.qtd ori_qtd, tt_ori.dura ori_dura, 
                   tt_rec.qtd rec_qtd, tt_rec.dura rec_dura, 
                   tt_nat.qtd nat_qtd, tt_nat.dura nat_dura, 
                  (tt_ori.qtd+tt_rec.qtd+tt_nat.qtd) qtd_tot, 
                  (tt_ori.dura+tt_rec.dura+tt_nat.dura) dura_tot 
              from (select count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                     where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho is not null and entrante='N'
                       and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                       and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                       and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                   ) tt_ori, 
                   (select count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                     where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho is not null and entrante='S' and recebida='S'
                       and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                       and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                       and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                   ) tt_rec, 
                   (select count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a 
                     where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho is not null and entrante='S' and recebida='N'
                       and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                       and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                       and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                   ) tt_nat 
            order by trabalho
      End Else If @p_restricao = 'CTCC' Begin
         -- Recupera o resumo por centro de custos das ligações da pessoa informada
            select x.sq_cc, x.sigla, 'A trabalho' trabalho, 
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura,
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura,
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura,
                   (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot,
                   (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot
              from ct_cc x
                      left outer join (select sq_cc, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a 
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='S' and entrante='N'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by sq_cc
                                      ) tt_ori on (x.sq_cc = tt_ori.sq_cc) 
                      left outer join (select sq_cc, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a 
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='S' and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by sq_cc
                                      ) tt_rec on (x.sq_cc = tt_rec.sq_cc) 
                      left outer join (select sq_cc, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='S' and entrante='S' and recebida='N'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by sq_cc
                                      ) tt_nat on (x.sq_cc = tt_nat.sq_cc)
              where x.sq_cc_pai is not null
                and 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0))
            UNION 
            select x.sq_cc, x.sigla, 'Particular' trabalho, 
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura,
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura,
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura,
                   (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot,
                   (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot
              from ct_cc x
                      left outer join (select sq_cc, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a 
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='N' and entrante='N'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by sq_cc
                                      ) tt_ori on (x.sq_cc = tt_ori.sq_cc) 
                      left outer join (select sq_cc, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a 
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='N' and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by sq_cc
                                      ) tt_rec on (x.sq_cc = tt_rec.sq_cc) 
                      left outer join (select sq_cc, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho='N' and entrante='S' and recebida='N'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by sq_cc
                                      ) tt_nat on (x.sq_cc = tt_nat.sq_cc)
              where x.sq_cc_pai is not null
                and 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0))
            UNION 
            select x.sq_cc, x.sigla, 'Total' trabalho, 
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura,
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura,
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura,
                   (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot,
                   (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot
              from ct_cc x
                      left outer join (select sq_cc, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a 
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho is not null and entrante='N'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by sq_cc
                                      ) tt_ori on (x.sq_cc = tt_ori.sq_cc) 
                      left outer join (select sq_cc, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a 
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho is not null and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by sq_cc
                                      ) tt_rec on (x.sq_cc = tt_rec.sq_cc) 
                      left outer join (select sq_cc, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1 and trabalho is not null and entrante='S' and recebida='N'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by sq_cc
                                      ) tt_nat on (x.sq_cc = tt_nat.sq_cc)
              where x.sq_cc_pai is not null
                and 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0))
            order by sigla, trabalho
      End Else If @p_restricao = 'MES' Begin
         -- Recupera o resumo mensal das ligações da pessoa informada
            select x.mes, 'A trabalho' trabalho,  
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from (select distinct replace(substring(convert(varchar, data,120), 1, 7),'-','') mes from tt_ligacao) x
                      left outer join (select replace(substring(convert(varchar, data,120), 1, 7),'-','') mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='S' and entrante='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by replace(substring(convert(varchar, data,120), 1, 7),'-','')) tt_ori on (x.mes = tt_ori.mes)
                      left outer join (select replace(substring(convert(varchar, data,120), 1, 7),'-','') mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='S' and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by replace(substring(convert(varchar, data,120), 1, 7),'-','')) tt_rec on (x.mes = tt_rec.mes)
                      left outer join (select replace(substring(convert(varchar, data,120), 1, 7),'-','') mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='S' and entrante='S' and recebida='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by replace(substring(convert(varchar, data,120), 1, 7),'-','')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            UNION 
            select x.mes, 'Particular' trabalho,  
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from (select distinct replace(substring(convert(varchar, data,120), 1, 7),'-','') mes from tt_ligacao) x
                      left outer join (select replace(substring(convert(varchar, data,120), 1, 7),'-','') mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='N' and entrante='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by replace(substring(convert(varchar, data,120), 1, 7),'-','')) tt_ori on (x.mes = tt_ori.mes)
                      left outer join (select replace(substring(convert(varchar, data,120), 1, 7),'-','') mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='N' and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by replace(substring(convert(varchar, data,120), 1, 7),'-','')) tt_rec on (x.mes = tt_rec.mes)
                      left outer join (select replace(substring(convert(varchar, data,120), 1, 7),'-','') mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='N' and entrante='S' and recebida='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by replace(substring(convert(varchar, data,120), 1, 7),'-','')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            UNION 
            select x.mes, 'Total' trabalho,  
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from (select distinct replace(substring(convert(varchar, data,120), 1, 7),'-','') mes from tt_ligacao) x
                      left outer join (select replace(substring(convert(varchar, data,120), 1, 7),'-','') mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho is not null and entrante='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by replace(substring(convert(varchar, data,120), 1, 7),'-','')) tt_ori on (x.mes = tt_ori.mes)
                      left outer join (select replace(substring(convert(varchar, data,120), 1, 7),'-','') mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho is not null and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by replace(substring(convert(varchar, data,120), 1, 7),'-','')) tt_rec on (x.mes = tt_rec.mes)
                      left outer join (select replace(substring(convert(varchar, data,120), 1, 7),'-','') mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho is not null and entrante='S' and recebida='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by replace(substring(convert(varchar, data,120), 1, 7),'-','')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            order by 1, 2
      End Else If @p_restricao = 'DIASEMANA' Begin
         -- Recupera o resumo das ligações da pessoa informada por dia da semana
            select x.mes,case x.mes when '1' then 'DOM' when '2' then 'SEG' when '3' then 'TER' when '4' then 'QUA' when '5' then 'QUI' when '6' then 'SEX' else 'SAB' end dia, 'A trabalho' trabalho,
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from (select distinct datepart(dw, data) mes from tt_ligacao) x
                      left outer join (select datepart(dw, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='S' and entrante='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(dw, data)) tt_ori on (x.mes = tt_ori.mes)
                      left outer join (select datepart(dw, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='S' and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(dw, data)) tt_rec on (x.mes = tt_rec.mes)
                      left outer join (select datepart(dw, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='S' and entrante='S' and recebida='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(dw, data)) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            UNION 
            select x.mes,case x.mes when '1' then 'DOM' when '2' then 'SEG' when '3' then 'TER' when '4' then 'QUA' when '5' then 'QUI' when '6' then 'SEX' else 'SAB' end dia, 'Particular' trabalho,
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from (select distinct datepart(dw, data) mes from tt_ligacao) x
                      left outer join (select datepart(dw, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='N' and entrante='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(dw, data)) tt_ori on (x.mes = tt_ori.mes)
                      left outer join (select datepart(dw, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='N' and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(dw, data)) tt_rec on (x.mes = tt_rec.mes)
                      left outer join (select datepart(dw, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='N' and entrante='S' and recebida='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(dw, data)) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            UNION 
            select x.mes,case x.mes when '1' then 'DOM' when '2' then 'SEG' when '3' then 'TER' when '4' then 'QUA' when '5' then 'QUI' when '6' then 'SEX' else 'SAB' end dia, 'Total' trabalho,
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from (select distinct datepart(dw, data) mes from tt_ligacao) x
                      left outer join (select datepart(dw, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho is not null and entrante='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(dw, data)) tt_ori on (x.mes = tt_ori.mes)
                      left outer join (select datepart(dw, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho is not null and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(dw, data)) tt_rec on (x.mes = tt_rec.mes)
                      left outer join (select datepart(dw, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho is not null and entrante='S' and recebida='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(dw, data)) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            order by 1, 2
      End Else If @p_restricao = 'DIAMES' Begin
         -- Recupera o resumo das ligações da pessoa informada por dia da semana
            select x.mes, 'A trabalho' trabalho,
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from (select distinct datepart(day, data) mes from tt_ligacao) x
                      left outer join (select datepart(day, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='S' and entrante='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(day, data)) tt_ori on (x.mes = tt_ori.mes)
                      left outer join (select datepart(day, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='S' and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(day, data)) tt_rec on (x.mes = tt_rec.mes)
                      left outer join (select datepart(day, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='S' and entrante='S' and recebida='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(day, data)) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            UNION 
            select x.mes, 'Particular' trabalho,
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from (select distinct datepart(day, data) mes from tt_ligacao) x
                      left outer join (select datepart(day, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='N' and entrante='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(day, data)) tt_ori on (x.mes = tt_ori.mes)
                      left outer join (select datepart(day, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='N' and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(day, data)) tt_rec on (x.mes = tt_rec.mes)
                      left outer join (select datepart(day, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho='N' and entrante='S' and recebida='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(day, data)) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            UNION 
            select x.mes, 'Total' trabalho,
                   IsNull(tt_ori.qtd,0) ori_qtd, IsNull(tt_ori.dura,0) ori_dura, 
                   IsNull(tt_rec.qtd,0) rec_qtd, IsNull(tt_rec.dura,0) rec_dura, 
                   IsNull(tt_nat.qtd,0) nat_qtd, IsNull(tt_nat.dura,0) nat_dura, 
                  (IsNull(tt_ori.qtd,0)+IsNull(tt_rec.qtd,0)+IsNull(tt_nat.qtd,0)) qtd_tot, 
                  (IsNull(tt_ori.dura,0)+IsNull(tt_rec.dura,0)+IsNull(tt_nat.dura,0)) dura_tot 
              from (select distinct datepart(day, data) mes from tt_ligacao) x
                      left outer join (select datepart(day, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho is not null and entrante='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(day, data)) tt_ori on (x.mes = tt_ori.mes)
                      left outer join (select datepart(day, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho is not null and entrante='S' and recebida='S'
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(day, data)) tt_rec on (x.mes = tt_rec.mes)
                      left outer join (select datepart(day, data) mes, count(*) qtd,IsNull(sum(duracao),0) dura from tt_ligacao a
                                        where ((@p_tipo=1 and sq_usuario_central = @p_pessoa) or @p_tipo <> 1) and data between @p_inicio and @p_fim+1  and trabalho is not null and entrante='S' and recebida='N' 
                                          and (@p_sq_cc   is null or (@p_sq_cc   is not null and sq_cc   = @p_sq_cc))
                                          and (@p_numero  is null or (@p_numero  is not null and numero like '%'+@p_numero+'%'))
                                          and (@p_contato is null or (@p_contato is not null and siw.acentos(upper(outra_parte_cont),null) like siw.acentos(upper('%'+@p_contato+'%'),null)))
                                       group by datepart(day, data)) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (IsNull(tt_ori.qtd,0) + IsNull(tt_rec.qtd,0) + IsNull(tt_nat.qtd,0)) 
            order by 1, 2
      End
   End
end




GO
setuser
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

