create or replace procedure SP_GetFormatData
   (p_sq_formacao in  number,
    p_result      out siw.sys_refcursor
   ) is
begin
   -- Recupera os dados da Formação
   open p_result for
      select * from co_formacao where sq_formacao = p_sq_formacao;
end SP_GetFormatData;
/

