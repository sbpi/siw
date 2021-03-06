alter procedure Sp_GetDefList
   (@p_nome      varchar(50) = null,
    @p_ativo     varchar(1) = null
   ) as
begin
   -- Recupera as deficiências existentes

      select a.sq_deficiencia, a.nome, a.ativo, 
             case a.ativo when 'S' then 'Sim' else 'Não' end ativodesc,
             a.codigo, coalesce(a.descricao,'-') descricao, b.nome sq_grupo_defic
        from co_deficiencia a, co_grupo_defic b  
      where a.sq_grupo_defic = b.sq_grupo_defic
        and (@p_nome  is null or (@p_nome  is not null and dbo.acentos(a.nome) like '%'+dbo.acentos(@p_nome)+'%'))
        and (@p_ativo is null or (@p_ativo is not null and a.ativo = @p_ativo));
end ;