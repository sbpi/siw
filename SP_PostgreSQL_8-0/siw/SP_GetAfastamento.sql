﻿create or replace FUNCTION SP_GetAfastamento
   (p_cliente                  numeric,
    p_pessoa                   numeric,
    p_chave                    numeric,
    p_sq_tipo_afastamento      numeric,
    p_sq_contrato_colaborador  numeric,
    p_inicio_data              date,
    p_fim_data                 date,
    p_periodo_inicio           varchar,
    p_periodo_fim              varchar,
    p_chave_aux                numeric,
    p_restricao                varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
    w_inicio numeric(9);
    w_fim    numeric(9);
BEGIN
   If p_inicio_data is not null Then
      w_inicio := to_char(p_inicio_data,'yyyymmdd')||case coalesce(p_periodo_inicio,'M') when 'M' then 0 else 1 end;
      w_fim    := to_char(p_fim_data,'yyyymmdd')||case coalesce(p_periodo_fim,'T') when 'M' then 0 else 1 end;
   End If;
   
   If p_restricao is null Then
      -- Recupera todos os afastamentos
      open p_result for 
         select a.sq_afastamento as chave, a.sq_tipo_afastamento, a.sq_contrato_colaborador, a.inicio_data,
                a.inicio_periodo, a.fim_data, a.fim_periodo, a.dias, a.observacao, 
                case inicio_periodo when 'M' then 'Manhã' else 'Tarde' end as nm_inicio_periodo,
                case fim_periodo when 'M' then 'Manhã' else 'Tarde' end as nm_fim_periodo,
                b.nome as nm_tipo_afastamento, b.sigla, b.abate_banco_horas, b.abate_ferias, b.falta_nao_justificada,
                e.sq_unidade, e.sigla||' ('||d.nome||' - R.'||d.ramal||')' as local, 
                f.nome_resumido, f.sq_pessoa
           from gp_afastamento                     a
                inner join gp_tipo_afastamento     b on (a.sq_tipo_afastamento     = b.sq_tipo_afastamento)
                inner join gp_contrato_colaborador c on (a.sq_contrato_colaborador = c.sq_contrato_colaborador)
                  inner join eo_localizacao        d on (c.sq_localizacao          = d.sq_localizacao)
                    inner join eo_unidade          e on (d.sq_unidade              = e.sq_unidade)
                  inner join co_pessoa             f on (c.sq_pessoa               = f.sq_pessoa and
                                                         c.cliente                 = f.sq_pessoa_pai)
          where a.cliente = p_cliente
            and ((p_pessoa                  is null) or (p_pessoa                  is not null and f.sq_pessoa               = p_pessoa))
            and ((p_chave                   is null) or (p_chave                   is not null and a.sq_afastamento          = p_chave))
            and ((p_chave_aux               is null) or (p_chave_aux               is not null and a.sq_afastamento          <> p_chave_aux))
            and ((p_sq_tipo_afastamento     is null) or (p_sq_tipo_afastamento     is not null and a.sq_tipo_afastamento     = p_sq_tipo_afastamento))
            and ((p_sq_contrato_colaborador is null) or (p_sq_contrato_colaborador is not null and a.sq_contrato_colaborador = p_sq_contrato_colaborador))
            and ((p_inicio_data             is null) or (p_inicio_data             is not null and (to_number(to_char(a.inicio_data,'yyyymmdd')||case coalesce(a.inicio_periodo,'M') when 'M' then 0 else 1 end) between w_inicio and w_fim or
                                                                                                    to_number(to_char(a.fim_data,'yyyymmdd')||case coalesce(a.fim_periodo,'M') when 'M' then 0 else 1 end) between w_inicio and w_fim or
                                                                                                    w_inicio  between to_number(to_char(a.inicio_data,'yyyymmdd')||case coalesce(a.inicio_periodo,'M') when 'M' then 0 else 1 end) and to_number(to_char(a.fim_data,'yyyymmdd')||case coalesce(a.fim_periodo,'M') when 'M' then 0 else 1 end) or
                                                                                                    w_fim     between to_number(to_char(a.inicio_data,'yyyymmdd')||case coalesce(a.inicio_periodo,'M') when 'M' then 0 else 1 end) and to_number(to_char(a.fim_data,'yyyymmdd')||case coalesce(a.fim_periodo,'M') when 'M' then 0 else 1 end)
                                                                                                   )
                                                        )
                );
   ElsIf p_restricao = 'VERIFICAENVIO' Then
      -- Verifica se houve algum envio para o afastamento
      open p_result for 
         select count(*) as existe 
           from gp_afastamento_envio a
          where a.sq_afastamento = p_chave;
   End If;

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;