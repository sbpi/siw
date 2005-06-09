create or replace procedure SP_GetEOAAtuac
   (p_sq_pessoa   in  number,
    p_result      out siw.sys_refcursor
   ) is
begin
   --Recupera a lista de áreas de atuação
   open p_result for
      select sq_area_atuacao, nome, ativo
        from eo_area_atuacao
       where sq_pessoa = p_sq_pessoa;
end SP_GetEOAAtuac;
/

