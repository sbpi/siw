create or replace procedure Sp_GetPPALocalizador_IS
   (p_cliente      in  number   default null,
    p_ano          in  number   default null,
    p_cd_programa  in  varchar2 default null,
    p_cd_acao      in  varchar2 default null,
    p_cd_unidade   in  varchar2 default null,
    p_cd_subacao   in  varchar2 default null,
    p_result  out sys_refcursor) is
begin
   -- Recupera os localizadores de uma determinada ação
   open p_result for 
      select a.nome, b.cd_subacao
        from is_ppa_localizador     a
             inner join is_sig_acao b on (a.cd_programa    = b.cd_programa    and
                                          a.cd_acao_ppa    = b.cd_acao_ppa    and
                                          a.cd_localizador = b.cd_localizador and
                                          a.cliente        = b.cliente        and
                                          a.ano            = b.ano)
       where a.cliente     = p_cliente
         and a.ano         = p_ano
         and b.cd_programa = p_cd_programa
         and b.cd_acao     = p_cd_acao
         and b.cd_unidade  = p_cd_unidade
         and (p_cd_subacao is null or (p_cd_subacao is not null and b.cd_subacao = p_cd_subacao));         
end Sp_GetPPALocalizador_IS;
/
