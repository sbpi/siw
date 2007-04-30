create or replace procedure SP_GetCronograma
   (p_chave             in number default null,
    p_chave_aux         in number default null,
    p_inicio            in date   default null,
    p_fim               in date   default null,    
    p_result    out sys_refcursor) is
begin
   -- Recupera o cronograma da rubrica
   open p_result for 
         select a.sq_rubrica_cronograma, a.inicio, a.fim, a.valor_previsto, a.valor_real
           from pj_rubrica_cronograma a
      where p_chave = p_chave      
        and ((p_chave_aux is null) or (p_chave_aux  is not null and a.sq_rubrica_cronograma = p_chave_aux));
end SP_GetCronograma;
/
