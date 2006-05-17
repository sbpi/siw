create or replace procedure SP_GetDefList
   (p_nome      in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera as deficiências existentes
   open p_result for
      select a.sq_deficiencia, a.nome, a.ativo,
             decode(a.ativo,'S','Sim','Não') ativodesc,
             a.codigo, Nvl(a.descricao,'-') descricao, b.nome sq_grupo_deficiencia, b.sq_grupo_defic
        from co_deficiencia a, co_grupo_defic b
      where a.sq_grupo_defic = b.sq_grupo_defic
        and (p_nome  is null or (p_nome  is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
        and (p_ativo is null or (p_ativo is not null and a.ativo = p_ativo));      
end SP_GetDefList;
/
