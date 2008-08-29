alter procedure SP_GetDataEspecial
   (@p_cliente   int,
    @p_chave     int         = null,
    @p_ano       varchar( 4) = null,
    @p_ativo     varchar( 1) = null,
    @p_tipo      varchar( 1) = null,
    @p_chave_aux int         = null,
    @p_restricao varchar(20) = null) as
begin
   If @p_restricao is null Begin
      -- Recupera todas ou muma das modalidades de contratação
         select a.sq_data_especial chave, a.cliente, a.sq_pais, a.co_uf, a.sq_cidade, a.tipo,
                a.data_especial, a.nome, a.abrangencia, a.expediente, a.ativo,
                case a.tipo 
                     when 'E' then cast(a.data_especial as datetime)
                     when 'I' then convert(datetime, a.data_especial+'/'+cast(coalesce(@p_ano,year(getDate())) as varchar),103)
                     else          dbo.VerificaDataMovel(coalesce(@p_ano,year(getDate())), a.tipo)
                end as data_formatada,
                case a.expediente
                     when 'S' then ' (Exp. normal)'
                     when 'M' then ' (Exp. apenas manhã)'
                     when 'T' then ' (Exp. apenas tarde)'
                     when 'N' then ' (Sem expediente)'
                end as nm_expediente
           from eo_data_especial  a
          where a.cliente = @p_cliente
            and ((@p_chave is null) or (@p_chave is not null and a.sq_data_especial = @p_chave))
            and ((@p_ativo is null) or (@p_ativo is not null and a.ativo            = @p_ativo))
            and ((@p_tipo  is null) or (@p_tipo  is not null and a.tipo             = @p_tipo))
            and ((@p_ano   is null) or (@p_ano   is not null and (a.tipo <> 'E' or (a.tipo = 'E' and substring(a.data_especial, 7, 4) = @p_ano))));
   End Else If @p_restricao = 'VERIFICATIPO' Begin
      -- Verifica se o tipo já foi cadastrado
         select a.tipo
           from eo_data_especial  a
          where a.cliente = @p_cliente
            and a.tipo not in ('I','E');
   End
end
