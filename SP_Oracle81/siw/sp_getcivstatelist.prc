create or replace procedure SP_GetCivStateList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera os dados da tabela de estados civis
   open p_result for
      select sq_estado_civil, nome, sigla, ativo, codigo_externo from co_estado_civil;
end SP_GetCivStateList;
/

