alter  procedure SP_GetIdiomList
   (@p_nome      varchar(50) =  null,
    @p_ativo     varchar(1) =  null
   ) as
begin
   -- Recupera os idiomas          existentes
 
      select sq_idioma, nome, padrao, ativo, 
             case padrao when 'S' then 'Sim' else 'Não' end padraodesc, 
             case ativo when 'S' then 'Sim' else 'Não' end ativodesc 
       from co_idioma
       where (@p_nome  is null or (@p_nome  is not null and DBO.acentos(nome) like '%'+DBO.acentos(@p_nome)+'%'))
         and (@p_ativo is null or (@p_ativo is not null and ativo = @p_ativo));
end 