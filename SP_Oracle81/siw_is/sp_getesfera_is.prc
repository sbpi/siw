create or replace procedure Sp_GetEsfera_IS
   (p_chave   in  number   default null,
    p_ativo   in  varchar2 default null,
    p_result  out siw.siw.sys_refcursor) is
begin
   -- Recupera as esferas da acao ppa do PPA
   open p_result for 
      select a.cd_esfera, a.nome, a.ativo
        from is_ppa_esfera a
       where ((p_chave is null) or (p_chave  is not null and a.cd_esfera = p_chave))
         and ((p_ativo is null) or (p_ativo  is not null and a.ativo     = p_ativo));
end Sp_GetEsfera_IS;
/

