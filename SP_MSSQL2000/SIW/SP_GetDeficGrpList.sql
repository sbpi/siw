alter  procedure Sp_GetDeficGrpList
   (@p_nome      varchar(50) =  null,
    @p_ativo     varchar(1) =  null
   ) as
begin
   -- Recupera os grupos de deficiências existentes
   
      select sq_grupo_defic, nome, ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end ativodesc 
        from co_grupo_defic
       where (@p_nome  is null or (@p_nome  is not null and dbo.acentos(nome) like '%'+dbo.acentos(@p_nome)+'%'))
         and (@p_ativo is null or (@p_ativo is not null and ativo = @p_ativo))
      order by nome; 
end 