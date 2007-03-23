create or replace procedure sp_getSolicRestricao
   (p_chave                 in  number   default null,
    p_chave_aux             in  number   default null,
    p_pessoa                in  number   default null,
    p_pessoa_atualizacao    in  number   default null,
    p_tipo_restricao        in  number   default null,
    p_risco                 in  varchar2 default null,
    p_restricao             in  varchar2 default null,
    p_result                out sys_refcursor) is
begin
   If p_restricao is null or p_restricao = 'EXISTERESTRICAO' Then
      -- Recupera as metas ligadas a uma solicitação
      open p_result for 
         select a.sq_siw_restricao    as chave_aux,                    a.sq_siw_solicitacao as chave, 
                a.sq_pessoa,           a.sq_pessoa_atualizacao,        a.sq_tipo_restricao,
                a.risco,               a.problema,                     a.descricao,           
                a.criticidade,         a.estrategia,                   a.acao_resposta,
                a.data_situacao,       a.situacao_atual,               a.ultima_atualizacao,
                a.probabilidade,       a.impacto,                      a.fase_atual,
                case a.risco         when 'S' then 'Sim' else 'Não' end as nm_risco, 
                case a.probabilidade when 1 then 'Muito baixa' when 2 then 'Baixa' when 3 then 'Média' when 4 then 'Alta' when 5 then 'Muito alta' end as nm_probabilidade,
                case a.impacto       when 1 then 'Muito baixo' when 2 then 'Baixo' when 3 then 'Médio' when 4 then 'Alto' when 5 then 'Muito alto' end as nm_impacto,
                case a.criticidade   when 1 then 'Baixa'       when 2 then 'Média' else 'Alta' end as nm_criticidade,
                case a.estrategia    when 'A' then 'Aceitar'  when 'E' then 'Evitar'   when 'T' then 'Transferir'   when 'M' then 'Mitigar' end as nm_estrategia,
                case a.fase_atual    when 'D' then 'Definido' when 'P' then 'Pendente' when 'A' then 'Em andamento' when 'C' then 'Concluído' end as nm_fase_atual,
                a1.sq_siw_tramite,     a1.solicitante,                 a1.inicio as ini_solic,
                a1.fim as fim_solic,   a1.conclusao,
                a2.sq_menu,            a2.sq_modulo,                   a2.nome,
                a2.p1,                 a2.p2,                          a2.p3,
                a2.p4,                 a2.sigla,                       a2.link,
                a3.nome nm_modulo,     a3.sigla sg_modulo,             
                a4.nome nm_tramite,    a4.ordem or_tramite,            a4.sigla sg_tramite,
                a4.ativo st_tramite,
                i.nome_resumido as nm_resp,                            i.nome_resumido_ind as nm_resp_ind,
                j.nome_resumido as nm_atualiz,                         j.nome_resumido_ind as nm_atualiz_ind,
                b.nome nm_tipo
           from siw_restricao                     a
                inner     join siw_solicitacao    a1 on (a.sq_siw_solicitacao    = a1.sq_siw_solicitacao)
                  inner   join siw_menu           a2 on (a1.sq_menu              = a2.sq_menu)
                    inner join siw_modulo         a3 on (a2.sq_modulo            = a3.sq_modulo)
                  inner   join siw_tramite        a4 on (a1.sq_siw_tramite       = a4.sq_siw_tramite)
                  inner   join co_pessoa          a5 on (a1.solicitante          = a5.sq_pessoa)
                inner     join siw_tipo_restricao b  on (a.sq_tipo_restricao     = b.sq_tipo_restricao) 
                inner     join co_pessoa          i  on (a.sq_pessoa             = i.sq_pessoa)
                inner     join co_pessoa          j  on (a.sq_pessoa_atualizacao = j.sq_pessoa)
          where (p_chave     is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux is null or (p_chave_aux is not null and ((p_restricao is null and a.sq_siw_restricao = p_chave_aux) or 
                                                                      (p_restricao = 'EXISTEMETA' and a.sq_siw_solicitacao <> coalesce(p_chave_aux,0))
                                                                     )
                                        )
                )
            and (p_pessoa             is null or (p_pessoa              is not null and a.sq_pessoa             = p_pessoa))
            and (p_pessoa_atualizacao is null or (p_pessoa_atualizacao  is not null and a.sq_pessoa_atualizacao = p_pessoa_atualizacao))
            and (p_tipo_restricao     is null or (p_tipo_restricao      is not null and a.sq_tipo_restricao     = p_tipo_restricao))
            and (p_risco              is null or (p_risco               is not null and a.risco                 = p_risco));
   End If;
end sp_getSolicRestricao;
/
