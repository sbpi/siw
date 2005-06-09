create or replace procedure SP_GetKindPersList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera os tipos de pessoas
   open p_result for
      select sq_tipo_pessoa, nome
        from co_tipo_pessoa
        order by nome;
end SP_GetKindPersList;
/

