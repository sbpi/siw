create or replace procedure Sp_GetAcao_IS
   (p_cd_programa in  varchar2,
    p_cd_acao     in  varchar2,
    p_cd_unidade  in  varchar2,
    p_ano         in  number,
    p_cliente     in  number,
    p_result  out sys_refcursor) is
begin
   -- Verifica se a a��o j� foi cadastrada
   open p_result for 
      select count(*) existe 
        from is_acao a
             inner join siw.siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
             inner join siw.siw_tramite     c on (b.sq_siw_tramite     = c.sq_siw_tramite and
                                                  'CA'                 <> Nvl(c.sigla,'-'))
       where a.cd_programa = p_cd_programa
         and a.cd_acao     = p_cd_acao
         and a.cd_unidade  = p_cd_unidade
         and a.ano         = p_ano
         and a.cliente     = p_cliente;
end Sp_GetAcao_IS;
/

