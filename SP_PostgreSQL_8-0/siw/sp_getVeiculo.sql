create or replace FUNCTION SP_GetVeiculo
   (p_chave             numeric,
    p_chave_aux         numeric,
    p_cliente           varchar, 
    p_placa             varchar, 
    p_alugado           varchar,     
    p_ativo             varchar,
    p_solic             numeric,
    p_inicio            date,
    p_fim               date,
    p_restricao         varchar,
    p_result            REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
       -- Recupera os grupos de veículos
       open p_result for 
          select a.sq_veiculo chave, a.sq_tipo_veiculo, a.cliente, a.placa, a.marca, a.modelo, a.combustivel, 
                 a.tipo, a.potencia, a.cilindrada, a.ano_modelo, a.ano_fabricacao, a.renavam, a.chassi, a.alugado, a.ativo,
                 case a.ativo   when 'S' Then 'Sim' Else 'Não' end  nm_ativo,
                 case a.alugado when 'S' Then 'Sim' Else 'Não' end  nm_alugado,
                 substr(a.placa,1,3)||'-'||substr(a.placa,4)||' - '||a.marca||' '||a.modelo as nm_veiculo,
                 b.sq_tipo_veiculo,  b.nome as nm_tipo_veiculo,  b.sigla as sg_tipo_veiculo,
                 c.sq_grupo_veiculo, c.nome as nm_grupo_veiculo, c.sigla as sg_grupo_veiculo
            from sr_veiculo                    a
                 inner   join sr_tipo_veiculo  b on (a.sq_tipo_veiculo  = b.sq_tipo_veiculo)
                   inner join sr_grupo_veiculo c on (b.sq_grupo_veiculo = c.sq_grupo_veiculo)
           where a.cliente     = p_cliente
             and ((p_chave     is null) or (p_chave     is not null and a.sq_veiculo       = p_chave))
             and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_tipo_veiculo  = p_chave_aux))
             and ((p_ativo     is null) or (p_ativo     is not null and a.ativo            = p_ativo))  
             and ((p_alugado   is null) or (p_alugado   is not null and a.alugado          = p_alugado))                    
             and ((p_placa     is null) or (p_placa     is not null and a.placa            = p_placa));
   Elsif p_restricao is not null Then
       -- Recupera os grupos de veículos
       open p_result for 
          select a.sq_veiculo chave, a.sq_tipo_veiculo, a.cliente, a.placa, a.marca, a.modelo, a.combustivel, 
                 a.tipo, a.potencia, a.cilindrada, a.ano_modelo, a.ano_fabricacao, a.renavam, a.chassi, a.alugado, a.ativo,
                 case a.ativo   when 'S' Then 'Sim' Else 'Não' end  nm_ativo,
                 case a.alugado when 'S' Then 'Sim' Else 'Não' end  nm_alugado,
                 substr(a.placa,1,3)||'-'||substr(a.placa,4)||' - '||a.marca||' '||a.modelo as nm_veiculo,
                 b.sq_tipo_veiculo,  b.nome as nm_tipo_veiculo,  b.sigla as sg_tipo_veiculo,
                 c.sq_grupo_veiculo, c.nome as nm_grupo_veiculo, c.sigla as sg_grupo_veiculo,
                 d.sq_siw_solicitacao, d.inicio, d.fim, d.conclusao, 
                 d.sq_siw_tramite, d.nome as nm_tramite, d.ativo as st_tramite, d.sigla as sg_tramite,
                 d.qtd_pessoas, d.carga, d.hodometro_saida, d.hodometro_chegada, d.horario_saida, d.horario_chegada,
                 d.destino, d.parcial, d.procedimento,
                 d.sq_solic, d.nm_solic, d.nm_res_solic,
                 d.sq_unid, d.nm_unid, d.sg_unid,
                 d.phpdt_inclusao, d.phpdt_inicio, d.phpdt_fim, d.phpdt_conclusao,
                 case when d.sq_siw_solicitacao is null then null else
                      case coalesce(d.procedimento,0) 
                           when 0 then 'Não informado'
                           when 1 then 'Levar'
                           when 2 then 'Aguardar'
                           when 3 then 'Buscar'
                           when 4 then 'Abastecimento'
                      end
                 end as nm_procedimento,
                 case when d.sq_siw_solicitacao is null 
                      then 'Não alocado'
                      else 'Alocado'
                 end as st_veiculo
            from sr_veiculo                               a
                 inner     join sr_tipo_veiculo           b on (a.sq_tipo_veiculo      = b.sq_tipo_veiculo)
                   inner   join sr_grupo_veiculo          c on (b.sq_grupo_veiculo     = c.sq_grupo_veiculo)
                 left      join (select x.sq_siw_solicitacao, x.inicio, x.fim, x.conclusao, 
                                        y.sq_siw_tramite, y.nome as nm_tramite, y.ativo as st_tramite, y.sigla as sg_tramite,
                                        y.sigla, y.ativo, y.nome,
                                        z.sq_veiculo, z.qtd_pessoas, z.carga, z.hodometro_saida, z.hodometro_chegada, z.horario_saida, 
                                        z.horario_chegada, z.destino, z.parcial, z.procedimento,
                                        k.sq_pessoa as sq_solic, k.nome as nm_solic, k.nome_resumido as nm_res_solic,
                                        l.sq_unidade as sq_unid, l.nome as nm_unid,  l.sigla as sg_unid,
                                        to_char(x.inclusao,'dd/mm/yyyy, hh24:mi:ss')  phpdt_inclusao,
                                        to_char(x.inicio,'dd/mm/yyyy, hh24:mi:ss')    phpdt_inicio,
                                        to_char(x.fim,'dd/mm/yyyy, hh24:mi:ss')       phpdt_fim,
                                        to_char(x.conclusao,'dd/mm/yyyy, hh24:mi:ss') phpdt_conclusao
                                   from siw_solicitacao                      x
                                        inner join siw_tramite               y on (x.sq_siw_tramite     = y.sq_siw_tramite and
                                                                                   'CA'                 <> coalesce(y.sigla,'-') and 
                                                                                   (p_restricao         <> 'MAPAFUTURO' or
                                                                                    (p_restricao        = 'MAPAFUTURO' and 
                                                                                     'S'                = y.ativo and 
                                                                                     'CI'               <> coalesce(y.sigla,'-'))
                                                                                   )
                                                                                  )
                                        inner join sr_solicitacao_transporte z on (x.sq_siw_solicitacao = z.sq_siw_solicitacao)
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
                                )                         d on (a.sq_veiculo           = d.sq_veiculo)
           where a.cliente    = p_cliente
             and (p_chave     is null or (p_chave     is not null and a.sq_veiculo       = p_chave))
             and (p_chave_aux is null or (p_chave_aux is not null and a.sq_tipo_veiculo  = p_chave_aux))
             and (p_ativo     is null or (p_ativo     is not null and a.ativo            = p_ativo))  
             and (p_alugado   is null or (p_alugado   is not null and a.alugado          = p_alugado))                    
             and (p_placa     is null or (p_placa     is not null and a.placa            = p_placa));
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;