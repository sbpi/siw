create or replace procedure SP_GetMetaMensal_IS
   (p_chave     in number   default null,
    p_result    out sys_refcursor) is
begin
   open p_result for
      select a.referencia, a.revisado, a.realizado,
              to_char(a.referencia, 'DD/MM/YYYY, HH24:MI:SS') phpdt_referencia
        from is_meta_execucao a 
       where a.sq_meta = p_chave;
End SP_GetMetaMensal_IS;
/
