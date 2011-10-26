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
          select a.sq_celular as chave, a.cliente, a.numero_linha, a.marca, a.modelo, a.sim_card, a.imei, a.acessorios, a.ativo,
                 a.bloqueado, a.inicio_bloqueio, a.fim_bloqueio, a.motivo_bloqueio,
                 case a.ativo   when 'S' Then 'Sim' Else 'Não' end  nm_ativo
            from sr_celular a
           where a.cliente     = p_cliente
             and ((p_chave     is null) or (p_chave     is not null and a.sq_celular       = p_chave))
             and ((p_ativo     is null) or (p_ativo     is not null and a.ativo            = p_ativo))  
             and ((p_numero    is null) or (p_numero    is not null and a.numero_linha     = p_numero));
   Elsif p_restricao is not null Then
       -- Recupera os grupos de veículos
       open p_result for 
          select a.sq_celular as chave, a.cliente, a.numero_linha, a.marca, a.modelo, a.sim_card, a.acessorios, a.imei, a.ativo,
                 a.bloqueado, a.inicio_bloqueio, a.fim_bloqueio, a.motivo_bloqueio,
                 d.sq_siw_solicitacao, d.inicio, d.fim, d.pendencia,
                 d.sq_siw_tramite, d.nome as nm_tramite, d.ativo as st_tramite, d.sigla as sg_tramite,
                 coalesce(d.codigo_interno, to_char(d.sq_siw_solicitacao)) as codigo_interno
            from sr_celular                               a
                 left      join (select x.sq_siw_solicitacao, x.codigo_interno, 
                                        coalesce(z.inicio_real, x.inicio) as inicio, coalesce(z.fim_real, x.fim) as fim, 
                                        y.sq_siw_tramite, y.nome as nm_tramite, y.sigla as sg_tramite,
                                        y.sigla, y.ativo, y.nome,
                                        z.sq_celular, z.pendencia
                                   from siw_solicitacao                        x
                                        inner   join siw_tramite               y on (x.sq_siw_tramite     = y.sq_siw_tramite and
                                                                                     'CA'                 <> y.sigla and 
                                                                                     (p_restricao         <> 'MAPAFUTURO' or
                                                                                      (p_restricao        = 'MAPAFUTURO' and 
                                                                                       y.ordem             in (3,4,5,6,7,8,9)
                                                                                      )
                                                                                     )
                                                                                    )
                                        inner   join sr_solicitacao_celular    z on (x.sq_siw_solicitacao = z.sq_siw_solicitacao)
                                  where z.sq_celular is not null
                                    and (p_inicio  is null or 
                                         (p_inicio is not null and (x.inicio     between p_inicio and p_fim or
                                                                    x.fim        between p_inicio and p_fim or
                                                                    p_inicio     between x.inicio and x.fim or
                                                                    p_fim        between x.inicio and x.fim
                                                                   )
                                         )
                                        )
                                    and (p_restricao  <> 'MAPAFUTURO' or
                                         (p_restricao = 'MAPAFUTURO' and x.sq_siw_solicitacao <> coalesce(p_solic,0))
                                        )
                                )                         d on (a.sq_celular           = d.sq_celular)
           where a.cliente    = p_cliente
             and (p_chave     is null or (p_chave     is not null and a.sq_celular       = p_chave))
             and (p_pendencia is null or (p_pendencia is not null and (a.bloqueado       = p_pendencia)))
             and (p_ativo     is null or (p_ativo     is not null and a.ativo            = p_ativo))
             and (p_numero    is null or (p_numero    is not null and a.numero_linha     = p_numero));
   End If;
end SP_GetCelular;
/
