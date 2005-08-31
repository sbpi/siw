create or replace procedure SP_GetDefList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera as defici�ncias existentes
   open p_result for
      select a.sq_deficiencia, a.nome, a.ativo,
             decode(a.ativo,'S','Sim','N�o') ativodesc,
             a.codigo, Nvl(a.descricao,'-') descricao, b.nome sq_grupo_defic
        from co_deficiencia a, co_grupo_defic b
      where a.sq_grupo_defic = b.sq_grupo_defic;
end SP_GetDefList;
/
