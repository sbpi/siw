create or replace FUNCTION sp_putBaseLine
   (p_cliente             numeric,
    p_chave               numeric,
    p_pessoa              numeric,
    p_tramite             numeric,
    p_caminho             varchar,
    p_tamanho             numeric,
    p_tipo                varchar,
    p_nome                varchar  
   ) RETURNS VOID AS $$
DECLARE
   w_chave_log numeric(18);
   w_chave_arq numeric(18);
BEGIN   
   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select sq_siw_solic_log.nextval into w_chave_log from dual;

      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (select 
          w_chave_log,        p_chave,            p_pessoa,
          p_tramite,          now(),            'N',
          '*** Nova versão'
         from dual
      );
      
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave_arq from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select w_chave_arq, p_cliente, 'Arquivo de dados', null, now(), 
              p_tamanho,   p_tipo,    p_caminho,          p_nome
        from dual
      );
      -- Insere registro em siw_solic_log_arq
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
      values (w_chave_log, w_chave_arq);
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;