create or replace FUNCTION sp_putGpFerias
   (p_operacao                    varchar,
    p_chave                        numeric,
    p_menu                        numeric,
    p_unidade                     numeric,
    p_solicitante                 numeric,
    p_cadastrador                 numeric,
    p_descricao                   varchar,
    p_justificativa               varchar,    
    p_sq_contrato_colaborador     numeric,
    p_inicio                      date, 
    p_inicio_periodo              varchar,
    p_fim                         date,
    p_fim_periodo                 varchar,
--    p_gozo_previsto,            numeric,
--    p_gozo_efetivo                numeric,
    p_inicio_aquisitivo           date, 
    p_fim_aquisitivo              date, 
    p_abono_pecuniario            varchar,
    p_data_hora                   varchar,
    p_cidade                      numeric,   
    p_chave_nova                  numeric
    ) RETURNS VOID AS $$
DECLARE
   
   w_Chave   numeric(18);
   w_log_sol numeric(18);
BEGIN
   -- Name                     Type         Nullable  Default  Comments
   -- SQ_SIW_SOLICITACAO       numeric(18)                      Chave de SIW_SOLICITACAO. Indica a que solicitação o pedido de férias está vinculado.
   -- SQ_CONTRATO_COLABORADOR  numeric(18)                      Chave de GP_CONTRATO_COLABORADOR. Indica a que contrato do colaborador o pedido de férias está vinculado.
   -- INICIO_DATA              date                            Data de início do gozo de férias.
   -- INICIO_PERIODO           varchar(1)            'M'      Indica se o início das férias é no turno matutino (M) ou vespertino (T).
   -- FIM_DATA                 date                            Data de término do gozo de férias.
   -- FIM_PERIODO              varchar(1)            'T'      Indica se o término das férias é no turno matutino (M) ou vespertino (T).
   -- GOZO_PREVISTO            numeric(3,1)            0        Este campo é atualizado pela trigger TG_GP_FERIAS, quando o registro é inserido ou atualizado.
   -- GOZO_EFETIVO             numeric(3,1)            0        Este campo é atualizado no momento da conclusão da solicitação de férias, calculado a partir do gozo previsto menos as interrupções registradas.
   -- INICIO_AQUISITIVO        date                            Início do período aquisitivo ao qual o pedido de férias refere-se.
   -- FIM_AQUISITIVO           date                            Fim do período aquisitivo ao qual o pedido de férias refere-se.
  -- ABONO_PECUNIARIO         varchar(1)            'N'      Indica a necessidade de pagamento de abono pecuniário.
  
  If p_operacao = 'I' Then
    -- Recupera a próxima chave
    select nextVal('sq_siw_solicitacao') into w_Chave;
    
    -- Insere registro em SIW_SOLICITACAO
    insert into siw_solicitacao (
       sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante, 
       cadastrador,        descricao,     justificativa,       inicio,
       fim,                inclusao,      ultima_alteracao,    data_hora,
       sq_unidade,         sq_cidade_origem)
    (select 
       w_Chave,            p_menu,        a.sq_siw_tramite,    p_solicitante,
       p_cadastrador,      p_descricao,   p_justificativa,     p_inicio,
       p_fim,              now(),       now(),             p_data_hora,
       p_unidade,          p_cidade
       from siw_tramite a
      where a.sq_menu = p_menu
        and a.sigla   = 'CI'
    );

    -- Insere registro em gp_ferias
    insert into gp_ferias
      (sq_siw_solicitacao, sq_contrato_colaborador, inicio_data, inicio_periodo, fim_data, fim_periodo, inicio_aquisitivo, fim_aquisitivo, abono_pecuniario)
    values
      (w_Chave, p_sq_contrato_colaborador, p_inicio, p_inicio_periodo, p_fim, p_fim_periodo, p_inicio_aquisitivo, p_fim_aquisitivo, p_abono_pecuniario);
      
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
  Elsif p_operacao = 'A' Then    
    -- Atualiza a tabela de solicitações
    Update siw_solicitacao set
        inicio           = p_inicio,
        fim              = p_fim,
        ultima_alteracao = now()
    where sq_siw_solicitacao = p_chave;  
    
    update gp_ferias
       set inicio_data             = p_inicio,
           inicio_periodo          = p_inicio_periodo,
           fim_data                = p_fim,
           fim_periodo             = p_fim_periodo,
           inicio_aquisitivo       = p_inicio_aquisitivo,
           fim_aquisitivo          = p_fim_aquisitivo,
           abono_pecuniario        = p_abono_pecuniario
     where sq_siw_solicitacao      = p_chave;
  Elsif p_operacao = 'E' Then       
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
         -- Remove o log da solicitação
         DELETE FROM siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro da tabela de férias
         DELETE FROM gp_ferias where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações         
         DELETE FROM siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
  End If;
  -- Devolve a chave
  If p_chave is not null
    Then p_chave_nova := p_chave;
    Else p_chave_nova := w_chave;
  End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;