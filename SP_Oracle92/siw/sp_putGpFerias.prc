create or replace procedure sp_putGpFerias
   (p_operacao                    in varchar2,
    p_chave                       in  number   default null,
    p_menu                        in number,
    p_unidade                     in number    default null,
    p_solicitante                 in number    default null,
    p_cadastrador                 in number    default null,
    p_descricao                   in varchar2  default null,
    p_justificativa               in varchar2  default null,    
    p_sq_contrato_colaborador     in number,
    p_inicio                      in date, 
    p_inicio_periodo              in varchar2,
    p_fim                         in date,
    p_fim_periodo                 in varchar2,
--    p_gozo_previsto,            in number,
--    p_gozo_efetivo                in number,
    p_inicio_aquisitivo           in date, 
    p_fim_aquisitivo              in date, 
    p_abono_pecuniario            in varchar2,
    p_data_hora                   in varchar2  default null,
    p_cidade                      in number    default null,   
    p_chave_nova                  out number
    ) is
   
   w_Chave   number(18);
   w_log_sol number(18);
begin
   -- Name                     Type         Nullable  Default  Comments
   -- SQ_SIW_SOLICITACAO       NUMBER(18)                      Chave de SIW_SOLICITACAO. Indica a que solicita��o o pedido de f�rias est� vinculado.
   -- SQ_CONTRATO_COLABORADOR  NUMBER(18)                      Chave de GP_CONTRATO_COLABORADOR. Indica a que contrato do colaborador o pedido de f�rias est� vinculado.
   -- INICIO_DATA              DATE                            Data de in�cio do gozo de f�rias.
   -- INICIO_PERIODO           VARCHAR2(1)            'M'      Indica se o in�cio das f�rias � no turno matutino (M) ou vespertino (T).
   -- FIM_DATA                 DATE                            Data de t�rmino do gozo de f�rias.
   -- FIM_PERIODO              VARCHAR2(1)            'T'      Indica se o t�rmino das f�rias � no turno matutino (M) ou vespertino (T).
   -- GOZO_PREVISTO            NUMBER(3,1)            0        Este campo � atualizado pela trigger TG_GP_FERIAS, quando o registro � inserido ou atualizado.
   -- GOZO_EFETIVO             NUMBER(3,1)            0        Este campo � atualizado no momento da conclus�o da solicita��o de f�rias, calculado a partir do gozo previsto menos as interrup��es registradas.
   -- INICIO_AQUISITIVO        DATE                            In�cio do per�odo aquisitivo ao qual o pedido de f�rias refere-se.
   -- FIM_AQUISITIVO           DATE                            Fim do per�odo aquisitivo ao qual o pedido de f�rias refere-se.
  -- ABONO_PECUNIARIO         VARCHAR2(1)            'N'      Indica a necessidade de pagamento de abono pecuni�rio.
  
  If p_operacao = 'I' Then
    -- Recupera a pr�xima chave
    select sq_siw_solicitacao.nextval into w_Chave from dual;
    
    -- Insere registro em SIW_SOLICITACAO
    insert into siw_solicitacao (
       sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante, 
       cadastrador,        descricao,     justificativa,       inicio,
       fim,                inclusao,      ultima_alteracao,    data_hora,
       sq_unidade,         sq_cidade_origem)
    (select 
       w_Chave,            p_menu,        a.sq_siw_tramite,    p_solicitante,
       p_cadastrador,      p_descricao,   p_justificativa,     p_inicio,
       p_fim,              sysdate,       sysdate,             p_data_hora,
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
      
    -- Insere log da solicita��o
    Insert Into siw_solic_log 
       (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
        sq_siw_tramite,            data,               devolucao, 
        observacao
       )
    (select 
        sq_siw_solic_log.nextval,  w_chave,            p_cadastrador,
        a.sq_siw_tramite,          sysdate,            'N',
        'Cadastramento inicial'
       from siw_tramite a
      where a.sq_menu = p_menu
        and a.sigla   = 'CI'
    );            
  Elsif p_operacao = 'A' Then    
    -- Atualiza a tabela de solicita��es
    Update siw_solicitacao set
        inicio           = p_inicio,
        fim              = p_fim,
        ultima_alteracao = sysdate
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
  -- Verifica a quantidade de logs da solicita��o
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      
      -- Se n�o foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contr�rio, coloca a solicita��o como cancelada.
      If w_log_sol > 1 Then
         -- Insere log de cancelamento
         Insert Into siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
             sq_siw_tramite,            data,                 devolucao, 
             observacao
            )
         (select 
             sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          sysdate,              'N',
             'Cancelamento'
            from siw_solicitacao a
           where a.sq_siw_solicitacao = p_chave
         );
         
         -- Recupera a chave que indica que a solicita��o est� cancelada
         select a.sq_siw_tramite into w_chave from siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';
         
         -- Atualiza a situa��o da solicita��o
         update siw_solicitacao set sq_siw_tramite = w_chave where sq_siw_solicitacao = p_chave;
      Else
         -- Remove o log da solicita��o
         delete siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro da tabela de f�rias
         delete gp_ferias where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicita��es         
         delete siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
  End If;
  -- Devolve a chave
  If p_chave is not null
    Then p_chave_nova := p_chave;
    Else p_chave_nova := w_chave;
  End If;
end sp_putGpFerias;
/
