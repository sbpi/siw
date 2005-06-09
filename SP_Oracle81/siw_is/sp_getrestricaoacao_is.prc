create or replace procedure SP_GetRestricaoAcao_IS
   (p_cliente   in number,
    p_ano       in number,
    p_programa  in varchar2,
    p_acao      in varchar2,
    p_subacao   in varchar2,    
    p_unidade   in varchar2,
    p_chave_aux in number   default null,
    p_result    out siw.siw.sys_refcursor) is
begin
   -- Recupera todas as retrições de uma ação
   open p_result for 
      select a.cd_programa, a.cd_acao, a.cd_subacao, a.cd_restricao_acao, a.cd_tipo_restricao, 
             a.cd_tipo_inclusao,
             a.cd_competencia, a.inclusao, a.descricao, a.providencia, a.superacao, a.relatorio,
             a.tempo_habil, a.observacao_monitor, a.observacao_controle, b.nome nm_tp_restricao
        from is_sig_restricao_acao            a,
             is_sig_tipo_restricao b,
             is_sig_acao           c
       where (a.cd_tipo_restricao = b.cd_tipo_restricao)
         and (a.cd_programa       = c.cd_programa and
              a.cd_acao           = c.cd_acao     and
              a.cd_subacao        = c.cd_subacao  and
              a.cliente           = c.cliente     and
              a.ano               = c.ano         and
              c.cd_tipo_unidade   = 'U')
         and a.cliente     = p_cliente
         and a.ano         = p_ano
         and a.cd_programa = p_programa
         and a.cd_acao     = p_acao
         and c.cd_unidade  = p_unidade
         and ((p_chave_aux is null) or (p_chave_aux is not null and a.cd_restricao_acao = p_chave_aux));
End SP_GetRestricaoAcao_IS;
/

