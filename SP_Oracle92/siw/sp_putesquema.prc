create or replace procedure SP_PutEsquema
   (p_operacao                 in  varchar2,
    p_cliente                  in  number,
    p_chave                    in  number,
    p_sq_modulo                in  number,
    p_nome                     in  varchar2,
    p_descricao                in  varchar2  default null,
    p_tipo                     in  varchar2  default null,    
    p_ativo                    in  varchar2  default null,
    p_formato                  in  varchar2  default null,
    p_ws_servidor              in  varchar2  default null,
    p_ws_url                   in  varchar2  default null,
    p_ws_acao                  in  varchar2  default null,
    p_ws_mensagem              in  varchar2  default null,
    p_no_raiz                  in  varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into dc_esquema
             (sq_esquema,         cliente,       sq_modulo,   nome,      descricao,     tipo,   
              ativo,   formato,   ws_servidor,   ws_url,      ws_acao,   ws_mensagem,   no_raiz         
             )
      (select sq_esquema.nextval, p_cliente,     p_sq_modulo, p_nome,    p_descricao,   p_tipo, 
              p_ativo, p_formato, p_ws_servidor, p_ws_url,    p_ws_acao, p_ws_mensagem, p_no_raiz
         from dual
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
         no_raiz               = p_no_raiz
       where sq_esquema        = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete dc_esquema_atributo where sq_esquema_tabela in (select sq_esquema_tabela from dc_esquema_tabela where sq_esquema = p_chave);
      delete dc_esquema_tabela   where sq_esquema = p_chave;
      delete dc_esquema          where sq_esquema = p_chave;
   End If;
end SP_PutEsquema;
/

