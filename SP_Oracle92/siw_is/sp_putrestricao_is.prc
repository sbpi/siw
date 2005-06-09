create or replace procedure SP_PutRestricao_IS
   (p_operacao            in varchar2,
    p_restricao           in varchar2,
    p_chave               in number,
    p_chave_aux           in number    default null,
    p_cd_subacao          in varchar2  default null,
    p_sq_isprojeto        in number    default null,
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
   If p_restricao = 'ISPRRESTR' Then
      If p_operacao = 'I' Then
         -- Insere registro
         insert into is_restricao
                     (sq_restricao,                         sq_programa,           
                      cd_tipo_restricao,                    cd_tipo_inclusao,      cd_competencia,
                      inclusao,                             descricao,             providencia,
                      superacao,                            relatorio,             tempo_habil,
                      observacao_monitor,                   observacao_controle)
                     (select sq_restricao.nextval,          p_chave,         
                      p_cd_tipo_restricao,                  p_cd_tipo_inclusao,    p_cd_competencia,
                      sysdate,                              p_descricao,           p_providencia,
                      p_superacao,                          p_relatorio,           p_tempo_habil,
                      p_observacao_monitor,                 p_observacao_controle from dual);
                      
      Elsif p_operacao = 'A' Then
         -- Altera registro
         update is_restricao set 
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
          where sq_restricao = p_chave_aux;
          
      Elsif p_operacao = 'E' Then
         -- Exclui registro
         delete is_restricao
          where sq_restricao = p_chave_aux;
      End If;
   Elsif p_restricao = 'ISACRESTR' Then
      If p_operacao = 'I' Then
         -- Insere registro
         insert into is_restricao
                    (sq_restricao,                         sq_acao,               sq_projeto,
                     cd_tipo_restricao,                    cd_tipo_inclusao,      cd_competencia,
                     inclusao,                             descricao,             providencia,
                     superacao,                            relatorio,             tempo_habil,
                     observacao_monitor,                   observacao_controle,   cd_subacao)
                    (select sq_restricao.nextval,          p_chave,               p_sq_isprojeto,
                     p_cd_tipo_restricao,                  p_cd_tipo_inclusao,    p_cd_competencia,
                     sysdate,                              p_descricao,           p_providencia,
                     p_superacao,                          p_relatorio,           p_tempo_habil,
                     p_observacao_monitor,                 p_observacao_controle, p_cd_subacao from dual);
      Elsif p_operacao = 'A' Then
         -- Altera registro
         update is_restricao set 
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
          where sq_restricao = p_chave_aux;
      Elsif p_operacao = 'E' Then
         -- Exclui registro
         delete is_restricao
          where sq_restricao = p_chave_aux;
      End If;
   End If;
end SP_PutRestricao_IS;
/

