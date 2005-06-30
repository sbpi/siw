create or replace procedure Sp_GetAcao_IS
   (p_cd_programa  in  varchar2,
    p_cd_acao      in  varchar2,
    p_cd_unidade   in  varchar2,
    p_ano          in  number,
    p_cliente      in  number,
    p_restricao    in  varchar2 default null,
    p_sq_isprojeto in  number   default null,
    p_result  out siw.siw.sys_refcursor) is
begin
   If p_restricao is null then
      -- Verifica se a ação já foi cadastrada
      open p_result for 
         select count(*) existe 
           from is_acao a,
                siw.siw_solicitacao b, 
                siw.siw_tramite     c 
          where (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            and (b.sq_siw_tramite     = c.sq_siw_tramite and
                 'CA'                 <> Nvl(c.sigla,'-'))
            and a.cd_programa = p_cd_programa
            and a.cd_acao     = p_cd_acao
            and a.cd_unidade  = p_cd_unidade
            and a.ano         = p_ano
            and a.cliente     = p_cliente;
   Elsif p_restricao = 'RELATORIO' Then
      open p_result for 
         select b.sq_siw_solicitacao chave, e.titulo
           from is_acao a,
                is_projeto          d,
                siw.pj_projeto      e,
                siw.siw_solicitacao b,
                siw.siw_tramite     c 
          where (a.sq_isprojeto       = d.sq_isprojeto)
            and (a.sq_siw_solicitacao = e.sq_siw_solicitacao)
            and (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
            and (b.sq_siw_tramite = c.sq_siw_tramite) 
            and  ('CA'            <> Nvl(c.sigla,'-'))
            and a.sq_isprojeto    is not null
            and a.ano             = p_ano
            and a.cliente         = p_cliente
            and ((p_sq_isprojeto is null) or (p_sq_isprojeto is not null and a.sq_isprojeto = p_sq_isprojeto));
   End If;
end Sp_GetAcao_IS;
/
