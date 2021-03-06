create  procedure SP_GetEtniaList
   (@p_nome        varchar(50) =  null,
    @p_ativo       varchar(14) = null
) as
begin


      select codigo_siape, sq_etnia, nome, ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end descativo 
        from co_etnia
       where (@p_nome  is null or (@p_nome  is not null and dbo.acentos(nome) like '%'+dbo.acentos(@p_nome)+'%'))
         and (@p_ativo is null or (@p_ativo is not null and ativo = @p_ativo));
end ;


