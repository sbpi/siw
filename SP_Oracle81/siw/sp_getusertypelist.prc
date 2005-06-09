create or replace procedure SP_GetUserTypeList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera o tipos de pessoas existentes
   open p_result for
      select sq_tipo_pessoa, nome, padrao,
             decode(padrao,'S','Sim','N�o') padraodesc,
             ativo,
             decode(ativo,'S','Sim','N�o') ativodesc
        from co_tipo_pessoa;
end SP_GetUserTypeList;
/

