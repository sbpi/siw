create or replace procedure SP_PutSolicTransp
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_copia               in  number   default null,
    p_menu                in number,
    p_unidade             in number    default null,
    p_solicitante         in number    default null,
    p_cadastrador         in number    default null,
    p_descricao           in varchar2  default null,
    p_justificativa       in varchar2  default null,
    p_inicio              in varchar2  default null,
    p_fim                 in varchar2  default null,
    p_data_hora           in varchar2  default null,
    p_cidade              in number    default null,
    p_destino             in varchar2  default null,
    p_sq_veiculo          in number    default null,
    p_qtd_pessoas         in number    default null,
    p_carga               in varchar2  default null,    
    p_hodometro_saida     in number    default null,
    p_hodometro_chegada   in number    default null,
    p_horario_saida       in varchar2  default null,
    p_horario_chegada     in varchar2  default null,
    p_parcial             in varchar2  default null,                   
    p_chave_nova          out number
   ) is
   w_arq     varchar2(4000) := ', ';
   w_chave   number(18);
   w_log_sol number(18);
   w_inicio  date := null;
   w_fim     date := null;

   cursor c_arquivos is
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
begin
   -- Transforma a data de início do tipo varchar2 para o tipo date
   If p_inicio is not null Then
      If length(p_inicio)=10 
         Then w_inicio := to_date(p_inicio,'dd/mm/yyyy'); 
         Else w_inicio := to_date(p_inicio,'dd/mm/yyyy, hh24:mi:ss'); 
      End If;
   End If;

   -- Transforma a data de término do tipo varchar2 para o tipo date
   If p_fim is not null Then
      If length(p_fim)=10 
         Then w_fim := to_date(p_fim,'dd/mm/yyyy'); 
         Else w_fim := to_date(p_fim,'dd/mm/yyyy, hh24:mi:ss'); 
      End If;
   End If;

   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_solicitacao.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,            sq_siw_tramite,      solicitante, 
         cadastrador,        descricao,          justificativa,       inicio,
         fim,                inclusao,           ultima_alteracao,    data_hora,
         sq_unidade,         sq_cidade_origem    )
      (select 
         w_Chave,            p_menu,             a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_descricao,        p_justificativa,     w_inicio,
         w_fim,              sysdate,            sysdate,             p_data_hora,
         p_unidade,          p_cidade
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      -- Insere registro em SR_SOLICITACAO_TRANSPORTE
      insert into sr_solicitacao_transporte
        (sq_siw_solicitacao,   sq_veiculo,          qtd_pessoas,         carga,       hodometro_saida, 
        hodometro_chegada,     horario_saida,       horario_chegada,     destino,     parcial)
      values (
         w_Chave,              p_sq_veiculo,        p_qtd_pessoas,       p_carga,     p_hodometro_saida,   
         p_hodometro_chegada,  p_horario_saida,     p_horario_chegada,   p_destino,   p_parcial);          
      
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
           
      -- Se a solicitação foi copiada de outra, grava os dados complementares
      If p_copia is not null Then
         -- Bloco reservado para futuras necessidades
         null;
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          solicitante      = p_solicitante,
          descricao        = trim(p_descricao), 
          justificativa    = trim(p_justificativa),
          inicio           = w_inicio,
          fim              = w_fim,
          ultima_alteracao = sysdate
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de sr_solicitações_transporte
      Update sr_solicitacao_transporte set
          destino           = p_destino,
          sq_veiculo        = p_sq_veiculo, 
          qtd_pessoas       = p_qtd_pessoas,
          carga             = p_carga,
          hodometro_saida   = p_hodometro_saida,
          hodometro_chegada = p_hodometro_chegada,
          horario_saida     = p_horario_saida,
          horario_chegada   = p_horario_chegada,
          parcial           = p_parcial
      where sq_siw_solicitacao = p_chave;
      
   Elsif p_operacao = 'E' Then -- Exclusão
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
      Else
         -- Monta string com a chave dos arquivos ligados à solicitação informada
         for crec in c_arquivos loop
            w_arq := w_arq || crec.sq_siw_arquivo;
         end loop;
         w_arq := substr(w_arq, 3, length(w_arq));

         -- Remove os registros vinculados à demanda
         delete siw_solic_arquivo where sq_siw_solicitacao = p_chave;
         delete siw_arquivo       where sq_siw_arquivo     in (w_arq);
         
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
end SP_PutSolicTransp;
/
