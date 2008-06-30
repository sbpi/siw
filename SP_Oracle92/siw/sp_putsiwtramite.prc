create or replace procedure SP_PutSIWTramite
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_chave_aux           in  number,
    p_nome                in  varchar2 default null,
    p_ordem               in  number   default null,
    p_sigla               in  varchar2 default null,
    p_descricao           in  varchar2 default null,
    p_chefia_imediata     in  varchar2 default null,
    p_ativo               in  varchar2 default null,
    p_solicita_cc         in  varchar2 default null,
    p_envia_mail          in  varchar2 default null,
    p_destinatario        in  varchar2 default null
   ) is
   w_chave  number(18);
begin
   If p_operacao = 'I' Then
      -- Recupera a próxima chave
      select sq_siw_tramite.nextval into w_Chave from dual;
      
      -- Insere registro em SIW_MENU
      insert into siw_tramite 
         (sq_siw_tramite,     sq_menu,           nome,     ordem,         sigla, 
          descricao,          chefia_imediata,   ativo,    solicita_cc,   envia_mail,
          destinatario)  
      values 
         (w_Chave,            p_chave_aux,       p_nome,   p_ordem,       upper(p_sigla),
          p_descricao,        p_chefia_imediata, p_ativo,  p_solicita_cc, p_envia_mail,
          p_destinatario
         );
      
      -- Cria a opção do menu para todos os endereços da organização
      insert into siw_menu_endereco (sq_menu, sq_pessoa_endereco) 
        (select w_chave, sq_pessoa_endereco 
           from co_pessoa_endereco a, co_tipo_endereco b 
          where a.sq_tipo_endereco = b.sq_tipo_endereco 
            and b.internet         = 'N' 
            and b.email            = 'N' 
            and sq_pessoa          = p_chave_aux
        );
      
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_tramite set
          nome             = trim(p_nome),
          ordem            = p_ordem,
          chefia_imediata  = p_chefia_imediata,
          envia_mail       = p_envia_mail,
          solicita_cc      = p_solicita_cc,
          sigla            = upper(p_sigla),
          descricao        = trim(p_descricao),
          ativo            = p_ativo,
          destinatario     = p_destinatario
      where sq_siw_tramite = p_chave;
   Elsif p_operacao = 'E' Then
      -- Remove o fluxo do trâmite
      delete siw_tramite_fluxo where sq_siw_tramite_origem = p_chave or sq_siw_tramite_destino = p_chave;

      -- Remove o trâmite do serviço
      delete siw_tramite where sq_siw_tramite = p_chave;
   End If;
end SP_PutSIWTramite;
/
