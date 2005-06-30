create or replace procedure Sp_GetAcao_IS
   (p_cd_programa  in  varchar2 default null,
    p_cd_acao      in  varchar2 default null,
    p_cd_unidade   in  varchar2 default null,
    p_ano          in  number,
    p_cliente      in  number,
    p_restricao    in  varchar2 default null,
    p_sq_isprojeto in  number   default null,
    p_result  out sys_refcursor) is
begin
   If p_restricao is null then
      -- Verifica se a ação já foi cadastrada
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
   Elsif p_restricao = 'RELATORIO' Then
      open p_result for 
         select b.sq_siw_solicitacao chave, e.titulo
           from is_acao a
                inner join is_projeto          d on (a.sq_isprojeto       = d.sq_isprojeto)
                inner join siw.pj_projeto      e on (a.sq_siw_solicitacao = e.sq_siw_solicitacao)
                inner join siw.siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                inner join siw.siw_tramite     c on (b.sq_siw_tramite     = c.sq_siw_tramite and
                                                     'CA'                 <> Nvl(c.sigla,'-'))
          where a.sq_isprojeto   is not null
            and a.ano            = p_ano
            and a.cliente        = p_cliente
            and ((p_sq_isprojeto is null) or (p_sq_isprojeto is not null and a.sq_isprojeto = p_sq_isprojeto));

   End If;
end Sp_GetAcao_IS;
/
