create or replace procedure SP_GetLancamentoProjeto
   (p_chave     in number   default null,
    p_menu      in number   default null,
    p_restricao in varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
     -- Recupera os lan�amentos n�o cancelados e do tipo dota��o inicial de um projeto 
     open p_result for 
       select b.sigla sg_tramite
         from siw_solicitacao a,
              siw_tramite     b,
              fn_lancamento   c
        where (a.sq_siw_tramite     = b.sq_siw_tramite 
          and  'CA'                 <> nvl(b.sigla,'--'))
          and (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
          and a.sq_solic_pai = p_chave
          and a.sq_menu      = p_menu
          and c.tipo         = 1;
   Elsif p_restricao = 'LANCAMENTOS' Then
     -- Recupera os lan�amentos n�o cancelados que nao seja do tipo dota��o inicial
     open p_result for 
       select b.sigla sg_tramite
         from siw_solicitacao a,
              siw_tramite     b,
              fn_lancamento   c
        where (a.sq_siw_tramite     = b.sq_siw_tramite 
          and  'CA'                 <> nvl(b.sigla,'--'))
          and (a.sq_siw_solicitacao = c.sq_siw_solicitacao)
          and a.sq_solic_pai = p_chave
          and a.sq_menu      = p_menu
          and c.tipo         <> 1;   
   End If;
End SP_GetLancamentoProjeto;
/
