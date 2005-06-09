create or replace procedure SP_GetIdiomList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera os bancos existentes
   open p_result for
      select sq_idioma, nome, padrao, ativo,
             decode(padrao,'S','Sim','N�o') padraodesc,
             decode(ativo,'S','Sim','N�o') ativodesc
       from co_idioma;
end SP_GetIdiomList;
/

