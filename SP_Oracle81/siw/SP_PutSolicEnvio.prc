create or replace procedure SP_PutSolicEnvio
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_novo_tramite        in number    default null,    
    p_devolucao           in varchar2,
    p_despacho            in varchar2,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar2  default null
   ) is
   w_chave         number(18) := null;
   w_chave_arq     number(18) := null;
   w_tramite       number(18);
   w_sg_tramite    varchar2(2);
begin
   -- Recupera a próxima chave
   select sq_siw_solic_log.nextval into w_chave from dual;
   
   -- Se houve mudança no trâmite atual, recupera o trâmite para o qual está sendo enviada a solicitação
   If p_tramite <> nvl(p_novo_tramite, 0) Then
      If p_devolucao = 'N' Then
         select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
            from siw_tramite a
           where a.sq_menu = p_menu
             and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = p_tramite);
      Else
         select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
            from siw_tramite a
           where a.sq_siw_tramite = p_novo_tramite;
      End If;
   Else
      w_tramite := p_tramite;
   End If;
   
   -- Recupera a próxima chave
   select sq_siw_solic_log.nextval into w_chave from dual;
    
   -- Se houve mudança de fase, grava o log
   Insert Into siw_solic_log 
       (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
        sq_siw_tramite,            data,               devolucao, 
        observacao
       )
   (Select 
        w_chave,                   p_chave,            p_pessoa,
        p_tramite,                 sysdate,            p_devolucao,
        decode(p_tramite,
               p_novo_tramite, 'Anotação: '||chr(13)||chr(10)||p_despacho,
               decode(p_devolucao, 'S', 'Devolução da fase "', 'Envio da fase "')||a.nome||'" '||' para a fase "'||b.nome||'".'||
               decode(p_devolucao, 'S', chr(13)||chr(10)||'Despacho: '||chr(13)||chr(10)||p_despacho, ''))
       from siw_tramite a,
            siw_tramite b
      where a.sq_siw_tramite = p_tramite
        and b.sq_siw_tramite = w_tramite
   );

   Update siw_solicitacao set
      sq_siw_tramite        = w_tramite,
      conclusao             = null,
      executor              = null,
      observacao            = null,
      valor                 = null,
      opiniao               = null
   Where sq_siw_solicitacao = p_chave;

   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave_arq from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, sysdate, 
              p_tamanho,   p_tipo,        p_caminho, p_nome_original
         from co_pessoa a
        where a.sq_pessoa = p_pessoa
      );
      
      -- Insere registro em SIW_SOLIC_LOG_ARQ
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo) values (w_chave, w_chave_arq);
   End If;

   commit;
      
end SP_PutSolicEnvio;
/
