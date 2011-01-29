create or replace FUNCTION SP_PutCLGeral
   (p_operacao             varchar,
    p_chave                numeric,
    p_copia                numeric,
    p_menu                numeric,
    p_unidade             numeric,
    p_solicitante         numeric,
    p_cadastrador         numeric,
    p_executor            numeric,
    p_plano               numeric,
    p_objetivo            varchar,    
    p_sqcc                numeric,
    p_solic_pai           numeric,
    p_justificativa       varchar,
    p_observacao          varchar,
    p_inicio              date,
    p_fim                 date,
    p_valor               numeric,
    p_codigo              varchar,
    p_prioridade          numeric,
    p_aviso               varchar,
    p_dias                numeric,
    p_cidade              numeric,
    p_decisao_judicial    varchar,
    p_numero_original     varchar,
    p_data_recebimento    date,
    p_arp                 varchar,
    p_interno             varchar,
    p_especie_documento   numeric,
    p_financeiro          numeric,
    p_rubrica             numeric,
    p_lancamento          numeric,
    p_observacao_log      varchar,
    p_chave_nova          numeric
   ) RETURNS VOID AS $$
DECLARE
   w_financeiro  numeric(18) := p_financeiro;
   w_existe      numeric(10);
   w_arq         varchar(4000) := ', ';
   w_chave       numeric(18);
   w_log_sol     numeric(18);
   w_item        varchar(18);   
   w_objetivo    varchar(200) := p_objetivo ||',';   
   w_codigo      varchar(60);
   w_unidade_pai numeric(18);
   w_data        date;
   w_dias        numeric(3);
   w_parametro   cl_parametro%rowtype;
   w_menu        siw_menu%rowtype;


    c_arquivos CURSOR FOR
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
BEGIN
   -- Recupera a hora atual
   w_data := now();
   
   -- Recupera os parâmetros do módulo
   select * into w_parametro
     from cl_parametro where cliente = (select sq_pessoa from siw_menu where sq_menu = p_menu);
   
   -- Se for decisão judicial calcula o número de dias para aviso
   If p_decisao_judicial = 'S' Then
      w_dias := round((p_fim-w_data+1),0);
   Else
      w_dias := p_dias;
   End If;
   
   -- Verifica se precisa gravar o tipo de vínculo financeiro
   If instr('IA','I')>0 and p_financeiro is null and p_lancamento is not null Then
      -- Verifica se há um vínculo único para as opções enviadas
      select count(*) into w_existe
        from cl_vinculo_financeiro a
       where a.sq_siw_solicitacao = p_solic_pai
         and a.sq_projeto_rubrica = p_rubrica
         and a.sq_tipo_lancamento = p_lancamento
         and a.sq_menu            = p_menu;
      -- Prepara variável para gravação se encontrou um, e apenas um registro.
      If w_existe = 1 Then
         select sq_clvinculo_financeiro into w_financeiro
           from cl_vinculo_financeiro a
          where a.sq_siw_solicitacao = p_solic_pai
            and a.sq_projeto_rubrica = p_rubrica
            and a.sq_tipo_lancamento = p_lancamento
            and a.sq_menu            = p_menu;
      End If;
   End If;
   
   If p_operacao not in ('I','T') Then -- Inclusão nem atendimento
      -- Remove as vinculações existentes para a solicitação
      DELETE FROM siw_solicitacao_objetivo where sq_siw_solicitacao = coalesce(w_chave, p_chave);
   End If;

   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select nextVal('sq_siw_solicitacao') into w_Chave;

      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante,
         cadastrador,        executor,      justificativa,       inicio,
         fim,                inclusao,      ultima_alteracao,    sq_unidade,
         sq_cc,              sq_solic_pai,  sq_cidade_origem,    sq_plano,
         codigo_interno,     observacao,    valor)
      (select
         w_Chave,            p_menu,        a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_executor,    p_justificativa,     case p_decisao_judicial when 'S' then p_inicio else w_data end,
         p_fim,              w_data,        w_data,              p_unidade,
         p_sqcc,             p_solic_pai,   p_cidade,            p_plano,
         p_codigo,           p_observacao,  coalesce(p_valor,0)
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      select sq_unidade_pai into w_unidade_pai from cl_unidade where sq_unidade = p_unidade;
      -- Insere registro em cl_solicitacao
      If w_unidade_pai is not null Then
         Insert into cl_solicitacao
            ( sq_siw_solicitacao,  prioridade,       decisao_judicial,     numero_original,
              data_recebimento,    aviso_prox_conc,  dias_aviso,           sq_unidade,
              arp,                 interno,          sq_especie_documento, sq_financeiro
            )
         (select
              w_chave,              p_prioridade,    p_decisao_judicial,   p_numero_original, 
              p_data_recebimento,   p_aviso,         w_dias,               a.sq_unidade_pai,
              p_arp,                p_interno,       p_especie_documento,  w_financeiro
           from cl_unidade a
          where a.sq_unidade = p_unidade
         );
      Else
         Insert into cl_solicitacao
            ( sq_siw_solicitacao,  prioridade,       decisao_judicial,     numero_original,
              data_recebimento,    aviso_prox_conc,  dias_aviso,           sq_unidade,
              arp,                 interno,          sq_especie_documento, sq_financeiro
            )
         (select
              w_chave,              p_prioridade,    p_decisao_judicial,   p_numero_original, 
              p_data_recebimento,   p_aviso,         w_dias,               p_unidade,
              p_arp,                p_interno,       p_especie_documento,  w_financeiro
          
         );
      
      End If;
      If p_codigo is null Then
         PERFORM geracodigointerno(w_chave,null,w_codigo);
         update siw_solicitacao set
                codigo_interno = w_codigo
          where sq_siw_solicitacao = w_chave;
      End If;
      -- Insere log da solicitação
      Insert Into siw_solic_log
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa,
          sq_siw_tramite,            data,               devolucao,
          observacao
         )
      (select
          nextVal('sq_siw_solic_log'),  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          w_data,             'N',
          coalesce(p_observacao_log,'Cadastramento inicial')
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );

      -- Se a solicitacao foi copiado de outra, grava os dados complementares
      If p_copia is not null Then
         -- Complementa as informações da solicitacao
         update siw_solicitacao set ( justificativa ) =
         (select justificativa
            from siw_solicitacao
           where sq_siw_solicitacao = p_copia
         )
         where sq_siw_solicitacao = w_chave;

         -- Complementa as informações da solicitacao
         update cl_solicitacao set 
                (sq_especie_documento,  sq_especificacao_despesa, sq_eoindicador, sq_lcfonte_recurso,
                 sq_lcmodalidade,       sq_lcjulgamento,          sq_lcsituacao,  numero_original, 
                 data_recebimento,      processo,                 indice_base,    tipo_reajuste, 
                 limite_variacao,       data_homologacao,         data_diario_oficial,
                 pagina_diario_oficial, financeiro_unico,         numero_ata,     numero_certame,
                 arp,                   interno,                  sq_financeiro
                ) = 
         (select sq_especie_documento,  sq_especificacao_despesa, sq_eoindicador, sq_lcfonte_recurso,
                 sq_lcmodalidade,       sq_lcjulgamento,          sq_lcsituacao,  numero_original, 
                 data_recebimento,      processo,                 indice_base,    tipo_reajuste, 
                 limite_variacao,       data_homologacao,         data_diario_oficial,
                 pagina_diario_oficial, financeiro_unico,         numero_ata,     numero_certame,
                 arp,                   interno,                  w_financeiro
            from cl_solicitacao
           where sq_siw_solicitacao = p_copia
         )
         where sq_siw_solicitacao = w_chave;
         
         -- Copia os itens da solicitação
         insert into cl_solicitacao_item
           (sq_solicitacao_item,              sq_siw_solicitacao, sq_material, quantidade, sq_unidade_medida, prazo_garantia, vistoria_previa, catalogo, prazo_manutencao)
         (select nextVal('sq_solicitacao_item'), w_chave,            sq_material, quantidade, sq_unidade_medida, prazo_garantia, vistoria_previa, catalogo, prazo_manutencao
            from cl_solicitacao_item a
           where sq_siw_solicitacao = p_copia
             and cancelado          = 'N'
         );
      End If;
   Elsif p_operacao = 'T' Then -- Atendimento
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          sq_plano         = p_plano,
          sq_cc            = p_sqcc,
          sq_solic_pai     = p_solic_pai,
          ultima_alteracao = w_data
      where sq_siw_solicitacao = p_chave;

      -- Atualiza a tabela de compras
      Update cl_solicitacao set sq_financeiro = w_financeiro where sq_siw_solicitacao = p_chave;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          sq_plano         = p_plano,
          sq_cc            = p_sqcc,
          sq_solic_pai     = p_solic_pai,
          sq_unidade       = p_unidade,
          solicitante      = p_solicitante,
          justificativa    = p_justificativa,
          observacao       = p_observacao,
          executor         = p_executor,
          inicio           = p_inicio,
          fim              = p_fim,
          valor            = coalesce(p_valor,valor),
          ultima_alteracao = w_data,
          sq_cidade_origem = p_cidade
      where sq_siw_solicitacao = p_chave;
      If p_codigo is not null Then
         update siw_solicitacao set
             codigo_interno = p_codigo
          where sq_siw_solicitacao = p_chave;
      End If;
      select sq_unidade_pai into w_unidade_pai from cl_unidade where sq_unidade = p_unidade;
      -- Atualiza a tabela de solicitacoes
      If w_unidade_pai is not null Then
         Update cl_solicitacao set
            ( prioridade,       decisao_judicial,     numero_original,
              data_recebimento, aviso_prox_conc,      sq_unidade,
              arp,              interno,              sq_especie_documento,
              dias_aviso,       sq_financeiro
            ) = 
         (select
              p_prioridade,       p_decisao_judicial, p_numero_original, 
              p_data_recebimento, p_aviso,            a.sq_unidade_pai,
              p_arp,              p_interno,          p_especie_documento,
              p_dias,             w_financeiro
           from cl_unidade a
          where a.sq_unidade = p_unidade
         )
         where sq_siw_solicitacao = p_chave;
      Else 
         Update cl_solicitacao set
            ( prioridade,         decisao_judicial,   numero_original,
              data_recebimento,   aviso_prox_conc,    sq_unidade,
              arp,                interno,            sq_especie_documento,
              dias_aviso,         sq_financeiro
            ) = 
         (select
              p_prioridade,       p_decisao_judicial, p_numero_original, 
              p_data_recebimento, p_aviso,            p_unidade,
              p_arp,              p_interno,          p_especie_documento,
              p_dias,             w_financeiro
          
         )
         where sq_siw_solicitacao = p_chave;      
      End If;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Recupera os dados do menu
      select * into w_menu from siw_menu where sq_menu = p_menu;
      
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;

      -- Se não tem atividades vinculadas nem foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If w_log_sol > 1 or w_menu.cancela_sem_tramite = 'S' Then
         -- Insere log de cancelamento
         Insert Into siw_solic_log
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa,
             sq_siw_tramite,            data,                 devolucao,
             observacao
            )
         (select
             nextVal('sq_siw_solic_log'),  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          w_data,               'N',
             coalesce(p_observacao_log,'Cancelamento')
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

         -- Remove os registros vinculados a solicitacao
         DELETE FROM siw_solic_arquivo           where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_arquivo                 where sq_siw_arquivo     in (w_arq);

         -- Remove os itens da solicitacao
         DELETE FROM cl_solicitacao_item_vinc a  where a.item_pedido    in (select sq_solicitacao_item from cl_solicitacao_item where sq_siw_solicitacao = p_chave)
                                               or a.item_licitacao in (select sq_solicitacao_item from cl_solicitacao_item where sq_siw_solicitacao = p_chave);
         DELETE FROM cl_solicitacao_item         where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitacao
         DELETE FROM cl_solicitacao              where sq_siw_solicitacao = p_chave;

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

   If p_operacao in ('I','A','T') and p_objetivo is not null Then
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