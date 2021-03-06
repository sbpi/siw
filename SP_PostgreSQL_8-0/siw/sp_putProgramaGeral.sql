create or replace FUNCTION sp_putProgramaGeral
   (p_operacao            varchar,
    p_chave               numeric,
    p_copia               numeric,
    p_menu                numeric,
    p_plano               numeric,
    p_objetivo            varchar,
    p_codigo              varchar,
    p_titulo              varchar,
    p_unidade             numeric,
    p_solicitante         numeric,
    p_unid_resp           numeric,
    p_horizonte           numeric,
    p_natureza            numeric,
    p_inicio              date,
    p_fim                 date,
    p_parcerias           varchar,
    p_ln_programa         varchar,
    p_cadastrador         numeric,
    p_executor            numeric,
    p_solic_pai           numeric,
    p_valor               numeric,
    p_data_hora           varchar,
    p_aviso               varchar,
    p_dias                numeric,
    p_chave_nova          numeric
   ) RETURNS VOID AS $$
DECLARE
   w_arq       varchar(4000) := ', ';
   w_chave     numeric(18);
   w_log_sol   numeric(18);
   w_log_esp   numeric(18);
   w_ativ      numeric(18);
   w_item      varchar(18);
   w_objetivo  varchar(200) := p_objetivo ||',';
   w_menu      siw_menu%rowtype;

    c_recursos CURSOR FOR
     select * from pj_projeto_recurso where sq_siw_solicitacao = p_copia;
     
    c_atividades CURSOR FOR
      select * from siw_solicitacao t where t.sq_solic_pai = p_chave;
   
    c_arquivos CURSOR FOR
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
BEGIN
   If p_operacao <> 'I' Then
      -- Remove as vinculações existentes para a solicitação
      DELETE FROM siw_solicitacao_objetivo where sq_siw_solicitacao = coalesce(w_chave, p_chave);
   End If;

   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select nextVal('sq_siw_solicitacao') into w_Chave;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,            sq_siw_tramite,      solicitante, 
         cadastrador,        executor,           inicio,              fim,
         inclusao,           ultima_alteracao,   data_hora,           sq_unidade,
         sq_solic_pai,       sq_cidade_origem,   palavra_chave,       sq_plano,
         valor,              titulo,             codigo_interno)
      (select 
         w_Chave,            p_menu,             a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_executor,         p_inicio,            p_fim,
         now(),            now(),            p_data_hora,         p_unidade,
         p_solic_pai,        c.sq_cidade_padrao, p_parcerias,         p_plano,
         p_valor,            p_titulo,           p_codigo
         from siw_tramite              a
              inner   join siw_menu    b on (a.sq_menu   = b.sq_menu)
                inner join siw_cliente c on (b.sq_pessoa = c.sq_pessoa)
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Insere registro em pe_programa
      insert into pe_programa
         ( sq_siw_solicitacao,  cliente,          sq_pehorizonte,    sq_penatureza, 
           sq_unidade_resp,     ln_programa,      aviso_prox_conc,   dias_aviso)
      (select
           w_chave,             a.sq_pessoa,      p_horizonte,       p_natureza,
           p_unid_resp,         p_ln_programa,    p_aviso,           p_dias
         from siw_menu a
        where a.sq_menu = p_menu
      );
      
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
           
      -- Se o programa foi copiado de outro, grava os dados complementares
      If p_copia is not null Then
         -- Insere registro na tabela de interessados
         insert into siw_solicitacao_interessado
           ( sq_solicitacao_interessado,         sq_siw_solicitacao, sq_pessoa,   sq_tipo_interessado)
         (select
             nextVal('sq_solicitacao_interessado'), w_chave,            a.sq_pessoa, a.sq_tipo_interessado
            from siw_solicitacao_interessado a
           where a.sq_siw_solicitacao = p_copia
         );
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          sq_solic_pai     = p_solic_pai,
          sq_plano         = p_plano,
          solicitante      = p_solicitante,
          sq_unidade       = p_unidade,
          inicio           = p_inicio,
          fim              = p_fim,
          valor            = p_valor,
          ultima_alteracao = now(),
          codigo_interno   = p_codigo,
          titulo           = trim(p_titulo),
          palavra_chave    = p_parcerias
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de projetos
      Update pe_programa set
          sq_unidade_resp  = p_unid_resp,
          ln_programa      = p_ln_programa,
          aviso_prox_conc  = p_aviso,
          dias_aviso       = p_dias
      where sq_siw_solicitacao = p_chave;

   Elsif p_operacao = 'E' Then -- Exclusão
      -- Recupera os dados do menu
      select * into w_menu from siw_menu where sq_menu = p_menu;
      
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from pe_programa_log where sq_siw_solicitacao = p_chave;
      select count(*) into w_ativ    from siw_solicitacao where sq_solic_pai      = p_chave;
      
      -- Se não tem projetos vinculados nem foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
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
         
         -- Recupera a chave que indica que a solicitação está cancelada
         select a.sq_siw_tramite into w_chave from siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';
         
         -- Atualiza a situação da solicitação
         update siw_solicitacao set sq_siw_tramite = w_chave where sq_siw_solicitacao = p_chave;

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
             update pj_projeto set concluida = 'S' where sq_siw_solicitacao = crec.sq_siw_solicitacao;
    
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
         
         -- Remove os registros vinculados ao programa
         DELETE FROM siw_solic_arquivo where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_arquivo       where sq_siw_arquivo     in (w_arq);
         
         DELETE FROM siw_solic_indicador         where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_meta_cronograma         where sq_solic_meta in (select sq_solic_meta from siw_solic_meta where sq_siw_solicitacao = p_chave);
         DELETE FROM siw_solic_meta              where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_solicitacao_interessado where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de programas
         DELETE FROM pe_programa where sq_siw_solicitacao = p_chave;
            
         -- Remove o log da solicitação
         DELETE FROM siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         DELETE FROM siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
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