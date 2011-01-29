create or replace FUNCTION SP_PutProjetoGeral
   (p_operacao             varchar,
    p_chave                numeric,
    p_copia                numeric,
    p_menu                numeric,
    p_unidade             numeric,
    p_solicitante         numeric,
    p_proponente          varchar,
    p_cadastrador         numeric,
    p_executor            numeric,
    p_plano               numeric,
    p_objetivo            varchar,    
    p_sqcc                numeric,
    p_solic_pai           numeric,
    p_descricao           varchar,
    p_justificativa       varchar,
    p_inicio              date,
    p_fim                 date,
    p_valor               numeric,
    p_data_hora           varchar,
    p_unid_resp           numeric,
    p_codigo              varchar,
    p_titulo              varchar,
    p_prioridade          numeric,
    p_aviso               varchar,
    p_dias                numeric,
    p_aviso_pacote        varchar,
    p_dias_pacote         numeric,
    p_cidade              numeric,
    p_palavra_chave       varchar,
    p_vincula_contrato    varchar,
    p_vincula_viagem      varchar,
    p_sq_acao_ppa         numeric,
    p_sq_orprioridade     numeric,
    p_selecionada_mpog    varchar,
    p_selecionada_relev   varchar,
    p_sq_tipo_pessoa      varchar,
    p_chave_nova          numeric
   ) RETURNS VOID AS $$
DECLARE
   w_arq       varchar(4000) := ', ';
   w_coord     varchar(4000) := ', ';
   w_chave     numeric(18);
   w_chave1    numeric(18);
   w_log_sol   numeric(18);
   w_log_esp   numeric(18);
   w_ativ      numeric(18);
   i           numeric(10) := 0;
   w_item      varchar(18);   
   w_objetivo  varchar(200) := p_objetivo ||',';   
   w_menu      siw_menu%rowtype;

   type tb_risco_pai is table of numeric(10) index by binary_integer;
   w_risco_pai tb_risco_pai;

   type tb_recurso_pai is table of numeric(10) index by binary_integer;
   w_recurso_pai tb_recurso_pai;

   type rec_etapa is record (
       sq_chave_destino       numeric(10) := null,
       sq_chave_origem        numeric(10) := null,
       sq_chave_pai_origem    numeric(10) := null
      );
   type tb_etapa is table of rec_etapa index by binary_integer;
   type tb_etapa_pai is table of numeric(10) index by binary_integer;

   w_etapa     tb_etapa;
   w_etapa_pai tb_etapa_pai;
   
    c_rubricas CURSOR FOR
     select * from pj_rubrica    where ativo = 'S' and sq_siw_solicitacao = p_copia;
     
    c_riscos CURSOR FOR
     select * from siw_restricao where risco = 'S' and sq_siw_solicitacao = p_copia;

    c_etapa_risco CURSOR FOR
      select a.*
        from siw_restricao_etapa      a
             inner join siw_restricao b on (a.sq_siw_restricao = b.sq_siw_restricao)
       where b.sq_siw_solicitacao = p_copia;

    c_recursos CURSOR FOR
     select * from pj_projeto_recurso where sq_siw_solicitacao = p_copia;

    c_etapas CURSOR FOR
      select * from pj_projeto_etapa where sq_siw_solicitacao = p_copia;

    c_etapa_recurso CURSOR FOR
      select a.*
        from pj_recurso_etapa              a
             inner join pj_projeto_recurso b on (a.sq_projeto_recurso = b.sq_projeto_recurso)
       where b.sq_siw_solicitacao = p_copia;

    c_atividades CURSOR FOR
      select * from siw_solicitacao t where t.sq_solic_pai = p_chave;

    c_arquivos CURSOR FOR
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;

    c_coordenadas CURSOR FOR
      select sq_siw_coordenada from siw_coordenada_solicitacao where sq_siw_solicitacao = p_chave;
BEGIN
   If p_operacao <> 'I' Then -- Inclusão
      -- Remove as vinculações existentes para a solicitação
      DELETE FROM siw_solicitacao_objetivo where sq_siw_solicitacao = coalesce(w_chave, p_chave);
   End If;

   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select nextVal('sq_siw_solicitacao') into w_Chave;

      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante,
         cadastrador,        executor,      descricao,           justificativa,
         inicio,             fim,           inclusao,            ultima_alteracao,
         conclusao,          valor,         opiniao,             data_hora,
         sq_unidade,         sq_cc,         sq_solic_pai,        sq_cidade_origem,
         palavra_chave,      sq_plano,      codigo_interno,      titulo)
      (select
         w_Chave,            p_menu,        a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_executor,    p_descricao,         p_justificativa,
         p_inicio,           p_fim,         now(),             now(),
         null,               p_valor,       null,                p_data_hora,
         p_unidade,          p_sqcc,        p_solic_pai,         p_cidade,
         p_palavra_chave,    p_plano,       p_codigo,            p_titulo
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );

      -- Insere registro em pj_projeto
      Insert into pj_projeto
         ( sq_siw_solicitacao,  sq_unidade_resp,  prioridade,        aviso_prox_conc,
           dias_aviso,          inicio_real,      fim_real,          concluida,
           data_conclusao,      nota_conclusao,   custo_real,        proponente,
           sq_tipo_pessoa,      vincula_contrato, vincula_viagem,    aviso_prox_conc_pacote, 
           perc_dias_aviso_pacote
         )
      (select
           w_chave,              p_unid_resp,     p_prioridade,      p_aviso,
           p_dias,               null,            null,              'N',
           null,                 null,            0,                 p_proponente,
           p_sq_tipo_pessoa,     Nvl(p_vincula_contrato,'N'),        Nvl(p_vincula_viagem,'N'),
           p_aviso_pacote,       p_dias_pacote
       
      );

      -- Grava os dados de uma ação orçamentária, se for o caso
      If p_sq_acao_ppa is not null or p_sq_orprioridade is not null Then
         -- Grava os dados complementares ao projeto, relativos à ação orçamentária
         insert into or_acao  (sq_siw_solicitacao, sq_acao_ppa, sq_orprioridade)
         values (w_chave, p_sq_acao_ppa, p_sq_orprioridade);
         If p_sq_acao_ppa is not null Then
            -- Atualiza os dados da tabela de ações do PPA
            update or_acao_ppa set
               selecionada_mpog      = p_selecionada_mpog,
               selecionada_relevante = p_selecionada_relev
            where sq_acao_ppa = p_sq_acao_ppa;
         End If;
      End If;

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

      -- Se o projeto foi copiado de outra, grava os dados complementares
      If p_copia is not null Then
         -- Complementa as informações da solicitacao
         update siw_solicitacao set ( descricao, justificativa ) =
         (select descricao, justificativa
            from siw_solicitacao
           where sq_siw_solicitacao = p_copia
         )
         where sq_siw_solicitacao = w_chave;

         -- Complementa as informações do projeto
         update pj_projeto set
               ( outra_parte,         preposto,        sq_cidade,         limite_passagem, 
                 objetivo_superior,   exclusoes,       premissas,         restricoes
               ) =
         (select outra_parte,         preposto,        sq_cidade,         limite_passagem, 
                 objetivo_superior,   exclusoes,       premissas,         restricoes
            from pj_projeto
           where sq_siw_solicitacao = p_copia
         )
         where sq_siw_solicitacao = w_chave;

         -- Insere registro na tabela de interessados
         Insert Into pj_projeto_interes ( sq_pessoa,   sq_siw_solicitacao,   tipo_visao,    envia_email )
         (Select                          a.sq_pessoa, w_chave,              a.tipo_visao,  a.envia_email
           from pj_projeto_interes a
          where a.sq_siw_solicitacao = p_copia
         );
         -- Insere registro na tabela de áreas envolvidas
         Insert Into pj_projeto_envolv ( sq_unidade,   sq_siw_solicitacao,   papel )
         (Select                         a.sq_unidade, w_chave,              a.papel
            from pj_projeto_envolv a
           where a.sq_siw_solicitacao = p_copia
          );

          -- Insere os riscos do projeto
          for crec in c_riscos loop
             -- recupera a próxima chave do recurso
             select nextVal('sq_siw_restricao') into w_chave1;

             -- Guarda pai do registro original
             w_risco_pai(crec.sq_siw_restricao) := w_chave1;

             -- insere o recurso
             insert into siw_restricao
               (sq_siw_restricao,      sq_siw_solicitacao,      sq_pessoa,           sq_pessoa_atualizacao,      sq_tipo_restricao,      
                risco,                 problema,                descricao,           probabilidade,              impacto, 
                criticidade,           estrategia,              acao_resposta,       ultima_atualizacao)
             values
               (w_chave1,              w_chave,                 crec.sq_pessoa,      p_cadastrador,              crec.sq_tipo_restricao, 
                crec.risco,            crec.problema,           crec.descricao,      crec.probabilidade,         crec.impacto, 
                crec.criticidade,      crec.estrategia,         crec.acao_resposta,  now());
          end loop;

          -- Insere recursos do projeto
          for crec in c_recursos loop
             -- recupera a próxima chave do recurso
             select nextVal('sq_projeto_recurso') into w_chave1;

             -- Guarda pai do registro original
             w_recurso_pai(crec.sq_projeto_recurso) := w_chave1;

             -- insere o recurso
             Insert Into pj_projeto_recurso
                ( sq_projeto_recurso, sq_siw_solicitacao, nome,       tipo,      descricao,      finalidade )
             Values
                ( w_chave1,           w_chave,            crec.nome,  crec.tipo, crec.descricao, crec.finalidade);
          end loop;

          -- Insere etapas do projeto
          for crec in c_etapas loop
             -- recupera a próxima chave do recurso
             select nextVal('sq_projeto_etapa') into w_chave1;

             -- Guarda pai do registro original
             i := i + 1;
             w_etapa(i).sq_chave_destino    := w_chave1;
             w_etapa(i).sq_chave_origem     := crec.sq_projeto_etapa;
             w_etapa(i).sq_chave_pai_origem := crec.sq_etapa_pai;

             w_etapa_pai(crec.sq_projeto_etapa) := w_chave1;

             -- insere o recurso
             Insert Into pj_projeto_etapa
                ( sq_projeto_etapa,   sq_siw_solicitacao,          ordem,                titulo,
                  descricao,          inicio_previsto,             fim_previsto,         inicio_real,
                  fim_real,           perc_conclusao,              orcamento,            sq_unidade,
                  sq_pessoa,          vincula_atividade,           sq_pessoa_atualizacao,
                  unidade_medida,     quantidade,                  cumulativa,           programada, 
                  vincula_contrato,   pacote_trabalho,             base_geografica,      sq_pais, 
                  sq_regiao,          co_uf,                       sq_cidade,            peso)
             Values
                ( w_chave1,              w_chave,                  crec.ordem,           crec.titulo,
                  crec.descricao,        crec.inicio_previsto,     crec.fim_previsto,    null,
                  null,                  0,                        crec.orcamento,       crec.sq_unidade,
                  crec.sq_pessoa,        crec.vincula_atividade,   crec.sq_pessoa_atualizacao,
                  crec.unidade_medida,   crec.quantidade,          crec.cumulativa,      crec.programada, 
                  crec.vincula_contrato, crec.pacote_trabalho,     crec.base_geografica, crec.sq_pais, 
                  crec.sq_regiao,        crec.co_uf,               crec.sq_cidade,       crec.peso);

             -- Grava os dados de uma ação orçamentária, se for o caso
             If p_sq_acao_ppa is not null or p_sq_orprioridade is not null Then
                -- Grava os dados complementares ao projeto, relativos à ação orçamentária
                insert into or_acao  (sq_siw_solicitacao, sq_acao_ppa, sq_orprioridade)
                values (p_copia, p_sq_acao_ppa, p_sq_orprioridade);
                If p_sq_acao_ppa is not null Then
                   -- Atualiza os dados da tabela de ações do PPA
                   update or_acao_ppa set
                      selecionada_mpog      = p_selecionada_mpog,
                      selecionada_relevante = p_selecionada_relev
                   where sq_acao_ppa = p_sq_acao_ppa;
                End If;
             End If;

          end loop;

          -- Acerta o vínculo entre as etapas
          i := 0;
          for i in 1 .. w_etapa.Count loop
              if w_etapa(i).sq_chave_pai_origem is not null then
                 update pj_projeto_etapa a
                    set sq_etapa_pai = w_etapa_pai(w_etapa(i).sq_chave_pai_origem)
                  where sq_projeto_etapa = w_etapa(i).sq_chave_destino;
              end if;
          end loop;

          -- Insere o relacionamento entre etapas e recursos
          for crec in c_etapa_recurso loop
             Insert Into pj_recurso_etapa
                ( sq_projeto_etapa,                   sq_projeto_recurso,                     observacao )
             Values
                ( w_etapa_pai(crec.sq_projeto_etapa), w_recurso_pai(crec.sq_projeto_recurso), crec.observacao );
          end loop;

          -- Insere o relacionamento entre etapas e riscos
          for crec in c_etapa_risco loop
             insert into siw_restricao_etapa (sq_siw_restricao,                   sq_projeto_etapa)
             values                          (w_risco_pai(crec.sq_siw_restricao), w_etapa_pai(crec.sq_projeto_etapa));
          end loop;
          
          -- Insere rubricas do projeto
          for crec in c_rubricas loop
             -- recupera a próxima chave da rubrica
             select nextVal('sq_projeto_rubrica') into w_chave1;
          
             insert into pj_rubrica
                (sq_projeto_rubrica, sq_siw_solicitacao,  sq_cc,        codigo,      nome,      descricao,      ativo,      aplicacao_financeira)
             values 
                (w_chave1,           w_chave,             crec.sq_cc,  crec.codigo, crec.nome, crec.descricao, crec.ativo, crec.aplicacao_financeira);
             
             insert into pj_rubrica_cronograma
               (sq_rubrica_cronograma,                sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real)
               (select nextVal('sq_rubrica_cronograma'), w_chave1, inicio,   fim,    valor_previsto,      valor_real 
                  from pj_rubrica_cronograma
                 where sq_projeto_rubrica = crec.sq_projeto_rubrica);
          end loop;
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          sq_plano         = p_plano,
          codigo_interno   = p_codigo,
          titulo           = trim(p_titulo),
          sq_cc            = p_sqcc,
          sq_solic_pai     = p_solic_pai,
          descricao        = coalesce(p_descricao,descricao),
          justificativa    = coalesce(p_justificativa,justificativa),
          solicitante      = p_solicitante,
          executor         = p_executor,
          inicio           = p_inicio,
          fim              = p_fim,
          ultima_alteracao = now(),
          valor            = p_valor,
          sq_cidade_origem = p_cidade,
          palavra_chave    = p_palavra_chave
      where sq_siw_solicitacao = p_chave;

      -- Atualiza a tabela de projetos
      Update pj_projeto set
          sq_unidade_resp  = p_unid_resp,
          proponente       = p_proponente,
          prioridade       = p_prioridade,
          aviso_prox_conc  = p_aviso,
          dias_aviso       = p_dias,
          aviso_prox_conc_pacote = p_aviso_pacote,
          perc_dias_aviso_pacote = p_dias_pacote,
          sq_tipo_pessoa   = p_sq_tipo_pessoa,
          vincula_contrato = Nvl(p_vincula_contrato,'N'),
          vincula_viagem   = Nvl(p_vincula_viagem,'N')
      where sq_siw_solicitacao = p_chave;

      If Nvl(p_sq_tipo_pessoa,0) = 1 Then
         update pj_projeto set preposto = null where sq_siw_solicitacao = p_chave;
         DELETE FROM pj_projeto_representante where sq_siw_solicitacao = p_chave;
      End If;

      -- Atualiza os dados de uma ação orçamentária, se for o caso
      If p_sq_acao_ppa is not null or p_sq_orprioridade is not null Then
         -- Grava os dados complementares ao projeto, relativos à ação orçamentária
         update or_acao set
            sq_acao_ppa      = p_sq_acao_ppa,
            sq_orprioridade  = p_sq_orprioridade
         where sq_siw_solicitacao = p_chave;
         If p_sq_acao_ppa is not null Then
            -- Atualiza os dados da tabela de ações do PPA
            update or_acao_ppa set
               selecionada_mpog      = p_selecionada_mpog,
               selecionada_relevante = p_selecionada_relev
            where sq_acao_ppa = p_sq_acao_ppa;
         End If;
      End If;

   Elsif p_operacao = 'E' Then -- Exclusão
      -- Recupera os dados do menu
      select * into w_menu from siw_menu where sq_menu = p_menu;
      
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from pj_projeto_log where sq_siw_solicitacao = p_chave;
      select count(*) into w_ativ    from siw_solicitacao where sq_solic_pai      = p_chave;

      -- Se não tem atividades vinculadas nem foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If (w_log_sol + w_log_esp + w_ativ) > 1 or w_menu.cancela_sem_tramite = 'S' Then
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

         -- Atualiza a situação do projeto
         update pj_projeto set concluida = 'S' where sq_siw_solicitacao = p_chave;

         -- Recupera a chave que indica que a solicitação está cancelada
         select a.sq_siw_tramite into w_chave from siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';

         -- Atualiza a situação da solicitação
         update siw_solicitacao set sq_siw_tramite = w_chave where sq_siw_solicitacao = p_chave;

         -- Atualiza a ação PPA e inicitiva prioritária quando a ação for cancelada ou excluída
         update or_acao set sq_acao_ppa = null, sq_orprioridade = null where sq_siw_solicitacao = p_chave;

         -- Atualiza eventuais atividades ligadas ao projeto
         for crec in c_atividades loop
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
               where a.sq_siw_solicitacao = crec.sq_siw_solicitacao
             );

             -- Atualiza a situação do projeto
             update gd_demanda set concluida = 'S' where sq_siw_solicitacao = crec.sq_siw_solicitacao;

             -- Recupera a chave que indica que a solicitação está cancelada
             select a.sq_siw_tramite into w_chave from siw_tramite a where a.sq_menu = crec.sq_menu and a.sigla = 'CA';

             -- Atualiza a situação da solicitação
             update siw_solicitacao set sq_siw_tramite = w_chave where sq_siw_solicitacao = crec.sq_siw_solicitacao;
         end loop;
      Else
         -- Monta string com a chave dos arquivos ligados à solicitação informada
         for crec in c_arquivos loop
            w_arq := w_arq || crec.sq_siw_arquivo;
         end loop;
         w_arq := substr(w_arq, 3, length(w_arq));

         -- Monta string com a chave das coordenadas ligadas à solicitação informada
         for crec in c_coordenadas loop
            w_coord := w_coord || crec.sq_siw_coordenada;
         end loop;
         w_coord := substr(w_coord, 3, length(w_coord));

         -- Remove os registros vinculados ao projeto
         DELETE FROM siw_coordenada_solicitacao  where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_coordenada              where sq_siw_coordenada in (w_coord);

         DELETE FROM siw_solic_arquivo           where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_arquivo                 where sq_siw_arquivo     in (w_arq);

         DELETE FROM siw_solic_indicador         where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_meta_cronograma         where sq_solic_meta in (select sq_solic_meta from siw_solic_meta where sq_siw_solicitacao = p_chave);
         DELETE FROM siw_solic_meta              where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_solicitacao_interessado where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_solic_recurso_alocacao  where sq_solic_recurso in (select sq_solic_recurso from siw_solic_recurso where sq_siw_solicitacao = p_chave);
         DELETE FROM siw_solic_recurso           where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_restricao_etapa         where sq_siw_restricao in (select sq_siw_restricao from siw_restricao where sq_siw_solicitacao = p_chave);
         DELETE FROM siw_restricao               where sq_siw_solicitacao = p_chave;

         DELETE FROM or_acao_prioridade          where sq_siw_solicitacao = p_chave;
         DELETE FROM or_acao_financ              where sq_siw_solicitacao = p_chave;
         DELETE FROM or_acao                     where sq_siw_solicitacao = p_chave;
         DELETE FROM pj_projeto_representante    where sq_siw_solicitacao = p_chave;
         DELETE FROM pj_projeto_envolv           where sq_siw_solicitacao = p_chave;
         DELETE FROM pj_projeto_interes          where sq_siw_solicitacao = p_chave;
         DELETE FROM pj_recurso_etapa            where sq_projeto_etapa in (select sq_projeto_etapa from pj_projeto_etapa where sq_siw_solicitacao = p_chave);
         DELETE FROM pj_rubrica_cronograma       where sq_projeto_rubrica in (select sq_projeto_rubrica from pj_rubrica where sq_siw_solicitacao = p_chave);
         DELETE FROM pj_rubrica                  where sq_siw_solicitacao = p_chave;
         DELETE FROM pj_projeto_etapa            where sq_siw_solicitacao = p_chave;
         DELETE FROM pj_projeto_recurso          where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de projetos
         DELETE FROM pj_projeto                  where sq_siw_solicitacao = p_chave;

         -- Remove o log da solicitação
         DELETE FROM siw_solic_log               where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         DELETE FROM siw_solicitacao             where sq_siw_solicitacao = p_chave;
      End If;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;

   If p_operacao in ('I','A') and p_objetivo is not null Then
      -- Para cada objetivo estratégico, grava um registro na tabela de vinculações
      Loop
         w_item  := Trim(substr(w_objetivo,1,Instr(w_objetivo,',')-1));
         If Length(w_item) > 0 Then
            insert into siw_solicitacao_objetivo(sq_siw_solicitacao, sq_plano, sq_peobjetivo) values (coalesce(w_chave,p_chave), p_plano, to_number(w_item));
         End If;
         w_objetivo := substr(w_objetivo,Instr(w_objetivo,',')+1,200);
         Exit when w_objetivo is null;
      End Loop;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;