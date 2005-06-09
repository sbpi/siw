create or replace procedure SP_PutTarefaGeral_IS
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_copia               in  number   default null,
    p_menu                in number,
    p_unidade             in number    default null,
    p_solicitante         in number    default null,
    p_proponente          in varchar2  default null,
    p_cadastrador         in number    default null,
    p_executor            in number    default null,
    p_descricao           in varchar2  default null,
    p_justificativa       in varchar2  default null,
    p_ordem               in number    default null,
    p_inicio              in date      default null,
    p_fim                 in date      default null,
    p_valor               in number    default null,
    p_data_hora           in varchar2  default null,
    p_unid_resp           in number    default null,
    p_titulo              in varchar2  default null,    
    p_assunto             in varchar2  default null,
    p_prioridade          in number    default null,
    p_aviso               in varchar2  default null,
    p_dias                in number    default null,
    p_cidade              in number    default null,
    p_palavra_chave       in varchar2  default null,
    p_inicio_real         in date      default null,
    p_fim_real            in date      default null,
    p_concluida           in varchar2  default null,
    p_data_conclusao      in date      default null,
    p_nota_conclusao      in varchar2  default null,
    p_custo_real          in number    default null,
    p_opiniao             in number    default null,
    p_projeto             in number    default null,
    p_atividade           in number    default null,
    p_projeto_ant         in number    default null,
    p_atividade_ant       in number    default null,
    p_chave_nova          out number
   ) is
   w_chave   number(18);
   w_log_sol number(18);
   w_log_esp number(18);
   w_arq     varchar2(4000) := ', ';
   
   cursor c_arquivos is
    select t.sq_siw_arquivo from siw.siw_solic_arquivo t where t.sq_siw_solicitacao = p_chave;
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select siw.sq_siw_solicitacao.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw.siw_solicitacao (
         sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante, 
         cadastrador,        executor,      descricao,           justificativa, 
         inicio,             fim,           inclusao,            ultima_alteracao, 
         conclusao,          valor,         opiniao,             data_hora, 
         sq_unidade,         sq_cidade_origem,                   palavra_chave,
         sq_solic_pai)
      (select 
         w_Chave,            p_menu,        a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_executor,    p_descricao,         p_justificativa,
         p_inicio,           p_fim,         sysdate,             sysdate,
         null,               p_valor,       null,                p_data_hora,
         p_unidade,          p_cidade,                           p_palavra_chave,
         p_projeto
         from siw.siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Insere registro em GD_DEMANDA
      Insert into siw.gd_demanda
         ( sq_siw_solicitacao,  sq_unidade_resp, assunto,           prioridade,
           aviso_prox_conc,     dias_aviso,      inicio_real,       fim_real,
           concluida,           data_conclusao,  nota_conclusao,    custo_real,
           proponente,          ordem
         )
      (select
           w_chave,             p_unid_resp,     p_assunto,         p_prioridade,
           p_aviso,             p_dias,          null,              null,
           'N',                 null,            null,              0,
           p_proponente,        p_ordem
        from dual
      );
      
      -- Insere registro em IS_TAREFA
      Insert into is_tarefa
         ( sq_siw_solicitacao, titulo )
      (select
           w_chave, p_titulo from dual);
           
      -- Insere log da solicitação
      Insert Into siw.siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (select 
          siw.sq_siw_solic_log.nextval,  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          sysdate,            'N',
          'Cadastramento inicial'
         from siw.siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
           
      -- Se receber p_atividade, grava na tabela de atividades de projeto
      If p_atividade is not null Then
         Insert Into siw.pj_etapa_demanda 
                (sq_etapa_demanda,             sq_projeto_etapa, sq_siw_solicitacao)
         Values (siw.sq_etapa_demanda.nextval, p_atividade,      w_chave);
      End If;

   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw.siw_solicitacao set
          sq_solic_pai     = p_projeto,
          solicitante      = p_solicitante,
          executor         = p_executor,
          descricao        = trim(p_descricao), 
          justificativa    = trim(p_justificativa),
          inicio           = p_inicio,
          fim              = p_fim,
          ultima_alteracao = sysdate,
          valor            = p_valor,
          sq_cidade_origem = p_cidade,
          palavra_chave    = p_palavra_chave
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas
      Update siw.gd_demanda set
          sq_unidade_resp  = p_unid_resp,
          proponente       = p_proponente,
          assunto          = trim(p_assunto),
          prioridade       = p_prioridade,
          aviso_prox_conc  = p_aviso,
          dias_aviso       = p_dias,
          inicio_real      = p_inicio_real,
          ordem            = p_ordem
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela IS_TAREFA
      Update is_tarefa set
          titulo           = p_titulo
      where sq_siw_solicitacao = p_chave;

      -- Verifica a tabela de etapas, se receber p_atividade
      If Nvl(p_atividade_ant,0) <> Nvl(p_atividade,0) Then
         -- Apaga a vinculação com os dados antigos
         delete siw.pj_etapa_demanda where sq_siw_solicitacao = p_chave and Nvl(sq_projeto_etapa,0) = Nvl(p_atividade_ant,0);
         
         If p_atividade is not null then
            -- Cria a vinculação com os novos dados
            Insert Into siw.pj_etapa_demanda 
                   (sq_etapa_demanda,         sq_projeto_etapa, sq_siw_solicitacao)
            Values (siw.sq_etapa_demanda.nextval, p_atividade,      p_chave);
         End If;
      End If;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw.siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from siw.gd_demanda_log where sq_siw_solicitacao = p_chave;
      
      -- Se não foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If (w_log_sol + w_log_esp) > 1 Then
         -- Insere log de cancelamento
         Insert Into siw.siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
             sq_siw_tramite,            data,                 devolucao, 
             observacao
            )
         (select 
             siw.sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          sysdate,              'N',
             'Cancelamento'
            from siw.siw_solicitacao a
           where a.sq_siw_solicitacao = p_chave
         );
         
         -- Atualiza a situação da demanda
         update siw.gd_demanda set concluida = 'S' where sq_siw_solicitacao = p_chave;

         -- Recupera a chave que indica que a solicitação está cancelada
         select a.sq_siw_tramite into w_chave from siw.siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';
         
         -- Atualiza a situação da solicitação
         update siw.siw_solicitacao set sq_siw_tramite = w_chave where sq_siw_solicitacao = p_chave;
      Else
         
         delete is_tarefa      where sq_siw_solicitacao = p_chave;
         
         -- Remove o registro na tabela de demandas
         delete siw.gd_demanda where sq_siw_solicitacao = p_chave;
         
         -- Monta string com a chave dos arquivos ligados à solicitação informada
         for crec in c_arquivos loop
            w_arq := w_arq || crec.sq_siw_arquivo;
         end loop;
         w_arq := substr(w_arq, 3, length(w_arq));
         
         delete siw.siw_solic_arquivo         where sq_siw_solicitacao = p_chave;
         delete siw.siw_arquivo               where sq_siw_arquivo     in (w_arq);
            
         -- Remove o log da solicitação
         delete siw.siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw.siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   Elsif p_operacao = 'V' Then -- Encaminhamento
      -- Ativa registro
      null;
   Elsif p_operacao = 'C' Then -- Conclusão
      -- Atualiza a tabela de solicitações com os dados da conclusão
      Update siw.siw_solicitacao set
          conclusao        = p_data_conclusao,
          ultima_alteracao = sysdate,
          sq_siw_tramite   = (select sq_siw_tramite from siw.siw_tramite where sq_menu = p_menu and sigla='AT')
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas com os dados da conclusão
      Update siw.gd_demanda set
          fim_real        = p_fim_real,
          concluida       = p_concluida,
          data_conclusao  = p_data_conclusao,
          nota_conclusao  = trim(p_nota_conclusao),
          custo_real      = p_custo_real
      where sq_siw_solicitacao = p_chave;
   Elsif p_operacao = 'O' Then -- Opinião
      -- Atualiza a tabela de solicitações com a opinião do solicitante
      Update siw.siw_solicitacao set
          opiniao         = p_opiniao
      where sq_siw_solicitacao = p_chave;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end SP_PutTarefaGeral_IS;
/

