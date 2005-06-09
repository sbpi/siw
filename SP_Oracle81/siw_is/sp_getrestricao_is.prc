create or replace procedure SP_GetRestricao_IS
   (p_restricao in varchar2,
    p_chave     in number,
    p_chave_aux in number   default null,
    p_result    out siw.siw.sys_refcursor) is
begin
   -- Recupera todas as retrições de uma solicitação
   If p_restricao = 'ISPRRESTR' Then
      open p_result for 
         select a.sq_restricao, a.sq_programa, a.cd_tipo_restricao, a.cd_tipo_inclusao,
                a.cd_competencia, a.inclusao, a.descricao, a.providencia, a.superacao, a.relatorio,
                a.tempo_habil, a.observacao_monitor, a.observacao_controle, b.nome nm_tp_restricao
           from is_restricao                     a,
                is_sig_tipo_restricao b 
          where (a.cd_tipo_restricao = b.cd_tipo_restricao)
            and a.sq_programa = p_chave
            and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_restricao = p_chave_aux));
   Elsif p_restricao = 'ISACRESTR' Then
      open p_result for 
         select a.sq_restricao, a.sq_acao, a.sq_projeto, a.cd_subacao, a.cd_tipo_restricao, 
                a.cd_tipo_inclusao,
                a.cd_competencia, a.inclusao, a.descricao, a.providencia, a.superacao, a.relatorio,
                a.tempo_habil, a.observacao_monitor, a.observacao_controle, b.nome nm_tp_restricao
           from is_restricao                     a,
                is_sig_tipo_restricao b,
                is_acao               c,
                is_sig_acao           d
          where (a.cd_tipo_restricao = b.cd_tipo_restricao)
            and (a.sq_acao           = c.sq_siw_solicitacao)
            and (c.cd_programa       = d.cd_programa (+) and
                 c.cd_acao           = d.cd_acao (+)     and
                 c.cd_subacao        = d.cd_subacao (+)  and
                 c.cliente           = d.cliente (+)     and
                 c.ano               = d.ano (+)         and
                 d.cd_tipo_unidade (+)   = 'U')
            and a.sq_acao = p_chave
            and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_restricao = p_chave_aux));
   End If;         
End SP_GetRestricao_IS;
/

