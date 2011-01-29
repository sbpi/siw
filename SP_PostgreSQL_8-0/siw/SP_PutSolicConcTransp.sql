create or replace FUNCTION SP_PutSolicConcTransp
   (p_menu                numeric,
    p_chave               numeric,
    p_pessoa              numeric,
    p_recebedor           numeric,
    p_tramite             numeric,
    p_executor            numeric,
    p_nota_conclusao      varchar,
    p_valor               numeric,
    p_caminho             varchar,
    p_tamanho             numeric,
    p_tipo                varchar,
    p_nome_original       varchar,
    p_sq_veiculo          numeric,
    p_hodometro_saida     numeric,
    p_hodometro_chegada   numeric,
    p_horario_saida       varchar,
    p_horario_chegada     varchar,
    p_parcial             varchar        
    
   ) RETURNS VOID AS $$
DECLARE
   w_chave_dem     numeric(18) := null;
   w_chave_arq     numeric(18) := null;
BEGIN
   -- Recupera a chave do log
   select nextVal('sq_siw_solic_log') into w_chave_dem;
   
   -- Insere registro na tabela de log da solicitacao
   Insert Into siw_solic_log 
      (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
       sq_siw_tramite,            data,               devolucao, 
       observacao
      )
   Values
      (w_chave_dem,               p_chave,            p_pessoa,
       p_tramite,                 now(),            'N',
       'Conclusão da solicitação');
       
   -- Atualiza a situação da solicitação
   Update siw_solicitacao set
      conclusao      = to_date(p_horario_chegada,'dd/mm/yyyy, hh24:mi'), 
      recebedor      = p_recebedor,
      executor       = p_executor,
      sq_siw_tramite = (select sq_siw_tramite 
                          from siw_tramite 
                         where sq_menu=p_menu 
                           and Nvl(sigla,'z')='AT'
                       )
   Where sq_siw_solicitacao = p_chave;
   
   -- Atualiza a tabela de sr_solicitações_transporte
   Update sr_solicitacao_transporte set
       sq_veiculo        = p_sq_veiculo, 
       hodometro_saida   = p_hodometro_saida,
       hodometro_chegada = p_hodometro_chegada,
       horario_saida     = to_date(p_horario_saida,'dd/mm/yyyy, hh24:mi'),
       horario_chegada   = to_date(p_horario_chegada,'dd/mm/yyyy, hh24:mi'),
       parcial           = p_parcial
   where sq_siw_solicitacao = p_chave;   

   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select nextVal('sq_siw_arquivo') into w_chave_arq;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, now(), 
              p_tamanho,   p_tipo,        p_caminho, p_nome_original
         from co_pessoa a
        where a.sq_pessoa = p_pessoa
      );
      
      -- Insere registro em SIW_SOLIC_LOG_ARQ
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
      values (w_chave_dem, w_chave_arq);
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;