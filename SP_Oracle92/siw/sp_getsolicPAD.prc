create or replace procedure SP_GetSolicPAD
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
   
   If substr(p_restricao,1,3) = 'PAD' or substr(p_restricao,1,4) = 'GRPA' Then
      -- Recupera os programas que o usuário pode ver
      open p_result for 
         select /*+ ordered*/ a.sq_menu,            a.sq_modulo,                   a.nome,
                a.p1,                 a.p2,                          a.p3,
                a.p4,                 a.sigla,                       a.acesso_geral,
                a.consulta_geral,     a.sq_unid_executora,           a.envia_email,
                a.vinculacao,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,
                a2.sq_tipo_unidade as tp_exec,                       a2.nome as nm_unidade_exec, 
                a2.informal as informal_exec,                        a2.vinculada as vinc_exec,
                a2.adm_central as adm_exec,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      b.sq_plano,                    b.protocolo_siw,
                case when b.sq_solic_pai is null 
                     then '???'
                     else dados_solic(b.sq_solic_pai) 
                end as dados_pai,
                case when b.protocolo_siw is not null then dados_solic(b.protocolo_siw) end as dados_vinc,
                b1.nome as nm_tramite,   b1.ordem as or_tramite,
                b1.sigla as sg_tramite,  b1.ativo,                   b1.envia_mail,
                b3.nome as nm_unid_origem, b3.sigla as sg_unid_origem,
                b7.sq_siw_solicitacao as sq_emprestimo, b7.fim as devolucao_prevista,
                b8.sq_siw_solicitacao as sq_eliminacao, b8.fim as dt_eliminacao, b8.sigla as sg_tramite_eliminacao,
                coalesce(ba.qtd,0) as qtd_juntado,
                coalesce(bb.qtd,0) as qtd_vinculado,
                d.numero_original,    d.data_recebimento,            d.numero_documento,
                d.ano,                d.prefixo,                     d.digito,
                d.sq_especie_documento, d.sq_natureza_documento,     d.unidade_autuacao,
                d.interno,            d.data_autuacao,               d.pessoa_origem,
                d.processo,           d.circular,                    d.copias,
                d.volumes,            d.unidade_int_posse,           d.pasta,
                d.tipo_juntada,       d.data_central,                d.pessoa_ext_posse,
                case d.interno when 'S' then e.sigla else d2.nome_resumido end as nm_origem_doc,
                case d.tipo_juntada when 'A' then 'Anexado' when 'P' then 'Apensado' end as nm_tipo_juntada,
                to_char(d.data_juntada, 'DD/MM/YYYY, HH24:MI:SS') as phpdt_juntada,
                to_char(d.data_desapensacao,'DD/MM/YYYY, HH24:MI:SS') as phpdt_desapensacao,
                to_char(d.numero_documento)||'/'||substr(to_char(d.ano),3) as protocolo,
                to_char(d.prefixo)||'.'||substr(1000000+to_char(d.numero_documento),2,6)||'/'||to_char(d.ano)||'-'||substr(100+to_char(d.digito),2,2) as protocolo_completo,
                case d.processo when 'S' then 'Proc' else 'Doc' end as nm_tipo_protocolo,
                case when d.pessoa_origem is null then b3.sq_unidade else d2.sq_pessoa end as sq_origem,
                case when d.pessoa_origem is null then b3.nome else d2.nome end as nm_origem,
                case when d.pessoa_origem is null then b3.sigla else d2.nome_resumido end as nm_origem_resumido,
                b9.prazo_guarda,
                coalesce(d1.nome,'Irrestrito') as nm_natureza,       d1.sigla as sg_natureza,
                d1.descricao as ds_natureza,                         d1.ativo as st_natureza,
                d2.nome_resumido as nm_res_pessoa_origem,            d2.nome as nm_pessoa_origem,
                d3.sq_tipo_pessoa,
                d4.sq_assunto,
                d5.codigo as cd_assunto, d5.provisorio,              d5.descricao as ds_assunto,
                d51.sigla as sg_final,   d51.descricao as nm_final,
                d7.nome as nm_especie,   d7.sigla as sg_especie,     d7.ativo as st_especie,
                d8.numero as nr_caixa, d9.sigla as sg_unid_caixa,
                e.sq_unidade as sq_unidade_resp,
                e.sq_tipo_unidade,    e.nome as nm_unidade_resp,     e.informal as informal_resp,
                e.vinculada as vinc_resp,e.adm_central as adm_resp,  e.sigla as sg_unidade_resp,
                k.sq_tipo_despacho, k.nm_tipo_despacho, k.envio, k.recebimento,
                to_char(k.envio,'dd/mm/yyyy, hh24:mi:ss') as phpdt_envio, to_char(k.recebimento,'dd/mm/yyyy, hh24:mi:ss') as phpdt_recebimento,
                cast(b.fim as date)-cast(k.dias_aviso as integer) as aviso,
                --o.nome_resumido as nm_solic, o.nome_resumido||' ('||o.sigla||')' as nm_resp,
                q.nome as nm_unidade_posse,                          q.sigla as sg_unidade_posse,
                r.nome as nm_pessoa_posse,                           r.nome_resumido as nm_res_pessoa_posse,
                case when r.sq_pessoa is null then r.nome else q.nome end as nm_posse
           from siw_menu                                           a 
                   inner             join eo_unidade               a2 on (a.sq_unid_executora        = a2.sq_unidade)
                   inner             join siw_modulo               a1 on (a.sq_modulo                = a1.sq_modulo)
                   inner             join siw_solicitacao          b  on (a.sq_menu                  = b.sq_menu and
                                                                          ('PADMTENT'               <> substr(p_restricao,1,8) or
                                                                           ('PADMTENT'               = substr(p_restricao,1,8) and 
                                                                            0                        < (select count(*) 
                                                                                                          from siw_solicitacao              w
                                                                                                               inner join siw_tramite       x on (w.sq_siw_tramite     = x.sq_siw_tramite and x.sigla <> 'CA')
                                                                                                               inner join fn_lancamento_doc y on (w.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                                                                         where w.protocolo_siw = b.sq_siw_solicitacao
                                                                                                       )
                                                                           )
                                                                          )
                                                                         )
                      inner          join siw_tramite              b1 on (b.sq_siw_tramite           = b1.sq_siw_tramite and
                                                                          (p_fase                    is null or (p_fase is not null and InStr(x_fase,''''||b.sq_siw_tramite||'''') > 0)) and 
                                                                          (p_tipo                    <> 1 or (p_tipo    = 1         and b1.sigla = 'CI')) and	
                                                                          (p_tipo                    <> 9 or (p_tipo    = 9         and b1.sigla <> 'CA')) -- Linha que adicionei (César) em 18/08/2011
                                                                         )
                      inner          join eo_unidade               b3 on (b.sq_unidade               = b3.sq_unidade)
                      inner          join pa_documento             d  on (b.sq_siw_solicitacao       = d.sq_siw_solicitacao and 
                                                                          (p_pais   is null or (p_pais   is not null and a.sq_pessoa = d.cliente and d.prefixo          = p_pais)) and 
                                                                          (p_regiao is null or (p_regiao is not null and a.sq_pessoa = d.cliente and d.numero_documento = p_regiao)) and
                                                                          (p_cidade is null or (p_cidade is not null and a.sq_pessoa = d.cliente and d.ano              = p_cidade)) and 
                                                                          (p_uf     is null or (p_uf     is not null and a.sq_pessoa = d.cliente and d.processo         = p_uf))
                                                                         )
                        inner        join pa_documento_assunto     d4 on (d.sq_siw_solicitacao       = d4.sq_siw_solicitacao and
                                                                          d4.principal               = 'S'
                                                                         )
                          inner      join pa_assunto               d5 on (d4.sq_assunto              = d5.sq_assunto)
                            inner    join pa_tipo_guarda          d51 on (d5.destinacao_final        = d51.sq_tipo_guarda)
                            inner    join pa_tipo_guarda          d52 on (d5.fase_intermed_guarda    = d52.sq_tipo_guarda)
                        inner        join pa_especie_documento     d7 on (d.sq_especie_documento     = d7.sq_especie_documento)
                        inner        join eo_unidade               e  on (d.unidade_autuacao         = e.sq_unidade)
                        left         join pa_natureza_documento    d1 on (d.sq_natureza_documento    = d1.sq_natureza_documento)
                        left         join co_pessoa                d2 on (d.pessoa_origem            = d2.sq_pessoa)
                          left       join co_tipo_pessoa           d3 on (d2.sq_tipo_pessoa          = d3.sq_tipo_pessoa)
                        left         join pa_caixa                 d8 on (d.sq_caixa                 = d8.sq_caixa)
                          left       join eo_unidade               d9 on (d8.sq_unidade              = d9.sq_unidade)
                      left           join pa_documento             da on (b.protocolo_siw            = da.sq_siw_solicitacao)
                        left         join eo_unidade               q  on (d.unidade_int_posse        = q.sq_unidade)
                        left         join co_pessoa                r  on (d.pessoa_ext_posse         = r.sq_pessoa)
                   left              join (select s.sq_siw_solicitacao, max(r.sq_documento_log) as chave 
                                             from siw_solicitacao             s
                                                  inner join pa_documento_log r on (s.sq_siw_solicitacao = r.sq_siw_solicitacao)
                                            where s.sq_menu = p_menu
                                           group by s.sq_siw_solicitacao
                                          )                        k1 on (b.sq_siw_solicitacao       = k1.sq_siw_solicitacao)
                     left            join (select w.sq_documento_log, w.envio, w.recebimento, w.dias_aviso, 
                                                  y.sq_tipo_despacho, y.nome as nm_tipo_despacho
                                             from pa_documento_log            w
                                                  inner join pa_tipo_despacho y on (w.sq_tipo_despacho   = y.sq_tipo_despacho)
                                                  inner join siw_solicitacao  z on (w.sq_siw_solicitacao = z.sq_siw_solicitacao)
                                            where z.sq_menu = p_menu
                                          )                        k  on (k1.chave                   = k.sq_documento_log)
                      left           join (select y.protocolo, y.sq_siw_solicitacao, x.fim
                                             from siw_solicitacao               x
                                                  inner join pa_emprestimo_item y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                  inner join siw_tramite        z on (x.sq_siw_tramite     = z.sq_siw_tramite and z.sigla <> 'CA')
                                            where y.devolucao is null
                                          )                        b7 on (b.sq_siw_solicitacao       = b7.protocolo)
                      left           join (select y.protocolo, y.sq_siw_solicitacao, x.fim, z.sigla, x.conclusao
                                             from siw_solicitacao          x
                                                  inner join pa_eliminacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                  inner join siw_tramite   z on (x.sq_siw_tramite     = z.sq_siw_tramite and z.sigla <> 'CA')
                                          )                        b8 on (b.sq_siw_solicitacao       = b8.protocolo)
                      left           join (select x.sq_siw_solicitacao, retornaLimiteProtocolo(x.sq_siw_solicitacao) as prazo_guarda
                                             from siw_solicitacao         x
                                                  inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and p_tipo  = 8)
                                                  inner join siw_tramite  z on (x.sq_siw_tramite     = z.sq_siw_tramite and z.ativo = 'N' and z.sigla <> 'CA')
                                            where x.sq_menu = p_menu
                                           group by x.sq_siw_solicitacao
                                          )                        b9 on (b.sq_siw_solicitacao       = b9.sq_siw_solicitacao)
                      left           join (select sq_solic_pai, count(*) as qtd
                                             from siw_solicitacao
                                            where sq_menu = p_menu
                                              and sq_solic_pai is not null
                                           group by sq_solic_pai
                                          )                        ba on (b.sq_siw_solicitacao       = ba.sq_solic_pai)
                      left           join (select x.protocolo_siw, count(*) as qtd
                                             from siw_solicitacao         x
                                                  inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                                  inner join siw_tramite  z on (x.sq_siw_tramite     = z.sq_siw_tramite and z.sigla <> 'CA')
                                            where x.sq_menu       = p_menu
                                              and x.protocolo_siw is not null
                                           group by protocolo_siw
                                          )                        bb on (b.sq_siw_solicitacao       = bb.protocolo_siw)
          where a.sq_menu        = p_menu
            and ((p_tipo         = 1     and b1.sigla = 'CI' and b.cadastrador     = p_pessoa) or
                 (p_tipo         = 2     and b1.ativo = 'S' and b1.sigla <> 'CI') or
                 --(p_tipo         = 3     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         = 4     and b1.sigla <> 'CA') or
                 --(p_tipo         = 4     and InStr(l_resp_unid,''''||b.sq_unidade||'''') > 0) or
                 (p_tipo         in (3,5,9)) or
                 (p_tipo         = 6     and b1.ativo          = 'S' and b1.sigla <> 'CI') or
                 (p_tipo         = 7     and b1.sigla          = 'AT' and b.sq_solic_pai is null and d.data_central is not null and b7.protocolo is null and b8.protocolo is null) or -- Empréstimo
                 (p_tipo         = 8     and b.sq_solic_pai is null and b1.sigla = 'AT' and d.data_central is not null 
                                         and b7.protocolo is null and b8.protocolo is null and d51.Sigla='ELIM'
                                         and to_char(sysdate,'yyyymmyy') > substr(b9.prazo_guarda,7,4)||substr(b9.prazo_guarda,4,2)||substr(b9.prazo_guarda,1,2)
                 ) -- Eliminação
                )
            and ((p_restricao <> 'GRPAPROP'    and p_restricao <> 'GRPAPRIO' and p_restricao <> 'GRPARESPATU' and p_restricao <> 'GRPACC' and p_restricao <> 'GRPAVINC') or 
                 ((p_restricao = 'GRPACC'      and d5.codigo           is not null)   or 
                  (p_restricao = 'GRPAPRIO'    and k.sq_tipo_despacho is not null)    or 
                  (p_restricao = 'GRPAPROP'    and d.pessoa_origem     is not null)   or 
                  (p_restricao = 'GRPARESPATU' and b.executor          is not null)   or
                  (p_restricao = 'GRPAVINC'    and b.sq_solic_pai      is not null)
                 )
                )
            and (p_chave          is null or (p_chave       is not null and d.sq_caixa                       = p_chave))
            and (p_atividade      is null or (p_atividade   is not null and d8.sq_unidade                    = p_atividade))
            and (p_uorg_resp      is null or (p_uorg_resp   is not null and d.unidade_int_posse              = p_uorg_resp))
            and (p_sqcc           is null or (p_sqcc        is not null and b.sq_cc                          = p_sqcc))
            and (p_projeto        is null or (p_projeto     is not null and ((p_tipo = 9 and b.protocolo_siw = p_projeto) or
                                                                             (p_tipo<> 9 and b.sq_solic_pai  = p_projeto)
                                                                            )
                                             )
                )
            and (p_prazo          is null or (p_prazo       is not null and b.conclusao          is null and cast(cast(b.fim as date)-cast(sysdate as date) as integer)+1 <=p_prazo))
            and (p_ini_i          is null or (p_ini_i       is not null and d.data_recebimento   between p_ini_i and p_ini_f))
            and (p_fim_i          is null or (p_fim_i       is not null and b.fim                between p_fim_i and p_fim_f))
            and (p_unidade        is null or (p_unidade     is not null and b.sq_unidade         = p_unidade))
            and (p_solicitante    is null or (p_solicitante is not null and d7.sq_especie_documento = p_solicitante))
            and (p_proponente     is null or (p_proponente  is not null and (to_char(d.pessoa_origem) = p_proponente or d2.nome_indice like '%'||acentos(p_proponente)||'%' or d2.nome_resumido_ind like '%'||acentos(p_proponente)||'%')))
            and (p_prioridade     is null or (p_prioridade  is not null and k.sq_tipo_despacho is not null and k.sq_tipo_despacho = p_prioridade))
            and (p_palavra        is null or (p_palavra     is not null and d.prefixo||'.'||substr(1000000+d.numero_documento,2,6)||'/'||d.ano||'-'||substr(100+d.digito,2,2) = p_palavra))
            and (p_sq_acao_ppa    is null or (p_sq_acao_ppa is not null and b.sq_solic_pai is null and ((instr(p_sq_acao_ppa,'#')=0 and d5.codigo like p_sq_acao_ppa||'%') or (instr(p_sq_acao_ppa,'#')>0 and d5.codigo = replace(p_sq_acao_ppa,'#','')))))
            and (p_empenho        is null or (p_empenho     is not null and acentos(d.numero_original) like '%'||acentos(p_empenho)||'%'))
            and (coalesce(p_atraso,'N') = 'N' or (p_atraso  = 'S'       and b1.ativo = 'S' and trunc(b.fim)+1 < trunc(sysdate)))
            and (p_sq_orprior     is null or (p_sq_orprior  is not null and d.sq_caixa           = p_sq_orprior))
            and (p_assunto        is null or (p_assunto     is not null and (acentos(b.descricao) like '%'||acentos(p_assunto)||'%'/* or 
                                                                             0 < (select count(*)
                                                                                    from siw_menu                     w
                                                                                         inner join siw_solicitacao   z on (w.sq_menu            = z.sq_menu)
                                                                                         inner join pa_documento      x on (z.sq_siw_solicitacao = x.sq_siw_solicitacao and w.sq_pessoa = x.cliente and x.ano = coalesce(p_cidade,x.ano))
                                                                                         inner join pa_documento_log  y on (z.sq_siw_solicitacao = y.sq_siw_solicitacao and y.resumo   is not null)
                                                                                   where z.sq_siw_solicitacao = d.sq_siw_solicitacao
                                                                                     and acentos(y.resumo)    like '%'||acentos(p_assunto)||'%'
                                                                                 ) or 
                                                                             0 < (select count(*)
                                                                                    from siw_menu                     w
                                                                                         inner join siw_solicitacao   z on (w.sq_menu            = z.sq_menu)
                                                                                         inner join pa_documento      x on (z.sq_siw_solicitacao = x.sq_siw_solicitacao and w.sq_pessoa   = x.cliente and x.ano = coalesce(p_cidade,x.ano))
                                                                                         inner join siw_solic_log     y on (z.sq_siw_solicitacao = y.sq_siw_solicitacao and y.observacao is not null)
                                                                                   where z.sq_siw_solicitacao = d.sq_siw_solicitacao
                                                                                     and acentos(y.observacao)    like '%'||acentos(p_assunto)||'%'
                                                                                 )*/
                                                                            )
                                             ) 
                 )  
            and (p_processo       is null or (p_processo    is not null and 0 < (select count(*)
                                                                                   from pa_documento_interessado x
                                                                                        inner join co_pessoa     y on (x.sq_pessoa = y.sq_pessoa)
                                                                                  where x.sq_siw_solicitacao = d.sq_siw_solicitacao
                                                                                    and (acentos(y.nome_indice)       like '%'||acentos(p_processo)||'%' or
                                                                                         acentos(y.nome_resumido_ind) like '%'||acentos(p_processo)||'%'
                                                                                        )
                                                                                )
                                             )
                );
   Elsif p_restricao = 'PROTOCOLO' Then
      -- Recupera os documentos ligados ao protocolo informado
      open p_result for
         select a.sq_menu,            a.sq_modulo,                   a.nome,
                a.tramite,            a.ultimo_nivel,                a.p1,
                a.p2,                 a.p3,                          a.p4,
                a.sigla,              a.descentralizado,             a.externo,
                a.acesso_geral,       a.ordem as or_servico,         a.sq_unid_executora,  
                a.emite_os,           a.consulta_opiniao,            a.envia_email,
                a.exibe_relatorio,    a.vinculacao,                  a.data_hora,
                a.envia_dia_util,     a.descricao,                   a.justificativa,
                a1.nome as nm_modulo, a1.sigla as sg_modulo,         a1.ordem as or_modulo,
                b.sq_siw_solicitacao, b.sq_siw_tramite,              b.solicitante,
                b.cadastrador,        b.executor,                    b.descricao,
                b.justificativa,      b.inicio,                      b.fim,
                b.inclusao,           b.ultima_alteracao,            b.conclusao,
                b.valor,              b.opiniao,                     b.protocolo_siw,
                b.sq_solic_pai,       b.sq_unidade,                  b.sq_cidade_origem,
                b.palavra_chave,      dados_solic(b.sq_siw_solicitacao) as dados_solic,
                coalesce(b.codigo_interno,to_char(b.sq_siw_solicitacao)) as codigo_interno,
                codigo2numero(coalesce(b.codigo_interno,to_char(b.sq_siw_solicitacao))) as ord_codigo_interno,
                coalesce(b.codigo_interno,b.titulo,to_char(b.sq_siw_solicitacao)) as titulo,
                b.titulo as ac_titulo,
                b1.sq_siw_tramite,    b1.ordem or_tramite,           b1.nome as nm_tramite,
                b1.sigla sg_tramite,  b1.ativo,                      b1.envia_mail
           from siw_menu                                      a
                inner          join siw_modulo                a1 on (a.sq_modulo           = a1.sq_modulo)
                inner          join siw_solicitacao           b  on (a.sq_menu             = b.sq_menu)
                  inner        join siw_tramite               b1 on (b.sq_siw_tramite      = b1.sq_siw_tramite and b1.sigla <> 'CA')
                  inner        join siw_solicitacao           c  on (b.protocolo_siw       = c.sq_siw_solicitacao)
                    inner      join siw_menu                  c1 on (c.sq_menu             = c1.sq_menu)
                    inner      join pa_documento              c2 on (c.sq_siw_solicitacao  = c2.sq_siw_solicitacao)
          where c1.sq_menu            = p_menu
            and c2.prefixo            = p_pais
            and c2.numero_documento   = p_regiao
            and c2.ano                = p_cidade
            and a.sq_menu             <> c1.sq_menu; -- Evita que sejam recuperadas cópias do protocolo informado
   End If;
end SP_GetSolicPAD;
/
