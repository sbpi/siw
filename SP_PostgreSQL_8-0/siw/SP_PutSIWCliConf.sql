create or replace function SP_PutSIWCliConf
   (p_chave                    numeric,
    p_tamanho_minimo_senha     numeric,
    p_tamanho_maximo_senha     numeric,
    p_maximo_tentativas        numeric,
    p_dias_vigencia_senha      numeric,
    p_dias_aviso_expiracao     numeric,
    p_smtp_server              varchar,
    p_siw_email_nome           varchar,
    p_siw_email_conta          varchar,
    p_siw_email_senha          varchar,
    p_logo                     varchar,
    p_logo1                    varchar,
    p_fundo                    varchar,
    p_tipo                     varchar,
    p_upload_maximo            numeric
   ) returns void as $$
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
         siw_email_senha      = coalesce(p_siw_email_senha, siw_email_senha),
         logo                 = coalesce(p_logo, logo),
         logo1                = coalesce(p_logo1, logo1),
         fundo                = coalesce(p_fundo, fundo),
         upload_maximo        = p_upload_maximo
      where sq_pessoa         = p_chave;
   End If;
end; $$ language 'plpgsql' volatile;