create or replace procedure SP_GetPeriodoList
   (p_result     out sys_refcursor
   ) is
begin
   -- Recupera os períodos disponíveis
   open p_result for
      select distinct ano_sem,
             case trim(a.tp_ano_letivo)
                  when 'A' then substr(ano_sem,1,4)
                  else          substr(ano_sem,1,4)||' - Semestre '||substr(ano_sem,5,1)
                  end periodo
         from s_periodounidade a;
end SP_GetPeriodoList;
/

