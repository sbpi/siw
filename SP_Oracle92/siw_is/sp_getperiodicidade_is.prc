create or replace procedure Sp_GetPeriodicidade_IS
   (p_chave   in  number   default null,
    p_ativo   in  varchar2 default null,
    p_result  out sys_refcursor) is
begin
   -- Recupera todas as periodicidades
   open p_result for 
      select a.cd_periodicidade chave, a.nome, a.ativo
        from is_sig_periodicidade a
       where ((p_chave   is null) or (p_chave   is not null and a.cd_periodicidade = p_chave))
         and ((p_ativo   is null) or (p_ativo   is not null and a.ativo            = p_ativo));
end Sp_GetPeriodicidade_IS;
/

