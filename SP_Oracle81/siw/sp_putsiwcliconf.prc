create or replace procedure SP_PutSIWCliConf
   (p_chave                    in  number,
    p_tamanho_minimo_senha     in  number   default null,
    p_tamanho_maximo_senha     in  number   default null,
    p_maximo_tentativas        in  number   default null,
    p_dias_vigencia_senha      in  number   default null,
    p_dias_aviso_expiracao     in  number   default null,
    p_smtp_server              in varchar2  default null,
    p_siw_email_nome           in varchar2  default null,
    p_siw_email_conta          in varchar2  default null,
    p_siw_email_senha          in varchar2  default null,
    p_logo                     in varchar2  default null,
    p_logo1                    in varchar2  default null,
    p_fundo                    in varchar2  default null,
    p_tipo                     in varchar2  default null,
    p_upload_maximo            in number
   ) is
begin
   If p_Tipo = 'AUTENTICACAO' Then
      -- Altera dados relativos à autenticação de usuários
      update siw_cliente set
         tamanho_min_senha    = p_tamanho_minimo_senha,
         tamanho_max_senha    = p_tamanho_maximo_senha,
         maximo_tentativas    = p_maximo_tentativas,
         dias_vig_senha       = p_dias_vigencia_senha,
         dias_aviso_expir     = p_dias_aviso_expiracao
      where sq_pessoa         = p_chave;
   Elsif p_Tipo = 'SERVIDOR' Then
      -- Altera dados relativos ao serviço de SMTP e imagens do cliente
      update siw_cliente set
         smtp_server          = p_smtp_server,
         siw_email_nome       = p_siw_email_nome,
         siw_email_conta      = p_siw_email_conta,
         siw_email_senha      = Nvl(p_siw_email_senha, siw_email_senha),
         logo                 = Nvl(p_logo, logo),
         logo1                = Nvl(p_logo1, logo1),
         fundo                = Nvl(p_fundo, fundo),
         upload_maximo        = p_upload_maximo
      where sq_pessoa         = p_chave;
   End If;
end SP_PutSIWCliConf;
/

