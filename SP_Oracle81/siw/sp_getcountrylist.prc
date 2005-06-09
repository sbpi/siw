create or replace procedure SP_GetCountryList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera os paises existentes
   open p_result for
      select sq_pais, nome, Nvl(sigla,'-') sigla, ddi,
             ativo,
             decode(ativo,'S','Sim','N�o') ativodesc,
             padrao,
             decode(padrao,'S','Sim','N�o') padraodesc
        from co_pais;
end SP_GetCountryList;
/

