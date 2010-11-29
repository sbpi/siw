create or replace procedure SP_PutPAEmpGeral
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_copia               in  number   default null,
    p_menu                in number,
    p_unidade             in number    default null,
    p_solicitante         in number    default null,
    p_cadastrador         in number    default null,
    p_justificativa       in varchar2  default null,
    p_fim                 in date      default null,
    p_cidade              in number    default null
   ) is
   w_codigo      varchar2(30);
   w_chave       number(18);
   w_log_sol     number(18);
   w_data        date;
   w_parametro   pa_parametro%rowtype;
   w_menu        siw_menu%rowtype;

begin
   -- Recupera a hora atual
   w_data := sysdate;
   
   -- Recupera os parâmetros do módulo
   select * into w_parametro
     from pa_parametro where cliente = (select sq_pessoa from siw_menu where sq_menu = p_menu);
   
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_solicitacao.nextval into w_Chave from dual;

      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,              sq_siw_tramite,      solicitante,
         cadastrador,        inicio,               fim,                 justificativa,
         inclusao,           ultima_alteracao,     sq_unidade,          sq_cidade_origem)
      (select
         w_Chave,            p_menu,               a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      trunc(sysdate),       p_fim,               p_justificativa,
         w_data,             w_data,               p_unidade,           p_cidade
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Gera código interno da solicitação
      geracodigointerno(w_chave,null,w_codigo);
      update siw_solicitacao set
            codigo_interno = w_codigo
      where sq_siw_solicitacao = w_chave;
      
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
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          justificativa    = p_justificativa,
          fim              = p_fim,
          ultima_alteracao = w_data,
          sq_cidade_origem = p_cidade
      where sq_siw_solicitacao = p_chave;
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
         -- Remove protocolos e caixas da solicitação
         delete pa_emprestimo_item  where sq_siw_solicitacao = p_chave;
         delete pa_emprestimo_caixa where sq_siw_solicitacao = p_chave;

         -- Remove o log da solicitação
         delete siw_solic_log       where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao     where sq_siw_solicitacao = p_chave;
      End If;
   End If;
end SP_PutPAEmpGeral;
/
