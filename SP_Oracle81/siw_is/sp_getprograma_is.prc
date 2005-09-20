create or replace procedure Sp_GetPrograma_IS
   (p_cd_programa in  varchar2,
    p_ano         in  number,
    p_cliente     in  number,
    p_restricao   in  varchar2,
    p_result  out siw.siw.sys_refcursor) is
begin
   -- Verifica se o programa já foi cadastrado e não está cancelado
   If p_restricao is null Then
      open p_result for 
         select count(*) existe 
           from is_programa a,
                siw.siw_solicitacao b,
                siw.siw_tramite     c
          where (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            and (b.sq_siw_tramite     = c.sq_siw_tramite and
                 'CA'                 <> Nvl(c.sigla,'-'))
            and a.cd_programa = p_cd_programa
            and a.ano         = p_ano
            and a.cliente     = p_cliente;
   Elsif p_restricao = 'CADASTRADOS' Then
      open p_result for 
         select b.sq_siw_solicitacao chave, e.titulo
           from is_programa a,
                siw.siw_solicitacao b,
                siw.siw_tramite     c,
                siw.pj_projeto      e
          where (a.sq_siw_solicitacao = e.sq_siw_solicitacao)
            and (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            and (b.sq_siw_tramite = c.sq_siw_tramite) 
            and  ('CA'            <> Nvl(c.sigla,'-'))
            and a.ano             = p_ano
            and a.cliente         = p_cliente;
   End If;
end Sp_GetPrograma_IS;
/
