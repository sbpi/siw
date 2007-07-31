create or replace procedure sp_putProgramaGeral
   (p_operacao            in varchar2,
    p_chave               in number    default null,
    p_copia               in number    default null,
    p_menu                in number,
    p_objetivo            in number    default null,
    p_codigo              in varchar2  default null,
    p_titulo              in varchar2  default null,
    p_unidade             in number    default null,
    p_solicitante         in number    default null,
    p_unid_resp           in number    default null,
    p_horizonte           in number    default null,
    p_natureza            in number    default null,
    p_inicio              in date      default null,
    p_fim                 in date      default null,
    p_parcerias           in varchar2  default null,
    p_ln_programa         in varchar2  default null,
    p_cadastrador         in number    default null,
    p_executor            in number    default null,
    p_solic_pai           in number    default null,
    p_valor               in number    default null,
    p_data_hora           in varchar2  default null,
    p_aviso               in varchar2  default null,
    p_dias                in number    default null,
    p_chave_nova          out number
   ) is
   w_arq     varchar2(4000) := ', ';
   w_chave   number(18);
   w_log_sol number(18);
   w_log_esp number(18);
   w_ativ    number(18);

   cursor c_recursos is
     select * from pj_projeto_recurso where sq_siw_solicitacao = p_copia;
     
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
         sq_siw_solicitacao, sq_menu,            sq_siw_tramite,      solicitante, 
         cadastrador,        executor,           inicio,              fim,
         inclusao,           ultima_alteracao,   data_hora,           sq_unidade,
         sq_solic_pai,       sq_cidade_origem,   palavra_chave,       sq_peobjetivo,
         valor,              titulo,             codigo_interno)
      (select 
         w_Chave,            p_menu,             a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_executor,         p_inicio,            p_fim,
         sysdate,            sysdate,            p_data_hora,         p_unidade,
         p_solic_pai,        c.sq_cidade_padrao, p_parcerias,         p_objetivo,
         p_valor,            p_titulo,           p_codigo
         from siw_tramite              a
              inner   join siw_menu    b on (a.sq_menu   = b.sq_menu)
                inner join siw_cliente c on (b.sq_pessoa = c.sq_pessoa)
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Insere registro em pj_projeto
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
          sq_siw_solic_log.nextval,  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          sysdate,            'N',
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
             sq_solicitacao_interessado.nextval, w_chave,            a.sq_pessoa, a.sq_tipo_interessado
            from siw_solicitacao_interessado a
           where a.sq_siw_solicitacao = p_copia
         );
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          sq_solic_pai     = p_solic_pai,
          sq_peobjetivo    = p_objetivo,
          solicitante      = p_solicitante,
          inicio           = p_inicio,
          fim              = p_fim,
          valor            = p_valor,
          ultima_alteracao = sysdate,
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
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from pe_programa_log where sq_siw_solicitacao = p_chave;
      select count(*) into w_ativ    from siw_solicitacao where sq_solic_pai      = p_chave;
      
      -- Se não tem projetos vinculados nem foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
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
                 sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
                 a.sq_siw_tramite,          sysdate,              'N',
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
         delete siw_solic_arquivo where sq_siw_solicitacao = p_chave;
         delete siw_arquivo       where sq_siw_arquivo     in (w_arq);
         
         delete siw_solicitacao_interessado where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de programas
         delete pe_programa where sq_siw_solicitacao = p_chave;
            
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
end sp_putProgramaGeral;
/
