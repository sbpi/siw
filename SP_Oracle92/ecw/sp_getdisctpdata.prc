create or replace procedure SP_GetDiscTPData
   (p_co_tipo_disciplina in  number,
    p_result             out sys_refcursor
   ) is
begin
   -- Recupera os dados do tipo da disciplina
   open p_result for
      select * from s_tipo_disciplina where co_tipo_disciplina = p_co_tipo_disciplina;
end SP_GetDiscTPData;
/

