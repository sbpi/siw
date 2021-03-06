create procedure Sp_GetUserTypeList
   (@p_nome      varchar(30) = null,
    @p_ativo     varchar(1) = null
  ) as
begin
   -- Recupera o tipos de pessoas existentes   
      select sq_tipo_pessoa, nome, padrao, 
             case padrao when 'S' then 'Sim' else 'Não' end padraodesc, 
             ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end ativodesc
        from co_tipo_pessoa
       where (@p_nome  is null or (@p_nome  is not null and dbo.acentos(nome) like '%'+dbo.acentos(@p_nome)+'%'))
         and (@p_ativo is null or (@p_ativo is not null and ativo = @p_ativo));
end 
