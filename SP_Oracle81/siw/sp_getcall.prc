create or replace procedure SP_GetCall
   (p_chave     in number default null,
    p_pessoa    in number,
    p_tipo      in number,
    p_restricao in varchar2 default null,
    p_sq_cc     in number   default null,
    p_contato   in varchar2 default null,
    p_numero    in varchar2 default null,
    p_inicio    in date     default null,
    p_fim       in date     default null,
    p_ativo     in varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as liga��es que o usu�rio pode ver
      open p_result for
         select /*+ FIRST_ROWS */ a.sq_ligacao, a.data, a.data ordem, a.assunto,
                Nvl(a.numero,'---') numero, a.duracao, a.sq_usuario_central, a.trabalho,
                decode(a.trabalho,null,0,1) informada, c.codigo sq_ramal,e.numero sq_tronco,
                decode(a.trabalho,null,'-','S','Sim','N','N�o') d_trabalho,
                Nvl(a.outra_parte_cont,'-') d_nome,
                Nvl(g.sigla,'-') d_cc, nvl(lower(h.localidade),'-') localidade,
                decode(a.entrante,'S', decode(a.recebida,'N','NAT','REC'),'ORI') tipo,
                i.nome_resumido responsavel, 
                to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_ordem
           from tt_ligacao         a,
                   tt_usuario         b,
                      co_pessoa          i,
                   tt_ramal           c,
                   tt_tronco          d,
                      co_pessoa_telefone e,
                   ct_cc              g,
                   tt_prefixos        h
          where (a.sq_usuario_central = b.sq_usuario_central)
            and (b.usuario            = i.sq_pessoa)
            and (a.sq_ramal           = c.sq_ramal)
            and (a.sq_tronco          = d.sq_tronco)
            and (d.sq_pessoa_telefone = e.sq_pessoa_telefone)
            and (a.sq_cc           = g.sq_cc (+))
            and (a.sq_prefixo      = h.sq_prefixo (+))
            and ((p_tipo           = 1 and b.usuario = p_pessoa) or p_tipo         <> 1)
            and (p_sq_cc           is null or (p_sq_cc   is not null and a.sq_cc   = p_sq_cc))
            and (p_contato         is null or (p_contato is not null and acentos(upper(a.outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
            and (p_numero          is null or (p_numero  is not null and a.numero like '%'||p_numero||'%'))
            and (p_inicio          is null or ((p_inicio  is not null and p_fim is not null and trunc(a.data) between p_inicio and p_fim) or
                                               (p_fim     is null                           and trunc(a.data) >= p_inicio)
                                              )
                )
            and ((p_ativo          is null and a.trabalho is null) or
                 (p_ativo          = 'A'   and a.trabalho is not null) or
                 (p_ativo          in ('S','N') and a.trabalho = p_ativo)
                )
         UNION
         select /*+ FIRST_ROWS */ a.sq_ligacao, a.data, a.data ordem,  a.assunto,
                Nvl(a.numero,'---') numero, a.duracao, a.sq_usuario_central, a.trabalho,
                decode(a.trabalho,null,0,1) informada, d.codigo sq_ramal,f.numero sq_tronco,
                decode(a.trabalho,null,'-','S','Sim','N�o') d_trabalho,
                Nvl(a.outra_parte_cont,'-') d_nome,
                Nvl(g.sigla,'-') d_cc, nvl(lower(h.localidade),'-') localidade,
                decode(a.entrante,'S',decode(a.recebida,'N','NAT','REC'),'ORI') tipo,
                null, 
                to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_ordem
           from tt_ligacao         a,
                   tt_ramal_usuario   c,
                      tt_usuario         b,
                   tt_ramal           d,
                   tt_tronco          e,
                      co_pessoa_telefone f,
                   ct_cc              g,
                   tt_prefixos        h
          where (a.sq_ramal           = c.sq_ramal)
            and (c.sq_usuario_central = b.sq_usuario_central)
            and (a.sq_ramal           = d.sq_ramal)
            and (a.sq_tronco          = e.sq_tronco)
            and (e.sq_pessoa_telefone = f.sq_pessoa_telefone)
            and (a.sq_cc              = g.sq_cc (+))
            and (a.sq_prefixo         = h.sq_prefixo (+))
            and a.sq_usuario_central is null
            and trunc(a.data)        between c.inicio and Nvl(c.fim,sysdate)
            and b.usuario            = p_pessoa
            and (p_sq_cc             is null or (p_sq_cc   is not null and a.sq_cc   = p_sq_cc))
            and (p_contato           is null or (p_contato is not null and acentos(upper(a.outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
            and (p_numero            is null or (p_numero  is not null and a.numero like '%'||p_numero||'%'))
            and (p_inicio            is null or ((p_inicio  is not null and p_fim is not null and trunc(a.data) between p_inicio and p_fim) or
                                                 (p_fim     is null                           and trunc(a.data) >= p_inicio)
                                                )
                )
            and ((p_ativo            is null and a.trabalho is null) or
                 (p_ativo            = 'A'   and a.trabalho is not null) or
                 (p_ativo            in ('S','N') and a.trabalho = p_ativo)
                );
   Else
      If p_restricao = 'REGISTRO' Then
         -- Recupera os dados de uma liga��o
         open p_result for
            select a.sq_ligacao, a.cliente, a.sq_central_fone, a.sq_tronco, a.sq_usuario_central, a.sq_ramal, a.sq_cc, a.sq_prefixo, a.data, a.operadora, 
                   a.valor, a.duracao, a.recebida, a.entrante, a.fax, a.numero, a.inclusao, a.trabalho, a.assunto, a.outra_parte_cont, a.imagem,
                   c.nome_resumido responsavel, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data
              from tt_ligacao a,
                     tt_usuario b,
                       co_pessoa  c
             where (a.sq_usuario_central = b.sq_usuario_central (+))
               and (b.usuario            = c.sq_pessoa (+))
               and a.sq_ligacao = p_chave;
      Elsif p_restricao = 'DADOS' Then
         -- Recupera todos os dados de uma liga��o
         open p_result for
            select a.sq_ligacao, a.data, a.data ordem,
                   Nvl(a.numero,'---') numero, a.duracao, a.valor,
                   decode(a.trabalho,null,0,1) informada, d.codigo sq_ramal,f.numero sq_tronco,
                   decode(a.entrante,'S',decode(a.recebida,'N','N�o atendida','Recebida'),'Originada') tipo, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_ordem 
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
               and a.sq_ligacao         = p_chave;
      Elsif p_restricao = 'HERANCA' Then
         -- Recupera os dados da �ltima liga��o de um n�mero
         open p_result for
            select sq_cc, assunto, imagem, fax, trabalho, outra_parte_cont
              from tt_ligacao
             where sq_ligacao = (select max(sq_ligacao) sq_ligacao
                                   from tt_ligacao a,
                                        tt_usuario b
                                  where (a.sq_usuario_central = b.sq_usuario_central)
                                    and a.trabalho           is not null
                                    and b.usuario            = p_pessoa
                                    and a.numero             = p_numero
                                );
      Elsif p_restricao = 'LOG' Then
         -- Recupera o hist�rico de transfer�ncias de uma liga��o
         open p_result for
            select a.data, a.observacao,
                   d.nome_resumido origem, e.nome_resumido destino, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') phpdt_ordem 
              from tt_ligacao_log a,
                      tt_usuario     b,
                         co_pessoa      d,
                      tt_usuario     c,
                         co_pessoa      e
             where (a.usuario_origem  = b.sq_usuario_central)
               and (b.usuario         = d.sq_pessoa)
               and (a.usuario_destino = c.sq_usuario_central)
               and (c.usuario         = e.sq_pessoa)
               and a.sq_ligacao      = p_chave;
      Elsif p_restricao = 'PESSOAS' Then
         -- Recupera o resumo de liga��es particulares por pessoa
         open p_result for
            select x.sq_usuario_central, x.usuario, y.nome nm_completo, y.nome_resumido, 'A trabalho' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from tt_usuario x,
                   co_pessoa  y,
                   (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='N' group by sq_usuario_central) tt_ori,
                   (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec,
                   (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat
             where (x.usuario = y.sq_pessoa)
               and (x.sq_usuario_central     = tt_ori.sq_usuario_central (+))
               and (x.sq_usuario_central     = tt_rec.sq_usuario_central (+))
               and (x.sq_usuario_central     = tt_nat.sq_usuario_central (+))
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION
            select x.sq_usuario_central, x.usuario, y.nome nm_completo, y.nome_resumido, 'Particular' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from tt_usuario x,
                   co_pessoa y,
                   (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='N' group by sq_usuario_central)                  tt_ori,
                   (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec,
                   (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat
             where (x.usuario = y.sq_pessoa)
               and (x.sq_usuario_central     = tt_ori.sq_usuario_central (+))
               and (x.sq_usuario_central     = tt_rec.sq_usuario_central (+))
               and (x.sq_usuario_central     = tt_nat.sq_usuario_central (+))
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION
            select x.sq_usuario_central, x.usuario, y.nome nm_completo, y.nome_resumido, 'Total' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from tt_usuario x,
                   co_pessoa  y,
                   (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='N' group by sq_usuario_central)                  tt_ori,
                   (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec,
                   (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat
             where (x.usuario = y.sq_pessoa)
               and (x.sq_usuario_central     = tt_ori.sq_usuario_central)
               and (x.sq_usuario_central     = tt_rec.sq_usuario_central)
               and (x.sq_usuario_central     = tt_nat.sq_usuario_central)
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            order by qtd_tot desc, dura_tot desc, nome_resumido, trabalho;
      Elsif p_restricao = 'GERAL' Then
         -- Recupera o resumo total de liga��es da pessoa informada
         open p_result for
            select 'A trabalho' trabalho,
                   tt_ori.qtd ori_qtd, tt_ori.dura ori_dura,
                   tt_rec.qtd rec_qtd, tt_rec.dura rec_dura,
                   tt_nat.qtd nat_qtd, tt_nat.dura nat_dura,
                  (tt_ori.qtd+tt_rec.qtd+tt_nat.qtd) qtd_tot,
                  (tt_ori.dura+tt_rec.dura+tt_nat.dura) dura_tot
              from (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_ori,
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='S'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_rec,
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_nat
            UNION
            select 'Particular' trabalho,
                   tt_ori.qtd ori_qtd, tt_ori.dura ori_dura,
                   tt_rec.qtd rec_qtd, tt_rec.dura rec_dura,
                   tt_nat.qtd nat_qtd, tt_nat.dura nat_dura,
                   (tt_ori.qtd+tt_rec.qtd+tt_nat.qtd) qtd_tot,
                   (tt_ori.dura+tt_rec.dura+tt_nat.dura) dura_tot
              from (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_ori,
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='S'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_rec,
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_nat
            UNION
            select 'Total' trabalho,
                   tt_ori.qtd ori_qtd, tt_ori.dura ori_dura,
                   tt_rec.qtd rec_qtd, tt_rec.dura rec_dura,
                   tt_nat.qtd nat_qtd, tt_nat.dura nat_dura,
                  (tt_ori.qtd+tt_rec.qtd+tt_nat.qtd) qtd_tot,
                  (tt_ori.dura+tt_rec.dura+tt_nat.dura) dura_tot
              from (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_ori,
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='S'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_rec,
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_nat
            order by trabalho;
      Elsif p_restricao = 'CTCC' Then
         -- Recupera o resumo por centro de custos das liga��es da pessoa informada
         open p_result for
            select x.sq_cc, x.sigla, 'A trabalho' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                   (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                   (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from ct_cc x,
                   (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by sq_cc
                    ) tt_ori,
                   (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='S'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by sq_cc
                   ) tt_rec,
                   (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by sq_cc
                   ) tt_nat
              where (x.sq_cc = tt_ori.sq_cc (+))
                and (x.sq_cc = tt_rec.sq_cc (+))
                and (x.sq_cc = tt_nat.sq_cc (+))
                and x.sq_cc_pai is not null
                and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION
            select x.sq_cc, x.sigla, 'Particular' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                   (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                   (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from ct_cc x,
                   (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by sq_cc
                    ) tt_ori,
                   (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='S'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by sq_cc
                    ) tt_rec,
                   (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by sq_cc
                   ) tt_nat
              where (x.sq_cc = tt_ori.sq_cc (+))
                and (x.sq_cc = tt_rec.sq_cc (+))
                and (x.sq_cc = tt_nat.sq_cc (+))
                and x.sq_cc_pai is not null
                and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION
            select x.sq_cc, x.sigla, 'Total' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                   (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                   (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from ct_cc x,
                   (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by sq_cc
                   ) tt_ori,
                   (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='S'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by sq_cc
                   ) tt_rec,
                   (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by sq_cc
                   ) tt_nat
              where (x.sq_cc = tt_ori.sq_cc (+))
                and (x.sq_cc = tt_rec.sq_cc (+))
                and (x.sq_cc = tt_nat.sq_cc (+))
                and x.sq_cc_pai is not null
                and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            order by sigla, trabalho;
      Elsif p_restricao = 'MES' Then
         -- Recupera o resumo mensal das liga��es da pessoa informada
         open p_result for
            select x.mes, 'A trabalho' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from (select distinct to_char(data,'yyyymm') mes from tt_ligacao) x,
                   (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'yyyymm')
                   ) tt_ori,
                   (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='S'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'yyyymm')
                   ) tt_rec,
                   (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'yyyymm')
                   ) tt_nat
             where (x.mes = tt_ori.mes (+))
               and (x.mes = tt_rec.mes (+))
               and (x.mes = tt_nat.mes (+))
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION
            select x.mes, 'Particular' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from (select distinct to_char(data,'yyyymm') mes from tt_ligacao) x,
                   (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'yyyymm')) tt_ori, 
                   (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='S'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'yyyymm')) tt_rec, 
                   (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'yyyymm')) tt_nat 
             where (x.mes = tt_ori.mes (+))
               and (x.mes = tt_rec.mes (+))
               and (x.mes = tt_nat.mes (+))
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION
            select x.mes, 'Total' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from (select distinct to_char(data,'yyyymm') mes from tt_ligacao) x,
                   (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'yyyymm')) tt_ori,
                   (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='S' and recebida='S'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'yyyymm')) tt_rec,
                   (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='S' and recebida='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'yyyymm')) tt_nat
             where (x.mes = tt_ori.mes (+))
               and (x.mes = tt_rec.mes (+))
               and (x.mes = tt_nat.mes (+))
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            order by 1,2;
      Elsif p_restricao = 'DIASEMANA' Then
         -- Recupera o resumo das liga��es da pessoa informada por dia da semana
         open p_result for
            select x.mes,decode(x.mes,'1','DOM','2','SEG','3','TER','4','QUA','5','QUI','6','SEX','SAB') dia, 'A trabalho' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from (select distinct to_char(data,'d') mes from tt_ligacao) x,
                   (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'d')) tt_ori,
                   (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='S'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'d')) tt_rec,
                   (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'d')) tt_nat
             where (x.mes = tt_ori.mes (+))
               and (x.mes = tt_rec.mes (+))
               and (x.mes = tt_nat.mes (+))
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION
            select x.mes,decode(x.mes,'1','DOM','2','SEG','3','TER','4','QUA','5','QUI','6','SEX','SAB') dia, 'Particular' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from (select distinct to_char(data,'d') mes from tt_ligacao) x,
                   (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'d')) tt_ori,
                   (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='S'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'d')) tt_rec,
                   (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'d')) tt_nat 
             where (x.mes = tt_ori.mes (+))
               and (x.mes = tt_rec.mes (+))
               and (x.mes = tt_nat.mes (+))
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION
            select x.mes,decode(x.mes,'1','DOM','2','SEG','3','TER','4','QUA','5','QUI','6','SEX','SAB') dia, 'Total' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from (select distinct to_char(data,'d') mes from tt_ligacao) x,
                   (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'d')) tt_ori,
                   (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='S' and recebida='S'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'d')) tt_rec,
                   (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='S' and recebida='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'d')) tt_nat
             where (x.mes = tt_ori.mes (+))
               and (x.mes = tt_rec.mes (+))
               and (x.mes = tt_nat.mes (+))
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            order by 1,3;
      Elsif p_restricao = 'DIAMES' Then
         -- Recupera o resumo das liga��es da pessoa informada por dia da semana
         open p_result for
            select x.mes, 'A trabalho' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from (select distinct to_char(data,'dd') mes from tt_ligacao) x,
                   (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'dd')) tt_ori,
                   (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='S'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'dd')) tt_rec,
                   (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'dd')) tt_nat
             where (x.mes = tt_ori.mes (+))
               and (x.mes = tt_rec.mes (+))
               and (x.mes = tt_nat.mes (+))
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION
            select x.mes, 'Particular' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from (select distinct to_char(data,'dd') mes from tt_ligacao) x,
                   (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'dd')) tt_ori,
                   (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='S'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'dd')) tt_rec,
                   (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'dd')) tt_nat
             where (x.mes = tt_ori.mes (+))
               and (x.mes = tt_rec.mes (+))
               and (x.mes = tt_nat.mes (+))
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION
            select x.mes, 'Total' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from (select distinct to_char(data,'dd') mes from tt_ligacao) x,
                   (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'dd')) tt_ori,
                   (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='S' and recebida='S'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'dd')) tt_rec,
                   (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='S' and recebida='N'
                                      and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                      and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                      and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                    group by to_char(data,'dd')) tt_nat
             where (x.mes = tt_ori.mes (+))
               and (x.mes = tt_rec.mes (+))
               and (x.mes = tt_nat.mes (+))
               and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            order by 1,2;
      Elsif p_restricao = 'HINT' Then
         -- Recupera todas as liga��es
         open p_result for
            select /*+ FIRST_ROWS */ a.sq_ligacao, a.data, a.data ordem, a.assunto,
                   Nvl(a.numero,'---') numero, a.duracao, a.sq_usuario_central, a.trabalho,
                   decode(a.trabalho,null,0,1) informada, c.codigo sq_ramal,e.numero sq_tronco,
                   decode(a.trabalho,null,'-','S','Sim','N�o') d_trabalho,
                   Nvl(a.outra_parte_cont,'-') d_nome,
                   Nvl(g.sigla,'-') d_cc, nvl(lower(h.localidade),'-') localidade,
                   decode(a.entrante,'S',decode(a.recebida,'N','NAT','REC'),'ORI') tipo,
                   i.nome_resumido responsavel
              from tt_ligacao         a,
                   tt_usuario         b,
                   co_pessoa          i,
                   tt_ramal           c,
                   tt_tronco          d,
                   co_pessoa_telefone e,
                   ct_cc              g,
                   tt_prefixos        h
             where (a.sq_usuario_central = b.sq_usuario_central)
               and (b.usuario            = i.sq_pessoa)
               and (a.sq_ramal           = c.sq_ramal)
               and (a.sq_tronco          = d.sq_tronco)
               and (d.sq_pessoa_telefone = e.sq_pessoa_telefone)
               and (a.sq_cc              = g.sq_cc (+))
               and (a.sq_prefixo         = h.sq_prefixo (+))
               and (p_sq_cc           is null or (p_sq_cc   is not null and a.sq_cc   = p_sq_cc))
               and (p_contato         is null or (p_contato is not null and acentos(upper(a.outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
               and (p_numero          is null or (p_numero  is not null and a.numero like '%'||p_numero||'%'))
               and (p_inicio          is null or ((p_inicio  is not null and p_fim is not null and trunc(a.data) between p_inicio and p_fim) or
                                                  (p_fim     is null                           and trunc(a.data) >= p_inicio)
                                                 )
                   )
               and ((p_ativo          is null and a.trabalho is null) or
                    (p_ativo          = 'A'   and a.trabalho is not null) or
                    (p_ativo          in ('S','N') and a.trabalho = p_ativo)
                   )
            UNION
            select /*+ FIRST_ROWS */ a.sq_ligacao, a.data, a.data ordem,  a.assunto,
                   Nvl(a.numero,'---') numero, a.duracao, a.sq_usuario_central, a.trabalho,
                   decode(a.trabalho,null,0,1) informada, d.codigo sq_ramal,f.numero sq_tronco,
                   decode(a.trabalho,null,'-','S','Sim','N�o') d_trabalho,
                   Nvl(a.outra_parte_cont,'-') d_nome,
                   Nvl(g.sigla,'-') d_cc, nvl(lower(h.localidade),'-') localidade,
                   decode(a.entrante,'S',decode(a.recebida,'N','NAT','REC'),'ORI') tipo,
                   null
              from tt_ligacao         a,
                   tt_ramal_usuario   c,
                   tt_usuario         b,
                   tt_ramal           d,
                   tt_tronco          e,
                   co_pessoa_telefone f,
                   ct_cc              g,
                   tt_prefixos        h
             where (a.sq_ramal           = c.sq_ramal)
               and (c.sq_usuario_central = b.sq_usuario_central)
               and (a.sq_ramal           = d.sq_ramal)
               and (a.sq_tronco          = e.sq_tronco)
               and (e.sq_pessoa_telefone = f.sq_pessoa_telefone)
               and (a.sq_cc              = g.sq_cc (+))
               and (a.sq_prefixo         = h.sq_prefixo (+))
               and a.sq_usuario_central is null
               and trunc(a.data)        between c.inicio and Nvl(c.fim,sysdate)
               and (p_sq_cc             is null or (p_sq_cc   is not null and a.sq_cc   = p_sq_cc))
               and (p_contato           is null or (p_contato is not null and acentos(upper(a.outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
               and (p_numero            is null or (p_numero  is not null and a.numero like '%'||p_numero||'%'))
               and (p_inicio            is null or ((p_inicio  is not null and p_fim is not null and trunc(a.data) between p_inicio and p_fim) or
                                                    (p_fim     is null                           and trunc(a.data) >= p_inicio)
                                                   )
                   )
               and ((p_ativo            is null and a.trabalho is null) or
                    (p_ativo            = 'A'   and a.trabalho is not null) or
                    (p_ativo            in ('S','N') and a.trabalho = p_ativo)
                   );
      End If;
   End If;
end SP_GetCall;
/
