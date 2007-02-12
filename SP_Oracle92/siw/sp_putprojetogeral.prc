create or replace procedure SP_PutProjetoGeral
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_copia               in  number   default null,
    p_menu                in number,
    p_unidade             in number    default null,
    p_solicitante         in number    default null,
    p_proponente          in varchar2  default null,
    p_cadastrador         in number    default null,
    p_executor            in number    default null,
    p_objetivo            in number    default null,
    p_sqcc                in number    default null,
    p_solic_pai           in number    default null,
    p_descricao           in varchar2  default null,
    p_justificativa       in varchar2  default null,
    p_inicio              in date      default null,
    p_fim                 in date      default null,
    p_valor               in number    default null,
    p_data_hora           in varchar2  default null,
    p_unid_resp           in number    default null,
    p_titulo              in varchar2  default null,
    p_prioridade          in number    default null,
    p_aviso               in varchar2  default null,
    p_dias                in number    default null,
    p_cidade              in number    default null,
    p_palavra_chave       in varchar2  default null,
    p_vincula_contrato    in varchar2  default null,
    p_vincula_viagem      in varchar2  default null,
    p_sq_acao_ppa         in number    default null,
    p_sq_orprioridade     in number    default null,
    p_selecionada_mpog    in varchar2  default null,
    p_selecionada_relev   in varchar2  default null,
    p_sq_tipo_pessoa      in varchar2  default null,
    p_chave_nova          out number
   ) is
   w_arq     varchar2(4000) := ', ';
   w_chave   number(18);
   w_chave1  number(18);
   w_log_sol number(18);
   w_log_esp number(18);
   w_ativ    number(18);
   i         number(10) := 0;

   type tb_recurso_pai is table of number(10) index by binary_integer;
   w_recurso_pai tb_recurso_pai;
    
   type rec_etapa is record (
       sq_chave_destino       number(10) := null,
       sq_chave_origem        number(10) := null,
       sq_chave_pai_origem    number(10) := null
      );
   type tb_etapa is table of rec_etapa index by binary_integer;
   type tb_etapa_pai is table of number(10) index by binary_integer;
    
   w_etapa     tb_etapa;
   w_etapa_pai tb_etapa_pai;

   cursor c_recursos is
     select * from pj_projeto_recurso where sq_siw_solicitacao = p_copia;
     
   cursor c_etapas is
      select * from pj_projeto_etapa where sq_siw_solicitacao = p_copia;
   
   cursor c_etapa_recurso is
      select a.*
        from pj_recurso_etapa              a
             inner join pj_projeto_recurso b on (a.sq_projeto_recurso = b.sq_projeto_recurso)
       where b.sq_siw_solicitacao = p_copia;

   cursor c_atividades is
      select * from siw_solicitacao t where t.sq_solic_pai = p_chave;
   
   cursor c_arquivos is
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_solicitacao.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante, 
         cadastrador,        executor,      descricao,           justificativa,      
         inicio,             fim,           inclusao,            ultima_alteracao, 
         conclusao,          valor,         opiniao,             data_hora, 
         sq_unidade,         sq_cc,         sq_solic_pai,        sq_cidade_origem,
         palavra_chave,      sq_peobjetivo)
      (select 
         w_Chave,            p_menu,        a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_executor,    p_descricao,         p_justificativa,
         p_inicio,           p_fim,         sysdate,             sysdate,
         null,               p_valor,       null,                p_data_hora,
         p_unidade,          p_sqcc,        p_solic_pai,         p_cidade,
         p_palavra_chave,    p_objetivo
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Insere registro em pj_projeto
      Insert into pj_projeto
         ( sq_siw_solicitacao,  sq_unidade_resp, titulo,            prioridade,
           aviso_prox_conc,     dias_aviso,      inicio_real,       fim_real,
           concluida,           data_conclusao,  nota_conclusao,    custo_real,
           proponente,          sq_tipo_pessoa,  vincula_contrato,   vincula_viagem
         )
      (select
           w_chave,             p_unid_resp,     p_titulo,          p_prioridade,
           p_aviso,             p_dias,          null,              null,
           'N',                 null,            null,              0,
           p_proponente,        p_sq_tipo_pessoa,Nvl(p_vincula_contrato,'N'), Nvl(p_vincula_viagem,'N')
        from dual
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
          sq_siw_solic_log.nextval,  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          sysdate,            'N',
          'Cadastramento inicial'
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
           
      -- Se o projeto foi copiado de outra, grava os dados complementares
      If p_copia is not null Then
         -- Insere registro na tabela de interessados
         Insert Into pj_projeto_interes 
            ( sq_pessoa,   sq_siw_solicitacao,   tipo_visao,    envia_email )
         (Select
              a.sq_pessoa, w_chave,              a.tipo_visao,  a.envia_email 
           from pj_projeto_interes a
          where a.sq_siw_solicitacao = p_copia
         );
         -- Insere registro na tabela de áreas envolvidas
         Insert Into pj_projeto_envolv 
            ( sq_unidade,   sq_siw_solicitacao,   papel )
         (Select
              a.sq_unidade, w_chave,              a.papel
            from pj_projeto_envolv a
           where a.sq_siw_solicitacao = p_copia
          );

          -- Insere recursos do projeto
          for crec in c_recursos loop

             -- recupera a próxima chave do recurso
             select sq_projeto_recurso.nextval into w_chave1 from dual;

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
             select sq_projeto_etapa.nextval into w_chave1 from dual;

             -- Guarda pai do registro original
             i := i + 1;
             w_etapa(i).sq_chave_destino    := w_chave1;
             w_etapa(i).sq_chave_origem     := crec.sq_projeto_etapa;
             w_etapa(i).sq_chave_pai_origem := crec.sq_etapa_pai;
             
             w_etapa_pai(crec.sq_projeto_etapa) := w_chave1;
        
             -- insere o recurso
             Insert Into pj_projeto_etapa
                ( sq_projeto_etapa,   sq_siw_solicitacao,       ordem,              titulo, 
                  descricao,          inicio_previsto,          fim_previsto,       inicio_real,
                  fim_real,           perc_conclusao,           orcamento,          sq_unidade,
                  sq_pessoa,          vincula_atividade,        sq_pessoa_atualizacao)
             Values
                ( w_chave1,           w_chave,                  crec.ordem,         crec.titulo,
                  crec.descricao,     crec.inicio_previsto,     crec.fim_previsto,  null,
                  null,               0,                        crec.orcamento,     crec.sq_unidade,
                  crec.sq_pessoa,     crec.vincula_atividade,   crec.sq_pessoa_atualizacao);

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
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          sq_peobjetivo    = p_objetivo,
          sq_cc            = p_sqcc,
          sq_solic_pai     = p_solic_pai,
          descricao        = coalesce(p_descricao,descricao),
          justificativa    = coalesce(p_justificativa,justificativa),
          solicitante      = p_solicitante,
          executor         = p_executor,
          inicio           = p_inicio,
          fim              = p_fim,
          ultima_alteracao = sysdate,
          valor            = p_valor,
          sq_cidade_origem = p_cidade,
          palavra_chave    = p_palavra_chave
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de projetos
      Update pj_projeto set
          sq_unidade_resp  = p_unid_resp,
          proponente       = p_proponente,
          titulo           = trim(p_titulo),
          prioridade       = p_prioridade,
          aviso_prox_conc  = p_aviso,
          dias_aviso       = p_dias,
          sq_tipo_pessoa   = p_sq_tipo_pessoa,
          vincula_contrato = Nvl(p_vincula_contrato,'N'),
          vincula_viagem   = Nvl(p_vincula_viagem,'N')
      where sq_siw_solicitacao = p_chave;

      If Nvl(p_sq_tipo_pessoa,0) = 1 Then
         update pj_projeto set preposto = null where sq_siw_solicitacao = p_chave;
         delete pj_projeto_representante where sq_siw_solicitacao = p_chave;
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
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from pj_projeto_log where sq_siw_solicitacao = p_chave;
      select count(*) into w_ativ    from siw_solicitacao where sq_solic_pai      = p_chave;
      
      -- Se não tem atividades vinculadas nem foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If (w_log_sol + w_log_esp + w_ativ) > 1 Then
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
                 sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
                 a.sq_siw_tramite,          sysdate,              'N',
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
         
         -- Remove os registros vinculados ao projeto
         delete siw_solic_arquivo where sq_siw_solicitacao = p_chave;
         delete siw_arquivo       where sq_siw_arquivo     in (w_arq);
         
         delete or_acao_prioridade where sq_siw_solicitacao = p_chave;
         delete or_acao_financ     where sq_siw_solicitacao = p_chave;
         delete or_acao            where sq_siw_solicitacao = p_chave;
         delete pj_projeto_representante where sq_siw_solicitacao = p_chave;
         delete pj_projeto_envolv  where sq_siw_solicitacao = p_chave;
         delete pj_projeto_interes where sq_siw_solicitacao = p_chave;
         delete pj_recurso_etapa   where sq_projeto_etapa in (select sq_projeto_etapa from pj_projeto_etapa where sq_siw_solicitacao = p_chave);
         delete pj_rubrica         where sq_siw_solicitacao = p_chave;
         delete pj_projeto_etapa   where sq_siw_solicitacao = p_chave;
         delete pj_projeto_recurso where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de projetos
         delete pj_projeto where sq_siw_solicitacao = p_chave;
            
         -- Remove o log da solicitação
         delete siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end SP_PutProjetoGeral;
/
