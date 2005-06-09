create or replace procedure SP_GetRestricaoPrograma_IS
   (p_cliente   in number,
    p_ano       in number,
    p_chave     in varchar2,
    p_chave_aux in number   default null,
    p_result    out siw.siw.sys_refcursor) is
begin
   -- Recupera todas as retrições de um programa
   open p_result for 
      select a.cd_programa, a.cd_restricao_programa, a.cd_tipo_restricao, a.cd_tipo_inclusao,
             a.cd_competencia, a.inclusao, a.descricao, a.providencia, a.superacao, a.relatorio,
             a.tempo_habil, a.observacao_monitor, a.observacao_controle, b.nome nm_tp_restricao
        from is_sig_restricao_programa        a,
             is_sig_tipo_restricao b
       where (a.cd_tipo_restricao = b.cd_tipo_restricao)
         and a.cliente     = p_cliente
         and a.ano         = p_ano
         and a.cd_programa = p_chave
         and ((p_chave_aux is null) or (p_chave_aux is not null and a.cd_restricao_programa = p_chave_aux));
End SP_GetRestricaoPrograma_IS;
/

