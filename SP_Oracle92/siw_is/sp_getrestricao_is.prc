create or replace procedure SP_GetRestricao_IS
   (p_restricao in varchar2,
    p_chave     in number,
    p_chave_aux in number   default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera todas as retrições de uma solicitação
   If p_restricao = 'ISPRRESTR' Then
      open p_result for 
         select a.sq_restricao, a.sq_programa, a.cd_tipo_restricao, a.cd_tipo_inclusao,
                a.cd_competencia, a.inclusao, a.descricao, a.providencia, a.superacao, a.relatorio,
                a.tempo_habil, a.observacao_monitor, a.observacao_controle, b.nome nm_tp_restricao,
                to_char(a.inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_inclusao
           from is_restricao                     a
                inner join is_sig_tipo_restricao b on (a.cd_tipo_restricao = b.cd_tipo_restricao)
          where a.sq_programa = p_chave
            and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_restricao = p_chave_aux));
   Elsif p_restricao = 'ISACRESTR' Then
      open p_result for 
         select a.sq_restricao, a.sq_acao, a.sq_projeto, a.cd_subacao, a.cd_tipo_restricao, 
                a.cd_tipo_inclusao,
                a.cd_competencia, a.inclusao, a.descricao, a.providencia, a.superacao, a.relatorio,
                a.tempo_habil, a.observacao_monitor, a.observacao_controle, b.nome nm_tp_restricao,
                to_char(a.inclusao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_inclusao
           from is_restricao                     a
                inner      join is_sig_tipo_restricao b on (a.cd_tipo_restricao = b.cd_tipo_restricao)
                inner      join is_acao               c on (a.sq_acao           = c.sq_siw_solicitacao)
                left outer join is_sig_acao           d on (c.cd_programa       = d.cd_programa and
                                                       c.cd_acao           = d.cd_acao     and
                                                       c.cd_subacao        = d.cd_subacao  and
                                                       c.cliente           = d.cliente     and
                                                       c.ano               = d.ano         and
                                                       d.cd_tipo_unidade   = 'U')
          where a.sq_acao = p_chave
            and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_restricao = p_chave_aux));
   End If;         
End SP_GetRestricao_IS;
/
