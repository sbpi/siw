create or replace FUNCTION SP_PutDemandaGeral
   (p_operacao             varchar,
    p_chave                numeric,
    p_copia                numeric,
    p_menu                numeric,
    p_unidade             numeric,
    p_solicitante         numeric,
    p_proponente          varchar,
    p_cadastrador         numeric,
    p_executor            numeric,
    p_sqcc                numeric,
    p_descricao           varchar,
    p_justificativa       varchar,
    p_ordem               numeric,
    p_inicio              date,
    p_fim                 date,
    p_valor               numeric,
    p_data_hora           varchar,
    p_unid_resp           numeric,
    p_assunto             varchar,
    p_prioridade          numeric,
    p_aviso               varchar,
    p_dias                numeric,
    p_cidade              numeric,
    p_palavra_chave       varchar,
    p_inicio_real         date,
    p_fim_real            date,
    p_concluida           varchar,
    p_data_conclusao      date,
    p_nota_conclusao      varchar,
    p_custo_real          numeric,
    p_opiniao             numeric,
    p_projeto             numeric,
    p_atividade           numeric,
    p_projeto_ant         numeric,
    p_atividade_ant       numeric,
    p_restricao           numeric,
    p_demanda_tipo        numeric,
    p_recebimento         date,
    p_limite_conclusao    date,
    p_responsavel         numeric,
    p_chave_nova          numeric
   ) RETURNS VOID AS $$
DECLARE
   w_arq     varchar(4000) := ', ';
   w_chave   numeric(18);
   w_log_sol numeric(18);
   w_log_esp numeric(18);
   w_menu    siw_menu%rowtype;

    c_arquivos CURSOR FOR
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_solicitacao.nextval into w_Chave;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante, 
         cadastrador,        executor,      descricao,           justificativa, 
         inicio,             fim,           inclusao,            ultima_alteracao, 
         conclusao,          valor,         opiniao,             data_hora, 
         sq_unidade,         sq_cc,         sq_cidade_origem,    palavra_chave,
         sq_solic_pai)
      (select 
         w_Chave,            p_menu,        a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_executor,    p_descricao,         p_justificativa,
         p_inicio,           p_fim,         now(),             now(),
         null,               p_valor,       null,                p_data_hora,
         p_unidade,          p_sqcc,        p_cidade,            p_palavra_chave,
         p_projeto
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Insere registro em GD_DEMANDA
      Insert into gd_demanda
         ( sq_siw_solicitacao,  sq_unidade_resp, assunto,           prioridade,
           aviso_prox_conc,     dias_aviso,      inicio_real,       fim_real,
           concluida,           data_conclusao,  nota_conclusao,    custo_real,
           proponente,          ordem,           sq_demanda_pai,    sq_siw_restricao,
           sq_demanda_tipo,     recebimento,     limite_conclusao,  responsavel
         )
      (select
           w_chave,             p_unid_resp,     p_assunto,         p_prioridade,
           p_aviso,             p_dias,          null,              null,
           'N',                 null,            null,              0,
           p_proponente,        p_ordem,         p_atividade_ant,   p_restricao,
           p_demanda_tipo,      p_recebimento,   p_limite_conclusao, p_responsavel
       
      );

      -- Insere log da solicitação
      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (select 
          sq_siw_solic_log.nextval,  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          now(),            'N',
          'Cadastramento inicial'
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
           
      -- Se receber p_atividade, grava na tabela de atividades de projeto
      If p_atividade is not null Then
         Insert Into pj_etapa_demanda 
                (sq_etapa_demanda,         sq_projeto_etapa, sq_siw_solicitacao)
         Values (sq_etapa_demanda.nextval, p_atividade,      w_chave);
      End If;

      -- Se a demanda foi copiada de outra, grava os dados complementares
      If p_copia is not null Then
         -- Insere registro na tabela de interessados
         Insert Into gd_demanda_interes 
            ( sq_pessoa,   sq_siw_solicitacao,   tipo_visao,    envia_email )
         (Select
              a.sq_pessoa, w_chave,              a.tipo_visao,  a.envia_email 
           from gd_demanda_interes a
          where a.sq_siw_solicitacao = p_copia
         );
         -- Insere registro na tabela de áreas envolvidas
         Insert Into gd_demanda_envolv 
            ( sq_unidade,   sq_siw_solicitacao,   papel )
         (Select
              a.sq_unidade, w_chave,              a.papel
            from gd_demanda_envolv a
           where a.sq_siw_solicitacao = p_copia
          );
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          sq_solic_pai     = p_projeto,
          sq_cc            = p_sqcc,
          solicitante      = p_solicitante,
          executor         = p_executor,
          descricao        = trim(p_descricao), 
          justificativa    = trim(p_justificativa),
          inicio           = p_inicio,
          fim              = p_fim,
          ultima_alteracao = now(),
          valor            = p_valor,
          sq_cidade_origem = p_cidade,
          palavra_chave    = p_palavra_chave
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas
      Update gd_demanda set
          sq_demanda_pai   = p_atividade_ant,
          sq_siw_restricao = p_restricao,
          sq_unidade_resp  = p_unid_resp,
          proponente       = p_proponente,
          assunto          = trim(p_assunto),
          prioridade       = p_prioridade,
          aviso_prox_conc  = p_aviso,
          dias_aviso       = p_dias,
          inicio_real      = p_inicio_real,
          ordem            = p_ordem,
          sq_demanda_tipo  = p_demanda_tipo,
          recebimento      = p_recebimento,
          limite_conclusao = p_limite_conclusao
      where sq_siw_solicitacao = p_chave;

      DELETE FROM pj_etapa_demanda where sq_siw_solicitacao = p_chave;
      If p_atividade is not null then
         -- Cria a vinculação com os novos dados
         Insert Into pj_etapa_demanda 
                (sq_etapa_demanda,         sq_projeto_etapa, sq_siw_solicitacao)
          Values (sq_etapa_demanda.nextval, p_atividade,     p_chave);
      End If;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Recupera os dados do menu
      select * into w_menu from siw_menu where sq_menu = p_menu;
      
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from gd_demanda_log where sq_siw_solicitacao = p_chave;
      
      -- Se não foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If (w_log_sol + w_log_esp) > 1 or w_menu.cancela_sem_tramite = 'S' Then
         -- Insere log de cancelamento
         Insert Into siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
             sq_siw_tramite,            data,                 devolucao, 
             observacao
            )
         (select 
             sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          now(),              'N',
             'Cancelamento'
            from siw_solicitacao a
           where a.sq_siw_solicitacao = p_chave
         );
         
         -- Atualiza a situação da demanda
         update gd_demanda set concluida = 'S' where sq_siw_solicitacao = p_chave;

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
         
         DELETE FROM gd_demanda_envolv  where sq_siw_solicitacao = p_chave;
         DELETE FROM gd_demanda_interes where sq_siw_solicitacao = p_chave;
         DELETE FROM pj_etapa_demanda   where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de demandas
         DELETE FROM gd_demanda where sq_siw_solicitacao = p_chave;
            
         -- Remove o log da solicitação
         DELETE FROM siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         DELETE FROM siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   Elsif p_operacao = 'V' Then -- Encaminhamento
      -- Ativa registro
      null;
   Elsif p_operacao = 'C' Then -- Conclusão
      -- Atualiza a tabela de solicitações com os dados da conclusão
      Update siw_solicitacao set
          conclusao        = p_data_conclusao,
          ultima_alteracao = now(),
          sq_siw_tramite   = (select sq_siw_tramite from siw_tramite where sq_menu = p_menu and sigla='AT')
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas com os dados da conclusão
      Update gd_demanda set
          fim_real        = p_fim_real,
          concluida       = p_concluida,
          data_conclusao  = p_data_conclusao,
          nota_conclusao  = trim(p_nota_conclusao),
          custo_real      = p_custo_real
      where sq_siw_solicitacao = p_chave;
   Elsif p_operacao = 'O' Then -- Opinião
      -- Atualiza a tabela de solicitações com a opinião do solicitante
      Update siw_solicitacao set
          opiniao         = p_opiniao
      where sq_siw_solicitacao = p_chave;
   Elsif p_operacao = 'F' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          sq_solic_pai     = p_projeto,
          sq_cc            = p_sqcc,
          ultima_alteracao = now(),
          palavra_chave    = p_palavra_chave
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas
      Update gd_demanda set
          sq_unidade_resp  = p_unid_resp,
          prioridade       = p_prioridade,
          sq_demanda_tipo  = p_demanda_tipo,
          responsavel      = p_responsavel
      where sq_siw_solicitacao = p_chave;
   Elsif p_operacao = 'D' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          inicio           = p_inicio,
          fim              = p_fim,
          valor            = p_valor,          
          ultima_alteracao = now()
      where sq_siw_solicitacao = p_chave;
   End If;
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;