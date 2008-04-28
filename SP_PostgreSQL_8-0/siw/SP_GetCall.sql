CREATE OR REPLACE FUNCTION siw.SP_GetCall
   (p_cliente   numeric,
    p_chave     numeric,
    p_pessoa    numeric,
    p_tipo      numeric,
    p_restricao varchar,
    p_sq_cc     varchar,
    p_contato   varchar,
    p_numero    varchar,
    p_inicio    date,
    p_fim       date,
    p_ativo     varchar)

  RETURNS character varying AS
$BODY$
DECLARE
  
    p_result   refcursor;
begin
   If p_restricao is null Then
      -- Recupera as ligações que o usuário pode ver
      open p_result for 
         select /*+ FIRST_ROWS */ a.sq_ligacao, a.data, a.data as ordem, a.assunto,
                Nvl(a.numero,'---') as numero, a.duracao, a.sq_usuario_central, a.trabalho,
                case when a.trabalho is null then 0 else 1 end as informada, c.codigo as sq_ramal,e.numero as sq_tronco,  
                case when a.trabalho is null then '-' when a.trabalho = 'S' then 'Sim' else 'Não' end  as d_trabalho,  
                Nvl(a.outra_parte_cont,'-') as d_nome,  
                Nvl(g.sigla,'-') as d_cc, nvl(lower(h.localidade),'-') as localidade,  
                case a.entrante when 'S' then case a.recebida when 'N' then 'NAT' else 'REC' end else 'ORI' end as tipo,
                i.nome_resumido as responsavel, i.nome as responsavel_nome,
                to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_ordem
           from siw.tt_ligacao                         a 
                inner      join siw.tt_usuario         b on (a.sq_usuario_central = b.sq_usuario_central) 
                   inner   join siw.co_pessoa          i on (b.usuario            = i.sq_pessoa)
                inner      join siw.tt_ramal           c on (a.sq_ramal           = c.sq_ramal) 
                inner      join siw.tt_tronco          d on (a.sq_tronco          = d.sq_tronco) 
                   inner   join siw.co_pessoa_telefone e on (d.sq_pessoa_telefone = e.sq_pessoa_telefone) 
                left join siw.ct_cc                    g on (a.sq_cc              = g.sq_cc) 
                left join siw.tt_prefixos              h on (a.sq_prefixo         = h.sq_prefixo) 
          where a.cliente          = p_cliente
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
         select /*+ FIRST_ROWS */ a.sq_ligacao, a.data, a.data as ordem,  a.assunto,
                Nvl(a.numero,'---') as numero, a.duracao, a.sq_usuario_central, a.trabalho,  
                case when a.trabalho is null then 0 else 1 end as informada, d.codigo as sq_ramal,f.numero as sq_tronco,  
                case when a.trabalho is null then '-' when a.trabalho = 'S' then 'Sim' else 'Não' end as d_trabalho,  
                Nvl(a.outra_parte_cont,'-') as d_nome,  
                Nvl(g.sigla,'-') as d_cc, nvl(lower(h.localidade),'-') as localidade,  
                case a.entrante when 'S' then case a.recebida when 'N' then 'NAT' else 'REC' end else 'ORI' end as tipo,
                null, null, 
                to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_ordem
           from siw.tt_ligacao                         a 
                inner      join siw.tt_ramal_usuario   c on (a.sq_ramal           = c.sq_ramal) 
                   inner   join siw.tt_usuario         b on (c.sq_usuario_central = b.sq_usuario_central) 
                inner      join siw.tt_ramal           d on (a.sq_ramal           = d.sq_ramal) 
                inner      join siw.tt_tronco          e on (a.sq_tronco          = e.sq_tronco) 
                   inner   join siw.co_pessoa_telefone f on (e.sq_pessoa_telefone = f.sq_pessoa_telefone) 
                left join siw.ct_cc                    g on (a.sq_cc              = g.sq_cc) 
                left join siw.tt_prefixos              h on (a.sq_prefixo         = h.sq_prefixo) 
          where a.cliente            = p_cliente
            and a.sq_usuario_central is null 
            and a.trabalho           is null
            and trunc(a.data)        between c.inicio and Nvl(c.fim,sysdate)
            and ((nvl(p_tipo,1)      = 3) or (nvl(p_tipo,1)      <> 3 and b.usuario          = p_pessoa))
            and (p_sq_cc             is null or (p_sq_cc   is not null and a.sq_cc   = p_sq_cc))
            and (p_contato           is null or (p_contato is not null and acentos(upper(a.outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
            and (p_numero            is null or (p_numero  is not null and a.numero like '%'||p_numero||'%'))
            and (p_inicio            is null or ((p_inicio  is not null and p_fim is not null and trunc(a.data) between p_inicio and p_fim) or
                                                 (p_fim     is null                           and trunc(a.data) >= p_inicio)
                                                )
                );
   Else
      If p_restricao = 'REGISTRO' Then
         -- Recupera os dados de uma ligação
         open p_result for
            select a.sq_ligacao, a.cliente, a.sq_central_fone, a.sq_tronco, a.sq_usuario_central, a.sq_ramal, a.sq_cc, a.sq_prefixo, a.data, a.operadora, 
                   a.valor, a.duracao, a.recebida, a.entrante, a.fax, a.numero, a.inclusao, a.trabalho, a.assunto, a.outra_parte_cont, a.imagem,
                   c.nome_resumido as responsavel, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_data
              from siw.tt_ligacao             a
                   left   join siw.tt_usuario b on (a.sq_usuario_central = b.sq_usuario_central)
                     left join siw.co_pessoa  c on (b.usuario            = c.sq_pessoa)
             where a.sq_ligacao = p_chave;
      Elsif p_restricao = 'DADOS' Then
         -- Recupera todos os dados de uma ligação
         open p_result for
            select a.sq_ligacao, a.data, a.data as ordem, 
                   Nvl(a.numero,'---') as numero, a.duracao, a.valor, 
                   case when a.trabalho is null then 0 else 1 end as informada, d.codigo as sq_ramal,f.numero as sq_tronco, 
                   case a.entrante when 'S' then case a.recebida when 'N' then 'Não atendida' else 'Recebida' end else 'Originada' end as tipo, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_ordem 
              from siw.tt_ligacao         a, 
                   siw.tt_usuario         b,  
                   siw.tt_ramal_usuario   c, 
                   siw.tt_ramal           d,  
                   siw.tt_tronco          e,  
                   siw.co_pessoa_telefone f  
             where a.sq_ramal           = c.sq_ramal 
               and c.sq_usuario_central = b.sq_usuario_central 
               and a.sq_ramal           = d.sq_ramal 
               and a.sq_tronco          = e.sq_tronco 
               and e.sq_pessoa_telefone = f.sq_pessoa_telefone 
               and a.sq_ligacao         = p_chave;
      Elsif p_restricao = 'HERANCA' Then
         -- Recupera os dados da última ligação de um número
         open p_result for
            select sq_cc, assunto, imagem, fax, trabalho, outra_parte_cont 
              from siw.tt_ligacao 
             where cliente    = p_cliente
               and sq_ligacao = (select max(sq_ligacao) as sq_ligacao 
                                   from siw.tt_ligacao a inner join siw.tt_usuario b on (a.sq_usuario_central = b.sq_usuario_central)
                                  where a.trabalho           is not null 
                                    and b.usuario            = p_pessoa
                                    and a.numero             = p_numero
                                );
      Elsif p_restricao = 'LOG' Then
         -- Recupera o histórico de transferências de uma ligação
         open p_result for
            select a.data, a.observacao, 
                   d.nome_resumido as origem, e.nome_resumido as destino, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_ordem 
              from siw.tt_ligacao_log               a
                   inner    join siw.tt_usuario     b on (a.usuario_origem  = b.sq_usuario_central)
                      inner join siw.co_pessoa      d on (b.usuario         = d.sq_pessoa)
                   inner    join siw.tt_usuario     c on (a.usuario_destino = c.sq_usuario_central)
                      inner join siw.co_pessoa      e on (c.usuario         = e.sq_pessoa)
             where a.sq_ligacao      = p_chave;
      Elsif p_restricao = 'PESSOAS' Then
         -- Recupera o resumo de ligações particulares por pessoa
         open p_result for
            select x.sq_usuario_central, x.usuario, y.nome as nm_completo, y.nome_resumido, 'A trabalho' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from tt_usuario x
                   inner join co_pessoa y on (x.usuario = y.sq_pessoa)
                   left  join (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='N' group by sq_usuario_central)                  tt_ori on (x.sq_usuario_central     = tt_ori.sq_usuario_central) 
                   left  join (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec on (x.sq_usuario_central     = tt_rec.sq_usuario_central)
                   left  join (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat on (x.sq_usuario_central     = tt_nat.sq_usuario_central)
             where x.cliente = p_cliente
               and 0         < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            UNION 
            select x.sq_usuario_central, x.usuario, y.nome as nm_completo, y.nome_resumido, 'Particular' trabalho,  
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from tt_usuario x
                   inner join co_pessoa y on (x.usuario = y.sq_pessoa)
                   left  join (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='N' group by sq_usuario_central)                  tt_ori on (x.sq_usuario_central     = tt_ori.sq_usuario_central)
                   left  join (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec on (x.sq_usuario_central     = tt_rec.sq_usuario_central) 
                   left  join (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat on (x.sq_usuario_central     = tt_nat.sq_usuario_central)
             where x.cliente = p_cliente
               and 0         < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            UNION 
            select x.sq_usuario_central, x.usuario, y.nome as nm_completo, y.nome_resumido, 'Total' as trabalho, 
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from tt_usuario x
                   inner join co_pessoa y on (x.usuario = y.sq_pessoa)
                   left  join (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='N' group by sq_usuario_central)                  tt_ori on (x.sq_usuario_central     = tt_ori.sq_usuario_central)
                   left  join (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec on (x.sq_usuario_central     = tt_rec.sq_usuario_central)
                   left  join (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where trabalho = 'N' and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat on (x.sq_usuario_central     = tt_nat.sq_usuario_central)
             where x.cliente = p_cliente
               and 0         < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            order by qtd_tot desc, dura_tot desc, nome_resumido, trabalho;
      Elsif p_restricao = 'GERAL' Then
         -- Recupera o resumo total de ligações da pessoa informada
         open p_result for
            select 'A trabalho' as trabalho, 
                   tt_ori.qtd ori_qtd, tt_ori.dura ori_dura, 
                   tt_rec.qtd rec_qtd, tt_rec.dura rec_dura, 
                   tt_nat.qtd nat_qtd, tt_nat.dura nat_dura, 
                  (tt_ori.qtd+tt_rec.qtd+tt_nat.qtd) qtd_tot, 
                  (tt_ori.dura+tt_rec.dura+tt_nat.dura) dura_tot       
              from (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a 
                     where a.cliente = p_cliente
                       and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_ori, 
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a 
                     where a.cliente = p_cliente
                       and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='S'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_rec,
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where a.cliente = p_cliente
                       and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_nat
            UNION 
            select 'Particular' as trabalho, 
                   tt_ori.qtd ori_qtd, tt_ori.dura ori_dura, 
                   tt_rec.qtd rec_qtd, tt_rec.dura rec_dura, 
                   tt_nat.qtd nat_qtd, tt_nat.dura nat_dura, 
                   (tt_ori.qtd+tt_rec.qtd+tt_nat.qtd) qtd_tot, 
                   (tt_ori.dura+tt_rec.dura+tt_nat.dura) dura_tot 
              from (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where a.cliente = p_cliente
                       and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_ori, 
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where a.cliente = p_cliente
                       and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='S'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_rec, 
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where a.cliente = p_cliente
                       and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_nat 
            UNION 
            select 'Total' as trabalho, 
                   tt_ori.qtd ori_qtd, tt_ori.dura ori_dura, 
                   tt_rec.qtd rec_qtd, tt_rec.dura rec_dura, 
                   tt_nat.qtd nat_qtd, tt_nat.dura nat_dura, 
                  (tt_ori.qtd+tt_rec.qtd+tt_nat.qtd) qtd_tot, 
                  (tt_ori.dura+tt_rec.dura+tt_nat.dura) dura_tot 
              from (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where a.cliente = p_cliente
                       and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_ori, 
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                     where a.cliente = p_cliente
                       and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='S'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_rec, 
                   (select count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a 
                     where a.cliente = p_cliente
                       and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='N'
                       and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                       and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                       and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                   ) tt_nat 
            order by trabalho;
      Elsif p_restricao = 'CTCC' Then
         -- Recupera o resumo por centro de custos das ligações da pessoa informada
         open p_result for
            select x.sq_cc, x.sigla, 'A trabalho' as trabalho, 
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                   (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                   (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from ct_cc x
                   left join (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a 
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='N'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by sq_cc
                             ) tt_ori on (x.sq_cc = tt_ori.sq_cc) 
                   left join (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a 
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by sq_cc
                             ) tt_rec on (x.sq_cc = tt_rec.sq_cc) 
                   left join (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='S' and entrante='S' and recebida='N'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by sq_cc
                             ) tt_nat on (x.sq_cc = tt_nat.sq_cc)
              where x.sq_cc_pai is not null
                and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION 
            select x.sq_cc, x.sigla, 'Particular' as trabalho, 
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                   (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                   (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from ct_cc x
                   left join (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a 
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='N'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by sq_cc
                             ) tt_ori on (x.sq_cc = tt_ori.sq_cc) 
                   left join (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a 
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by sq_cc
                             ) tt_rec on (x.sq_cc = tt_rec.sq_cc) 
                   left join (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho='N' and entrante='S' and recebida='N'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by sq_cc
                             ) tt_nat on (x.sq_cc = tt_nat.sq_cc)
              where x.sq_cc_pai is not null
                and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            UNION 
            select x.sq_cc, x.sigla, 'Total' as trabalho, 
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
                   (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
                   (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot
              from ct_cc x
                   left join (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a 
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='N'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by sq_cc
                             ) tt_ori on (x.sq_cc = tt_ori.sq_cc) 
                   left join (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a 
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by sq_cc
                             ) tt_rec on (x.sq_cc = tt_rec.sq_cc) 
                   left join (select sq_cc, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim and trabalho is not null and entrante='S' and recebida='N'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by sq_cc
                             ) tt_nat on (x.sq_cc = tt_nat.sq_cc)
              where x.sq_cc_pai is not null
                and 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
            order by sigla, trabalho;
      Elsif p_restricao = 'MES' Then
         -- Recupera o resumo mensal das ligações da pessoa informada
         open p_result for
            select x.mes, 'A trabalho' as trabalho,  
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from (select distinct to_char(data,'yyyymm') mes from tt_ligacao) x
                   left join (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'yyyymm')) tt_ori on (x.mes = tt_ori.mes)
                   left join (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'yyyymm')) tt_rec on (x.mes = tt_rec.mes)
                   left join (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'yyyymm')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            UNION 
            select x.mes, 'Particular' as trabalho,  
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from (select distinct to_char(data,'yyyymm') mes from tt_ligacao) x
                   left join (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'yyyymm')) tt_ori on (x.mes = tt_ori.mes)
                   left join (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'yyyymm')) tt_rec on (x.mes = tt_rec.mes)
                   left join (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'yyyymm')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            UNION 
            select x.mes, 'Total' as trabalho,  
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from (select distinct to_char(data,'yyyymm') mes from tt_ligacao) x
                   left join (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and as trabalho is not null and entrante='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'yyyymm')) tt_ori on (x.mes = tt_ori.mes)
                   left join (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and as trabalho is not null and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'yyyymm')) tt_rec on (x.mes = tt_rec.mes)
                   left join (select to_char(data,'yyyymm') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and as trabalho is not null and entrante='S' and recebida='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'yyyymm')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            order by mes, as trabalho;
      Elsif p_restricao = 'DIASEMANA' Then
         -- Recupera o resumo das ligações da pessoa informada por dia da semana
         open p_result for
            select x.mes,case x.mes when '1' then 'DOM' when '2' then 'SEG' when '3' then 'TER' when '4' then 'QUA' when '5' then 'QUI' when '6' then 'SEX' else 'SAB' end dia, 'A trabalho'as trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from (select distinct to_char(data,'d') mes from tt_ligacao) x
                   left join (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'d')) tt_ori on (x.mes = tt_ori.mes)
                   left join (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'d')) tt_rec on (x.mes = tt_rec.mes)
                   left join (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'d')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            UNION 
            select x.mes,case x.mes when '1' then 'DOM' when '2' then 'SEG' when '3' then 'TER' when '4' then 'QUA' when '5' then 'QUI' when '6' then 'SEX' else 'SAB' end dia, 'Particular' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from (select distinct to_char(data,'d') mes from tt_ligacao) x
                   left join (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'d')) tt_ori on (x.mes = tt_ori.mes)
                   left join (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'d')) tt_rec on (x.mes = tt_rec.mes)
                   left join (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'d')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            UNION 
            select x.mes,case x.mes when '1' then 'DOM' when '2' then 'SEG' when '3' then 'TER' when '4' then 'QUA' when '5' then 'QUI' when '6' then 'SEX' else 'SAB' end dia, 'Total' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from (select distinct to_char(data,'d') mes from tt_ligacao) x
                   left join (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'d')) tt_ori on (x.mes = tt_ori.mes)
                   left join (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'d')) tt_rec on (x.mes = tt_rec.mes)
                   left join (select to_char(data,'d') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='S' and recebida='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'d')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            order by mes, trabalho;
      Elsif p_restricao = 'DIAMES' Then
         -- Recupera o resumo das ligações da pessoa informada por dia da semana
         open p_result for
            select x.mes, 'A trabalho' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from (select distinct to_char(data,'dd') mes from tt_ligacao) x
                   left join (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'dd')) tt_ori on (x.mes = tt_ori.mes)
                   left join (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'dd')) tt_rec on (x.mes = tt_rec.mes)
                   left join (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='S' and entrante='S' and recebida='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'dd')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            UNION 
            select x.mes, 'Particular' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from (select distinct to_char(data,'dd') mes from tt_ligacao) x
                   left join (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'dd')) tt_ori on (x.mes = tt_ori.mes)
                   left join (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'dd')) tt_rec on (x.mes = tt_rec.mes)
                   left join (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho='N' and entrante='S' and recebida='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'dd')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            UNION 
            select x.mes, 'Total' trabalho,
                   Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura, 
                   Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura, 
                   Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura, 
                  (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot, 
                  (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot 
              from (select distinct to_char(data,'dd') mes from tt_ligacao) x
                   left join (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'dd')) tt_ori on (x.mes = tt_ori.mes)
                   left join (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='S' and recebida='S'
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'dd')) tt_rec on (x.mes = tt_rec.mes)
                   left join (select to_char(data,'dd') mes, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a
                               where a.cliente = p_cliente
                                 and ((p_tipo=1 and sq_usuario_central = p_pessoa) or p_tipo <> 1) and trunc(data) between p_inicio and p_fim  and trabalho is not null and entrante='S' and recebida='N' 
                                 and (p_sq_cc   is null or (p_sq_cc   is not null and sq_cc   = p_sq_cc))
                                 and (p_numero  is null or (p_numero  is not null and numero like '%'||p_numero||'%'))
                                 and (p_contato is null or (p_contato is not null and acentos(upper(outra_parte_cont)) like acentos(upper('%'||p_contato||'%'))))
                              group by to_char(data,'dd')) tt_nat on (x.mes = tt_nat.mes)
             where 0 < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0)) 
            order by mes, trabalho;
      Elsif p_restricao = 'HINT' Then
         -- Recupera todas as ligações
         open p_result for 
            select /*+ FIRST_ROWS */ a.data, Nvl(a.outra_parte_cont,'-') as d_nome,  
                   case a.entrante when 'S' then case a.recebida when 'N' then 'NAT' else 'REC' end else 'ORI' end as tipo,
                   i.nome_resumido as responsavel, 
                   to_char(a.data, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_ordem
              from siw.tt_ligacao                     a 
                      inner      join siw.tt_usuario  b on (a.sq_usuario_central = b.sq_usuario_central) 
                         inner   join siw.co_pessoa   i on (b.usuario            = i.sq_pessoa)
             where a.cliente = p_cliente
               and a.numero   = p_numero
               and a.trabalho is not null
               and a.data     > sysdate-365
           order by data desc;
      End If;
   End If;
end 


 $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCall
   (p_cliente   numeric,
    p_chave     numeric,
    p_pessoa    numeric,
    p_tipo      numeric,
    p_restricao varchar,
    p_sq_cc     varchar,
    p_contato   varchar,
    p_numero    varchar,
    p_inicio    date,
    p_fim       date,
    p_ativo     varchar) OWNER TO siw;
