create or replace procedure SP_PutSolicCelular
   (p_operacao            in  varchar2,
    p_chave               in  number    default null,
    p_copia               in  number    default null,
    p_menu                in  number,
    p_unidade             in  number    default null,
    p_solicitante         in  number    default null,
    p_cadastrador         in  number    default null,
    p_descricao           in  varchar2  default null,
    p_justificativa       in  varchar2  default null,
    p_inicio              in  date      default null,
    p_fim                 in  date      default null,
    p_data_hora           in  number    default null,
    p_cidade              in  number    default null,
    p_destino             in  number    default null,    
    p_chave_nova          out number
   ) is
   w_arq     varchar2(4000) := ', ';
   w_chave   number(18);
   w_log_sol number(18);

   cursor c_arquivos is
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
begin
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
         p_cadastrador,      p_descricao,        p_justificativa,     p_inicio,
         p_fim,              sysdate,            sysdate,             p_data_hora,
         p_unidade,          p_cidade
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      -- Insere registro em sr_solicitacao_celular
      insert into sr_solicitacao_celular (sq_siw_solicitacao, sq_pais) values (w_chave, p_destino);
      
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
          inicio           = p_inicio,
          fim              = p_fim,
          ultima_alteracao = sysdate
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de sr_solicitacao_celular
      Update sr_solicitacao_celular set sq_pais = p_destino where sq_siw_solicitacao = p_chave;
      
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

         -- Remove o registro na tabela de solicitações de transporte
         delete sr_solicitacao_celular where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end SP_PutSolicCelular;
/
