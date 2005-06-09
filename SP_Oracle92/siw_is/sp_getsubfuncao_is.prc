create or replace procedure Sp_GetSubFuncao_IS
   (p_chave   in  varchar2 default null,
    p_funcao  in  varchar2 default null,
    p_result  out sys_refcursor) is
begin
   -- Recupera as subfuncoes da acao ppa do PPA
   open p_result for 
      select a.cd_subfuncao, a.cd_funcao, a.descricao
        from is_ppa_subfuncao a
       where ((p_chave  is null) or (p_chave  is not null and a.cd_funcao = p_chave))
         and ((p_funcao is null) or (p_funcao is not null and a.cd_funcao = p_funcao));
end Sp_GetSubFuncao_IS;
/

