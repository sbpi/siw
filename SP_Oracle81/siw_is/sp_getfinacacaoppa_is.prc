create or replace procedure SP_GetFinacAcaoPPA_IS
   (p_chave              in  number,
    p_cliente            in  number,
    p_ano                in  number,
    p_cd_programa        in  varchar2 default null,
    p_cd_acao            in  varchar2 default null,
    p_cd_subacao         in  varchar2 default null,
    p_result    out siw.siw.sys_refcursor) is
begin
   open p_result for 
      select distinct a.cd_acao, a.cd_programa, a.cd_subacao, c.descricao_acao, 
                      e.nome nm_programa, a.observacao, c.cd_unidade,
                      a.cd_programa||a.cd_acao||a.cd_subacao||c.cd_unidade sq_acao_ppa
        from is_acao_financ a,
             is_sig_acao     c, 
             is_ppa_acao     d, 
             is_sig_programa e 
       where (a.cd_programa        = c.cd_programa        and
              a.cd_acao            = c.cd_acao            and
              a.cd_subacao         = c.cd_subacao         and
              a.cliente            = c.cliente            and
              a.ano                = c.ano)
         and (c.cd_programa        = d.cd_programa        and
              c.cd_acao            = d.cd_acao            and
              c.cd_unidade         = d.cd_unidade         and
              c.cliente            = d.cliente            and
              c.ano                = d.ano)
         and (a.cd_programa        = e.cd_programa        and
              a.cliente            = e.cliente            and
              a.ano                = e.ano)
         and a.sq_siw_solicitacao = p_chave
         and a.cliente            = p_cliente
         and a.ano                = p_ano
         and (p_cd_programa is null or (p_cd_programa is not null and a.cd_programa = p_cd_programa))
         and (p_cd_acao     is null or (p_cd_acao     is not null and a.cd_acao     = p_cd_acao))
         and (p_cd_subacao  is null or (p_cd_subacao  is not null and a.cd_subacao  = p_cd_subacao));
end SP_GetFinacAcaoPPA_IS;
/
