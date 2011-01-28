create or replace FUNCTION SP_PutAcordoEnvio
   (p_menu                numeric,
    p_chave               numeric,
    p_pessoa              numeric,
    p_tramite             numeric,
    p_novo_tramite        numeric,
    p_devolucao           varchar,
    p_tipo_log            numeric,
    p_observacao          varchar,
    p_destinatario        numeric,
    p_despacho            varchar,
    p_caminho             varchar,
    p_tamanho             numeric,
    p_tipo                varchar,
    p_nome                varchar  
   ) RETURNS VOID AS $$
DECLARE
   w_reg           numeric(18) := null;
   w_chave         numeric(18) := null;
   w_chave_dem     numeric(18) := null;
   w_chave_arq     numeric(18) := null;
BEGIN
   If p_tramite <> p_novo_tramite Then
      -- Recupera a próxima chave
      select sq_siw_solic_log.nextval into w_chave;
      
      -- Se houve mudança de fase, grava o log
      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (Select 
          w_chave,                   p_chave,            p_pessoa,
          p_tramite,                 now(),            p_devolucao,
          'Envio da fase "'||a.nome||'" '||
          ' para a fase "'||b.nome||'".'
         from siw_tramite a,
              siw_tramite b
        where a.sq_siw_tramite = p_tramite
          and b.sq_siw_tramite = p_novo_tramite
      );

      -- Atualiza a situação da demanda
      Update siw_solicitacao set
         sq_siw_tramite = p_novo_tramite
      Where sq_siw_solicitacao = p_chave;
   End If;

   -- Verifica se o envio é na/para fase de cadastramento. Se for, atualiza o cadastrador.
   If p_destinatario is  not null Then

      -- Atualiza o responsável atual pela demanda
      Update siw_solicitacao set conclusao = null, executor = p_destinatario Where sq_siw_solicitacao = p_chave;

      select count(*) into w_reg from siw_tramite where sq_siw_tramite = Nvl(p_novo_tramite,p_tramite) and sigla='CI';
      If w_reg > 0 Then
         Update siw_solicitacao set cadastrador = p_destinatario Where sq_siw_solicitacao = p_chave;
      End If;
   End If;

   -- Recupera a nova chave da tabela de encaminhamentos da demanda
   select sq_acordo_log.nextval into w_chave_dem;
   
   -- Insere registro na tabela de encaminhamentos da demanda
   Insert into ac_acordo_log 
      (sq_acordo_log,             sq_siw_solicitacao, cadastrador, 
       destinatario,              data_inclusao,      observacao, 
       despacho,                  sq_siw_solic_log,   sq_tipo_log
      )
   Values (
       w_chave_dem,               p_chave,            p_pessoa,
       p_destinatario,            now(),            p_observacao,
       p_despacho,                w_chave,            p_tipo_log
    );

   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave_arq;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, now(), 
              p_tamanho,   p_tipo,        p_caminho,           p_nome
         from co_pessoa a
        where a.sq_pessoa = p_pessoa
      );
      
      -- Decide se o vínculo do arquivo será com o log da solicitação ou da demanda.
      If p_tramite <> p_novo_tramite Then
         -- Insere registro em SIW_SOLIC_LOG_ARQ
         insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
         values (w_chave, w_chave_arq);
      Else
         -- Insere registro em AC_ACORDO_LOG_ARQ
         insert into ac_acordo_log_arq (sq_acordo_log, sq_siw_arquivo)
         values (w_chave_dem, w_chave_arq);
      End If;
   End If;

   commit;
      END; $$ LANGUAGE 'PLPGSQL' VOLATILE;