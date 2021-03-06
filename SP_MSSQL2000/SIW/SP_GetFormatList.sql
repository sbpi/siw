alter procedure Sp_GetFormatList
   (@p_tipo      varchar(20) =  null,
    @p_nome      varchar(50) =  null,
    @p_ativo     varchar(1) =  null
 ) as
begin
   -- Recupera os bancos existentes
  
      select a.ordem, a.sq_formacao, a.nome, a.ativo, b.tipo,
             case a.ativo when 'S' then 'Sim' else 'Não' end ativodesc
        from co_formacao   a
             inner join (select sq_formacao, 
                                case tipo when '1' then 'Acadêmica' 
                                          when '2' then 'Técnica'
                                          else 'Prod.Cient.'
                                end tipo
                           from co_formacao
                         ) b on a.sq_formacao = b.sq_formacao
       where (@p_tipo  is null or (@p_tipo  is not null and b.tipo = @p_tipo))
         and (@p_nome  is null or (@p_nome  is not null and dbo.acentos(a.nome) like '%'+dbo.acentos(@p_nome)+'%'))
         and (@p_ativo is null or (@p_ativo is not null and a.ativo = @p_ativo));
end 
