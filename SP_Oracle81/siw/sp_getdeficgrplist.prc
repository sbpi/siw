create or replace procedure SP_GetDeficGrpList
   (p_nome      in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os grupos de deficiências existentes
   open p_result for
      select sq_grupo_defic, nome, ativo,
             decode(ativo,'S','Sim','Não') ativodesc
        from co_grupo_defic
       where (p_nome  is null or (p_nome  is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo))        
      order by nome;
end SP_GetDeficGrpList;
/
