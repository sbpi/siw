create or replace procedure SP_GetCivStateList
   (p_ativo     in  varchar2  default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os dados da tabela de estados civis
   open p_result for 
      select sq_estado_civil, nome, sigla, ativo, 
             codigo_externo 
        from co_estado_civil
       where (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
end SP_GetCivStateList;
/
