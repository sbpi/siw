create or replace procedure SP_GetModList
   (p_result      out siw.sys_refcursor
   ) is
begin
   --Recupera a lista de m�dulos
   open p_result for
      select sq_modulo, nome, objetivo_geral, sigla
        from siw_modulo;
end SP_GetModList;
/

