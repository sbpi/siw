create or replace FUNCTION SP_PutEsquema
   (p_operacao                  varchar,
    p_cliente                   numeric,
    p_chave                     numeric,
    p_sq_modulo                 numeric,
    p_nome                      varchar,
    p_descricao                 varchar,
    p_tipo                      varchar,    
    p_ativo                     varchar,
    p_formato                   varchar,
    p_ws_servidor               varchar,
    p_ws_url                    varchar,
    p_ws_acao                   varchar,
    p_ws_mensagem               varchar,
    p_no_raiz                   varchar,
    p_bd_hostname               varchar,
    p_bd_username               varchar,
    p_bd_password               varchar,
    p_tx_delimitador            varchar,
    p_tipo_efetivacao           numeric,
    p_tx_origem_arquivos        numeric,
    p_ftp_hostname              varchar,
    p_ftp_username              varchar,
    p_ftp_password              varchar,
    p_ftp_diretorio             varchar,
    p_envia_mail                numeric,
    p_lista_mail                varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_esquema
             (sq_esquema,         cliente,       sq_modulo,   nome,      descricao,     tipo,   
              ativo,   formato,   ws_servidor,   ws_url,      ws_acao,   ws_mensagem,   no_raiz,
              bd_hostname,        bd_username,   bd_password, tx_delimitador, tipo_efetivacao,
              tx_origem_arquivos, ftp_hostname,  ftp_username,ftp_password,   ftp_diretorio,
              envia_mail,         lista_mail
             )
      (select sq_esquema.nextval, p_cliente,     p_sq_modulo, p_nome,    p_descricao,   p_tipo, 
              p_ativo, p_formato, p_ws_servidor, p_ws_url,    p_ws_acao, p_ws_mensagem, p_no_raiz,
              p_bd_hostname,      p_bd_username, p_bd_password,          p_tx_delimitador, p_tipo_efetivacao,
              p_tx_origem_arquivos, p_ftp_hostname, p_ftp_username,      p_ftp_password,p_ftp_diretorio,
              p_envia_mail,      p_lista_mail
        
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_esquema set 
         nome                  = p_nome,
         descricao             = p_descricao,
         ativo                 = p_ativo,
         formato               = p_formato,
         ws_servidor           = p_ws_servidor,
         ws_url                = p_ws_url,
         ws_mensagem           = p_ws_mensagem,
         no_raiz               = p_no_raiz,
         bd_hostname           = p_bd_hostname,
         bd_username           = p_bd_username,
         bd_password           = p_bd_password,
         tx_delimitador        = p_tx_delimitador,
         tipo_efetivacao       = p_tipo_efetivacao,
         tx_origem_arquivos    = p_tx_origem_arquivos,
         ftp_hostname          = p_ftp_hostname,
         ftp_username          = p_ftp_username,
         ftp_password          = p_ftp_password,
         ftp_diretorio         = p_ftp_diretorio,
         envia_mail            = p_envia_mail,
         lista_mail            = p_lista_mail 
       where sq_esquema        = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_esquema_atributo where sq_esquema_tabela in (select sq_esquema_tabela from dc_esquema_tabela where sq_esquema = p_chave);
      DELETE FROM dc_esquema_tabela   where sq_esquema = p_chave;
      DELETE FROM dc_esquema          where sq_esquema = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;