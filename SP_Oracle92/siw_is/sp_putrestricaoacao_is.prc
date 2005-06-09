create or replace procedure SP_PutRestricaoAcao_IS
   (p_operacao            in varchar2,
    p_cliente             in number,
    p_ano                 in number,
    p_cd_programa         in varchar2  default null,
    p_cd_acao             in varchar2  default null,
    p_cd_subacao          in varchar2  default null,
    p_chave_aux           in number    default null,
    p_cd_tipo_restricao   in number,
    p_cd_tipo_inclusao    in varchar2  default null,
    p_cd_competencia      in varchar2  default null,
    p_superacao           in date      default null,
    p_relatorio           in varchar2  default null,
    p_tempo_habil         in varchar2  default null,
    p_descricao           in varchar2,
    p_providencia         in varchar2  default null,
    p_observacao_controle in varchar2  default null,
    p_observacao_monitor  in varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into is_sig_restricao_acao
                     (cd_restricao_acao,                    cliente,               ano, 
                      cd_programa,                          cd_acao,               cd_subacao,
                      cd_tipo_restricao,                    cd_tipo_inclusao,      cd_competencia,
                      inclusao,                             descricao,             providencia,
                      superacao,                            relatorio,             tempo_habil,
                      observacao_monitor,                   observacao_controle)
                     (select cd_restricao_acao.nextval,     p_cliente,             p_ano,  
                      p_cd_programa,                        p_cd_acao,             p_cd_subacao,
                      p_cd_tipo_restricao,                  p_cd_tipo_inclusao,    p_cd_competencia,
                      sysdate,                              p_descricao,           p_providencia,
                      p_superacao,                          p_relatorio,           p_tempo_habil,
                      p_observacao_monitor,                 p_observacao_controle from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update is_sig_restricao_acao set 
             cd_tipo_restricao         = p_cd_tipo_restricao,
             cd_tipo_inclusao          = p_cd_tipo_inclusao,
             cd_competencia            = p_cd_competencia,
             descricao                 = p_descricao,
             providencia               = p_providencia,
             superacao                 = p_superacao,
             relatorio                 = p_relatorio,
             tempo_habil               = p_tempo_habil,
             observacao_monitor        = p_observacao_monitor,
             observacao_controle       = p_observacao_controle
       where cd_restricao_acao = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete is_sig_restricao_acao
       where cd_restricao_acao = p_chave_aux;
   End If;
end SP_PutRestricaoAcao_IS;
/

