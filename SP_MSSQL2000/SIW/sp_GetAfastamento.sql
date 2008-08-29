alter procedure sp_GetAfastamento
   (@p_cliente                  int,
    @p_pessoa                   int         =null,
    @p_chave                    int         =null,
    @p_sq_tipo_afastamento      int         =null,
    @p_sq_contrato_colaborador  int         =null,
    @p_inicio_data              datetime    =null,
    @p_fim_data                 datetime    =null,
    @p_periodo_inicio           varchar(1)  =null,
    @p_periodo_fim              varchar(1)  =null,
    @p_chave_aux                int         =null,
    @p_restricao                varchar(20) =null
   ) as
    declare @w_inicio int;
    declare @w_fim    int;
begin
   If @p_inicio_data is not null begin
      set @w_inicio = dbo.to_char(@p_inicio_data,'yyyymmdd')+ case coalesce(@p_periodo_inicio,'M') when 'M' then 0 else 1 end;
      set @w_fim    = dbo.to_char(@p_fim_data,'yyyymmdd')+case coalesce(@p_periodo_fim,'T') when 'M' then 0 else 1 end;
   end
   
   If @p_restricao is null begin
      -- Recupera todos os afastamentos
         select a.sq_afastamento as chave, a.sq_tipo_afastamento, a.sq_contrato_colaborador, a.inicio_data,
                a.inicio_periodo, a.fim_data, a.fim_periodo, a.dias, a.observacao, 
                case inicio_periodo when 'M' then 'Manhã' else 'Tarde' end as nm_inicio_periodo,
                case fim_periodo when 'M' then 'Manhã' else 'Tarde' end as nm_fim_periodo,
                b.nome as nm_tipo_afastamento, 
                e.sq_unidade, e.sigla+' ('+d.nome+' - R.'+d.ramal+')' as local, 
                f.nome_resumido, f.sq_pessoa
           from gp_afastamento                     a
                inner join gp_tipo_afastamento     b on (a.sq_tipo_afastamento     = b.sq_tipo_afastamento)
                inner join gp_contrato_colaborador c on (a.sq_contrato_colaborador = c.sq_contrato_colaborador)
                  inner join eo_localizacao        d on (c.sq_localizacao          = d.sq_localizacao)
                    inner join eo_unidade          e on (d.sq_unidade              = e.sq_unidade)
                  inner join co_pessoa             f on (c.sq_pessoa               = f.sq_pessoa and
                                                         c.cliente                 = f.sq_pessoa_pai)
          where a.cliente = @p_cliente
            and ((@p_pessoa                  is null) or (@p_pessoa                  is not null and f.sq_pessoa               = @p_pessoa))
            and ((@p_chave                   is null) or (@p_chave                   is not null and a.sq_afastamento          = @p_chave))
            and ((@p_chave_aux               is null) or (@p_chave_aux               is not null and a.sq_afastamento          <> @p_chave_aux))
            and ((@p_sq_tipo_afastamento     is null) or (@p_sq_tipo_afastamento     is not null and a.sq_tipo_afastamento     = @p_sq_tipo_afastamento))
            and ((@p_sq_contrato_colaborador is null) or (@p_sq_contrato_colaborador is not null and a.sq_contrato_colaborador = @p_sq_contrato_colaborador))
            and ((@p_inicio_data             is null) or (@p_inicio_data             is not null and (dbo.to_char(a.inicio_data,'yyyymmdd')+case coalesce(a.inicio_periodo,'M') when 'M' then 0 else 1 end between @w_inicio and @w_fim or
                                                                                                            dbo.to_char(a.fim_data,'yyyymmdd')+case coalesce(a.fim_periodo,'M') when 'M' then 0 else 1 end between @w_inicio and @w_fim or
                                                                                       @w_inicio  between dbo.to_char(a.inicio_data,'yyyymmdd')+case coalesce(a.inicio_periodo,'M') when 'M' then 0 else 1 end and dbo.to_char(a.fim_data,'yyyymmdd')+case coalesce(a.fim_periodo,'M') when 'M' then 0 else 1 end or
                                                                                       @w_fim     between dbo.to_char(a.inicio_data,'yyyymmdd')+case coalesce(a.inicio_periodo,'M') when 'M' then 0 else 1 end and dbo.to_char(a.fim_data,'yyyymmdd')+case coalesce(a.fim_periodo,'M') when 'M' then 0 else 1 end
                                                                                                   )
                                                        )
                );
   end else if @p_restricao = 'VERIFICAENVIO' begin
      -- Verifica se houve algum envio para o afastamento
         select count(*) as existe 
           from gp_afastamento_envio a
          where a.sq_afastamento = @p_chave;
   end
end