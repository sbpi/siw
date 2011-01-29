create or replace FUNCTION SP_PutSolicTransp
   (p_operacao             varchar,
    p_chave                numeric,
    p_copia                numeric,
    p_menu                 numeric,
    p_unidade              numeric,
    p_solicitante          numeric,
    p_cadastrador          numeric,
    p_descricao            varchar,
    p_justificativa        varchar,
    p_inicio               varchar,
    p_fim                  varchar,
    p_data_hora            varchar,
    p_cidade               numeric,
    p_destino              varchar,
    p_sq_veiculo           numeric,
    p_qtd_pessoas          numeric,
    p_procedimento         numeric,
    p_carga                varchar,    
    p_chave_nova          numeric
   ) RETURNS VOID AS $$
DECLARE
   w_arq     varchar(4000) := ', ';
   w_chave   numeric(18);
   w_log_sol numeric(18);
   w_inicio  date := null;
   w_fim     date := null;

    c_arquivos CURSOR FOR
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
BEGIN
   -- Transforma a data de início do tipo varchar para o tipo date
   If p_inicio is not null Then
      If length(p_inicio)=10 
         Then w_inicio := to_date(p_inicio,'dd/mm/yyyy'); 
         Else w_inicio := to_date(p_inicio,'dd/mm/yyyy, hh24:mi:ss'); 
      End If;
   End If;

   -- Transforma a data de término do tipo varchar para o tipo date
   If p_fim is not null Then
      If length(p_fim)=10 
         Then w_fim := to_date(p_fim,'dd/mm/yyyy'); 
         Else w_fim := to_date(p_fim,'dd/mm/yyyy, hh24:mi:ss'); 
      End If;
   End If;

   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select nextVal('sq_siw_solicitacao') into w_Chave;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,            sq_siw_tramite,      solicitante, 
         cadastrador,        descricao,          justificativa,       inicio,
         fim,                inclusao,           ultima_alteracao,    data_hora,
         sq_unidade,         sq_cidade_origem    )
      (select 
         w_Chave,            p_menu,             a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_descricao,        p_justificativa,     w_inicio,
         w_fim,              now(),            now(),             p_data_hora,
         p_unidade,          p_cidade
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      -- Insere registro em SR_SOLICITACAO_TRANSPORTE
      insert into sr_solicitacao_transporte
        (sq_siw_solicitacao,   sq_veiculo,          qtd_pessoas,         procedimento,   carga,       destino)
      values (
         w_Chave,              p_sq_veiculo,        p_qtd_pessoas,       p_procedimento, p_carga,     p_destino);          
      
      -- Insere log da solicitação
      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (select 
          nextVal('sq_siw_solic_log'),  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          now(),            'N',
          'Cadastramento inicial'
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
           
      -- Se a solicitação foi copiada de outra, grava os dados complementares
      If p_copia is not null Then
         -- Bloco reservado para futuras necessidades
         null;
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          solicitante      = p_solicitante,
          descricao        = trim(p_descricao), 
          justificativa    = trim(p_justificativa),
          inicio           = w_inicio,
          fim              = w_fim,
          ultima_alteracao = now()
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de sr_solicitações_transporte
      Update sr_solicitacao_transporte set
          destino           = p_destino,
          sq_veiculo        = p_sq_veiculo, 
          qtd_pessoas       = p_qtd_pessoas,
          procedimento      = p_procedimento,
          carga             = p_carga
      where sq_siw_solicitacao = p_chave;
      
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      
      -- Se não foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If w_log_sol > 1 Then
         -- Insere log de cancelamento
         Insert Into siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
             sq_siw_tramite,            data,                 devolucao, 
             observacao
            )
         (select 
             nextVal('sq_siw_solic_log'),  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          now(),              'N',
             'Cancelamento'
            from siw_solicitacao a
           where a.sq_siw_solicitacao = p_chave
         );
         
         -- Recupera a chave que indica que a solicitação está cancelada
         select a.sq_siw_tramite into w_chave from siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';
         
         -- Atualiza a situação da solicitação
         update siw_solicitacao set sq_siw_tramite = w_chave where sq_siw_solicitacao = p_chave;
      Else
         -- Monta string com a chave dos arquivos ligados à solicitação informada
         for crec in c_arquivos loop
            w_arq := w_arq || crec.sq_siw_arquivo;
         end loop;
         w_arq := substr(w_arq, 3, length(w_arq));

         -- Remove os registros vinculados à demanda
         DELETE FROM siw_solic_arquivo where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_arquivo       where sq_siw_arquivo     in (w_arq);
         
         -- Remove o log da solicitação
         DELETE FROM siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações de transporte
         DELETE FROM sr_solicitacao_transporte where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         DELETE FROM siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;