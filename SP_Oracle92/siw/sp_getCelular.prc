create or replace procedure SP_GetCelular
   (p_cliente           in number, 
    p_chave             in number   default null,
    p_chave_aux         in number   default null,
    p_numero            in varchar2 default null, 
    p_pendencia         in varchar2 default null,     
    p_ativo             in varchar2 default null,
    p_solic             in number   default null,
    p_inicio            in date     default null,
    p_fim               in date     default null,
    p_restricao         in varchar2 default null,
    p_result            out sys_refcursor) is
begin
   If p_restricao is null Then
       -- Recupera os grupos de veículos
       open p_result for 
          select a.sq_celular as chave, a.cliente, a.numero_linha, a.marca, a.modelo, a.sim_card, a.imei, a.ativo,
                 case a.ativo   when 'S' Then 'Sim' Else 'Não' end  nm_ativo
            from sr_celular a
           where a.cliente     = p_cliente
             and ((p_chave     is null) or (p_chave     is not null and a.sq_celular       = p_chave))
             and ((p_ativo     is null) or (p_ativo     is not null and a.ativo            = p_ativo))  
             and ((p_numero    is null) or (p_numero    is not null and a.numero_linha     = p_numero));
   Elsif p_restricao is not null Then
       -- Recupera os grupos de veículos
       open p_result for 
          select a.sq_celular as chave, a.cliente, a.numero_linha, a.marca, a.modelo, a.sim_card, a.imei, a.ativo,
                 case a.ativo   when 'S' Then 'Sim' Else 'Não' end  nm_ativo,
                 d.sq_siw_solicitacao, d.inicio, d.fim, d.conclusao, d.pendencia,
                 d.sq_siw_tramite, d.nome as nm_tramite, d.ativo as st_tramite, d.sigla as sg_tramite,
                 d.sq_solic, d.nm_solic, d.nm_res_solic,
                 d.sq_unid, d.nm_unid, d.sg_unid,
                 d.phpdt_inclusao, d.phpdt_inicio, d.phpdt_fim, d.phpdt_conclusao,
                 case when d.sq_siw_solicitacao is null 
                      then 'Não alocado'
                      else 'Alocado'
                 end as st_celular
            from sr_celular                               a
                 left      join (select x.sq_siw_solicitacao, x.inicio, x.fim, x.conclusao, 
                                        y.sq_siw_tramite, y.nome as nm_tramite, y.ativo as st_tramite, y.sigla as sg_tramite,
                                        y.sigla, y.ativo, y.nome,
                                        z.sq_celular, z.inicio_real, z.fim_real, z.pendencia,
                                        k.sq_pessoa as sq_solic, k.nome as nm_solic, k.nome_resumido as nm_res_solic,
                                        l.sq_unidade as sq_unid, l.nome as nm_unid,  l.sigla as sg_unid,
                                        to_char(x.inclusao,'dd/mm/yyyy, hh24:mi:ss')    phpdt_inclusao,
                                        to_char(x.inicio,'dd/mm/yyyy, hh24:mi:ss')      phpdt_inicio,
                                        to_char(x.fim,'dd/mm/yyyy, hh24:mi:ss')         phpdt_fim,
                                        to_char(x.conclusao,'dd/mm/yyyy, hh24:mi:ss')   phpdt_conclusao,
                                        to_char(z.inicio_real,'dd/mm/yyyy, hh24:mi:ss') phpdt_inicio_real,
                                        to_char(z.fim_real,'dd/mm/yyyy, hh24:mi:ss')    phpdt_fim_real
                                   from siw_solicitacao                      x
                                        inner join siw_tramite               y on (x.sq_siw_tramite     = y.sq_siw_tramite and
                                                                                   'CA'                 <> coalesce(y.sigla,'-') and 
                                                                                   (p_restricao         <> 'MAPAFUTURO' or
                                                                                    (p_restricao        = 'MAPAFUTURO' and 
                                                                                     'S'                = y.ativo and 
                                                                                     'CI'               <> coalesce(y.sigla,'-'))
                                                                                   )
                                                                                  )
                                        inner join sr_solicitacao_celular    z on (x.sq_siw_solicitacao = z.sq_siw_solicitacao)
                                        inner join co_pessoa                 k on (x.solicitante        = k.sq_pessoa)
                                        inner join eo_unidade                l on (x.sq_unidade         = l.sq_unidade)
                                  where (p_inicio  is null or 
                                         (p_inicio is not null and (trunc(x.inicio)   between p_inicio and p_fim or
                                                                    trunc(x.fim)      between p_inicio and p_fim or
                                                                    p_inicio          between trunc(x.inicio) and trunc(x.fim) or
                                                                    p_fim             between trunc(x.inicio) and trunc(x.fim)
                                                                   )
                                         )
                                        )
                                    and (p_restricao  <> 'MAPAFUTURO' or
                                         (p_restricao = 'MAPAFUTURO' and x.sq_siw_solicitacao <> p_solic)
                                        )
                                )                         d on (a.sq_celular           = d.sq_celular)
           where a.cliente    = p_cliente
             and (p_chave     is null or (p_chave     is not null and a.sq_celular       = p_chave))
             and (p_pendencia is null or (p_pendencia is not null and (d.pendencia is null or d.pendencia = p_pendencia)))
             and (p_ativo     is null or (p_ativo     is not null and a.ativo            = p_ativo))
             and (p_numero    is null or (p_numero    is not null and a.numero_linha     = p_numero));
   End If;
end SP_GetCelular;
/
