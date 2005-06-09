create or replace procedure SP_GetCountryList
   (p_result    out sys_refcursor) is
begin
   -- Recupera os paises existentes
   open p_result for 
      select sq_pais, nome, Nvl(sigla,'-') sigla, ddi,
             ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end ativodesc, 
             padrao, 
             case padrao when 'S' then 'Sim' else 'Não' end padraodesc
        from co_pais; 
end SP_GetCountryList;
/

