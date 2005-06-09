create or replace procedure Sp_GetBaseGeografica_IS
   (p_chave   in  number   default null,
    p_ativo   in  varchar2 default null,
    p_result  out siw.siw.sys_refcursor) is
begin
   -- Recupera todas as bases geográficas
   open p_result for 
      select a.cd_base_geografica chave, a.nome, a.ativo
        from is_sig_base_geografica a
       where ((p_chave   is null) or (p_chave   is not null and a.cd_base_geografica = p_chave))
         and ((p_ativo   is null) or (p_ativo   is not null and a.ativo              = p_ativo));
end Sp_GetBaseGeografica_IS;
/

