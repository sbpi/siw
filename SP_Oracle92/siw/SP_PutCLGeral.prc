create or replace procedure SP_PutCLGeral
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_copia               in  number   default null,
    p_menu                in number,
    p_unidade             in number    default null,
    p_solicitante         in number    default null,
    p_cadastrador         in number    default null,
    p_executor            in number    default null,
    p_plano               in number    default null,
    p_objetivo            in varchar2  default null,    
    p_sqcc                in number    default null,
    p_solic_pai           in number    default null,
    p_justificativa       in varchar2  default null,
    p_observacao          in varchar2  default null,
    p_inicio              in date      default null,
    p_fim                 in date      default null,
    p_codigo              in varchar2  default null,
    p_prioridade          in number    default null,
    p_aviso               in varchar2  default null,
    p_dias                in number    default null,
    p_cidade              in number    default null,
    p_decisao_judicial    in varchar2  default null,
    p_numero_original     in varchar2  default null,
    p_data_recebimento    in date      default null,
    p_arp                 in varchar2  default null,
    p_interno             in varchar2  default null,
    p_chave_nova          out number
   ) is
   w_arq       varchar2(4000) := ', ';
   w_chave     number(18);
   w_log_sol   number(18);
   w_item      varchar2(18);   
   w_objetivo  varchar2(200) := p_objetivo ||',';   
   w_codigo    varchar(60);
   w_unidade_pai number(18);
   w_data        date;
   w_dias      number(3);


   cursor c_arquivos is
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
begin
   -- Recupera a hora atual
   w_data := sysdate;
   
   -- Se for decisão judicial calcula o número de dias para aviso
   If p_decisao_judicial = 'S' Then
      w_dias := round((p_fim-w_data+1),0);
   Else
      w_dias := p_dias;
   End If;
   
   If p_operacao <> 'I' Then -- Inclusão
      -- Remove as vinculações existentes para a solicitação
      delete siw_solicitacao_objetivo where sq_siw_solicitacao = coalesce(w_chave, p_chave);
   End If;

   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_solicitacao.nextval into w_Chave from dual;

      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante,
         cadastrador,        executor,      justificativa,       inicio,
         fim,                inclusao,      ultima_alteracao,    sq_unidade,
         sq_cc,              sq_solic_pai,  sq_cidade_origem,    sq_plano,
         codigo_interno,     observacao)
      (select
         w_Chave,            p_menu,        a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_executor,    p_justificativa,     case p_decisao_judicial when 'S' then p_inicio else w_data end,
         p_fim,              w_data,        w_data,              p_unidade,
         p_sqcc,             p_solic_pai,   p_cidade,            p_plano,
         p_codigo,           p_observacao
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      select sq_unidade_pai into w_unidade_pai from cl_unidade where sq_unidade = p_unidade;
      -- Insere registro em cl_solicitacao
      If w_unidade_pai is not null Then
         Insert into cl_solicitacao
            ( sq_siw_solicitacao,  prioridade,       decisao_judicial,  numero_original,
              data_recebimento,    aviso_prox_conc,  dias_aviso,        sq_unidade,
              arp,                 interno           
            )
         (select
              w_chave,              p_prioridade,    p_decisao_judicial, p_numero_original, 
              p_data_recebimento,   p_aviso,         w_dias,             a.sq_unidade_pai,
              p_arp,                p_interno
           from cl_unidade a
          where a.sq_unidade = p_unidade
         );
      Else
         Insert into cl_solicitacao
            ( sq_siw_solicitacao,  prioridade,       decisao_judicial,  numero_original,
              data_recebimento,    aviso_prox_conc,  dias_aviso,        sq_unidade,
              arp,                 interno
            )
         (select
              w_chave,              p_prioridade,    p_decisao_judicial, p_numero_original, 
              p_data_recebimento,   p_aviso,         w_dias,             p_unidade,
              p_arp,                p_interno
           from dual
         );
      
      End If;
      If p_codigo is null Then
         geracodigointerno(w_chave,null,w_codigo);
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
          sq_siw_solic_log.nextval,  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          w_data,             'N',
          'Cadastramento inicial'
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
                 arp,                   interno
                ) = 
         (select sq_especie_documento,  sq_especificacao_despesa, sq_eoindicador, sq_lcfonte_recurso,
                 sq_lcmodalidade,       sq_lcjulgamento,          sq_lcsituacao,  numero_original, 
                 data_recebimento,      processo,                 indice_base,    tipo_reajuste, 
                 limite_variacao,       data_homologacao,         data_diario_oficial,
                 pagina_diario_oficial, financeiro_unico,         numero_ata,     numero_certame,
                 arp,                   interno
            from cl_solicitacao
           where sq_siw_solicitacao = p_copia
         )
         where sq_siw_solicitacao = w_chave;
      End If;
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
              data_recebimento, aviso_prox_conc,      dias_aviso,        sq_unidade,
              arp,              interno
            ) = 
         (select
              p_prioridade,       p_decisao_judicial, p_numero_original, 
              p_data_recebimento, p_aviso,            w_dias,            a.sq_unidade_pai,
              p_arp,              p_interno
           from cl_unidade a
          where a.sq_unidade = p_unidade
         )
         where sq_siw_solicitacao = p_chave;
      Else 
         Update cl_solicitacao set
            ( prioridade,       decisao_judicial,     numero_original,
              data_recebimento, aviso_prox_conc,      dias_aviso,        sq_unidade,
              arp,              interno
            ) = 
         (select
              p_prioridade,       p_decisao_judicial, p_numero_original, 
              p_data_recebimento, p_aviso,            w_dias,            p_unidade,
              p_arp,              p_interno
           from dual
         )
         where sq_siw_solicitacao = p_chave;      
      End If;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;

      -- Se não tem atividades vinculadas nem foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If w_log_sol > 1 Then
         -- Insere log de cancelamento
         Insert Into siw_solic_log
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa,
             sq_siw_tramite,            data,                 devolucao,
             observacao
            )
         (select
             sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          w_data,               'N',
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

         -- Remove os registros vinculados a solicitacao
         delete siw_solic_arquivo           where sq_siw_solicitacao = p_chave;
         delete siw_arquivo                 where sq_siw_arquivo     in (w_arq);

         -- Remove o registro na tabela de solicitacao
         delete cl_solicitacao              where sq_siw_solicitacao = p_chave;

         -- Remove o log da solicitação
         delete siw_solic_log               where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao             where sq_siw_solicitacao = p_chave;
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
   End If;
end SP_PutCLGeral;
/
