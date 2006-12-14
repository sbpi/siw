create or replace procedure sp_PutXMLRestricaoAcao_SIG
   (p_cliente             in number   default null,
    p_ano                 in number   default null,
    p_cd_programa         in varchar2 default null,
    p_cd_acao             in varchar2 default null,
    p_cd_subacao          in varchar2 default null,
    p_cd_tipo_restricao   in number   default null,
    p_cd_restricao_acao   in number   default null,
    p_cd_tipo_inclusao    in varchar2 default null,
    p_cd_competencia      in varchar2 default null,
    p_inclusao            in varchar2 default null,
    p_descricao           in varchar2 default null,
    p_providencia         in varchar2 default null,
    p_relatorio           in varchar2 default null,
    p_tempo_habil         in varchar2 default null,
    p_observacao_monitor  in varchar2 default null,
    p_observacao_controle in varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);

begin
   select count(*) into w_cont 
     from is_sig_restricao_acao a 
    where a.cd_restricao_acao = p_cd_restricao_acao 
      and a.cd_programa       = p_cd_programa
      and a.cliente           = p_cliente
      and a.ano               = p_ano;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
      
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_sig_restricao_acao (cliente, ano, cd_programa, cd_acao, cd_subacao, cd_tipo_restricao, cd_restricao_acao,
                                         cd_tipo_inclusao, cd_competencia, inclusao, descricao, providencia, relatorio, tempo_habil,
                                         observacao_monitor, observacao_controle, flag_inclusao, flag_alteracao)
      values (p_cliente, p_ano, p_cd_programa, p_cd_acao, p_cd_subacao, p_cd_tipo_restricao, p_cd_restricao_acao, 
              p_cd_tipo_inclusao, p_cd_competencia, to_date(p_inclusao,'yyyy-mm-dd hh24:mi:ss'), p_descricao, p_providencia, p_relatorio, p_tempo_habil,
              p_observacao_monitor, p_observacao_controle, sysdate, sysdate);
   Else
      -- Altera registro
      update is_sig_restricao_acao set
         cd_tipo_restricao   = p_cd_tipo_restricao,
         cd_tipo_inclusao    = p_cd_tipo_inclusao,
         cd_competencia      = p_cd_competencia,
         inclusao            = to_date(p_inclusao,'yyyy-mm-dd hh24:mi:ss'),
         descricao           = p_descricao,
         providencia         = p_providencia,
         relatorio           = p_relatorio,
         tempo_habil         = p_tempo_habil,
         observacao_monitor  = p_observacao_monitor,
         observacao_controle = p_observacao_controle,
         flag_alteracao     = sysdate
       where cd_restricao_acao = p_cd_restricao_acao
         and cd_programa       = p_cd_programa
         and cliente           = p_cliente
         and ano               = p_ano;
   End If;
end sp_PutXMLRestricaoAcao_SIG;
/
