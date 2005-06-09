create or replace procedure SP_GetMetaMensal_IS
   (p_chave     in number   default null,
    p_result    out siw.siw.sys_refcursor) is
begin
   open p_result for
      select a.referencia, a.revisado, a.realizado
        from is_meta_execucao a 
       where a.sq_meta = p_chave;
End SP_GetMetaMensal_IS;
/

