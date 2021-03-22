create or replace procedure sp_putMtBaixaBem
   (p_operacao            in     varchar2,
    p_cliente             in     number   default null,
    p_usuario             in     number   default null,
    p_chave               in     number   default null,
    p_menu                in     number   default null,
    p_unidade             in     number   default null,
    p_descricao           in     varchar2 default null,
    p_observacao          in     varchar2 default null,
    p_almoxarifado        in     number   default null,
    p_fornecedor          in     number   default null,
    p_tipo_movimentacao   in     number   default null,    
    p_chave_nova          out    number,
    p_codigo_interno      in out varchar2
   ) is
   w_arq        varchar2(4000) := ', ';
   w_chave      number(18) := Nvl(p_chave,0);
   w_cidade     co_cidade.sq_cidade%type;
   w_log_sol    number(18);
   w_log_esp    number(18);
   w_menu       siw_menu%rowtype;
   
   cursor c_arquivos is
      select t.sq_siw_arquivo from siw_solic_arquivo t where t.sq_siw_solicitacao = p_chave;

begin
   If p_menu is not null Then
      -- Recupera os dados do menu
      select * into w_menu from siw_menu where sq_menu = p_menu;
   End If;
   
   If p_operacao = 'I' or p_operacao = 'A' Then
      -- Recupera a cidade onde associada ao almoxarifado
      select sq_cidade into w_cidade
        from mt_almoxarifado                 a
             inner   join eo_localizacao     b on a.sq_localizacao     = b.sq_localizacao
               inner join co_pessoa_endereco c on b.sq_pessoa_endereco = c.sq_pessoa_endereco
       where sq_almoxarifado = p_almoxarifado;
   End If;
   
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_solicitacao.nextval into w_chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao,            sq_menu,                sq_siw_tramite,            solicitante, 
         cadastrador,                   descricao,              inclusao,                  ultima_alteracao,
         data_hora,                     sq_unidade,             sq_cidade_origem)
      (select 
         w_chave,                       p_menu,                 a.sq_siw_tramite,          p_usuario,
         p_usuario,                     trim(p_descricao),      sysdate,                   sysdate,
         w_menu.data_hora,              p_unidade,              w_cidade
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
         
      -- Insere registro em MT_SAIDA
      insert into mt_saida
        (sq_mtsaida,           sq_almoxarifado, sq_tipo_movimentacao, sq_siw_solicitacao, sq_unidade_origem, sq_unidade_destino, sq_pessoa_destino)
      values
        (sq_mtentrada.nextval, p_almoxarifado,  p_tipo_movimentacao,  w_chave,            p_unidade,         null,               p_fornecedor);

      -- Insere log da solicitação
      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (select 
          sq_siw_solic_log.nextval,  w_chave,            p_usuario,
          a.sq_siw_tramite,          sysdate,            'N',
          'Cadastramento inicial'
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      If p_codigo_interno is null and w_menu.numeracao_automatica > 0 Then
         -- Gera código a partir da configuração do menu      
         geraCodigoInterno(w_chave, to_number(to_char(sysdate,'yyyy')), p_codigo_interno);
      End If;
 
      -- Atualiza o código interno para o sequencial encontrado
      update siw_solicitacao a 
         set codigo_interno = p_codigo_interno
      where a.sq_siw_solicitacao = w_chave;
         
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações

      -- Recupera o código interno  do acordo, gerado por trigger
      select codigo_interno into p_codigo_interno from siw_solicitacao where sq_siw_solicitacao = w_chave;
      
      Update siw_solicitacao set
         solicitante           = p_usuario,
         cadastrador           = p_usuario,
         descricao             = trim(p_descricao), 
         ultima_alteracao      = sysdate,
         sq_unidade            = p_unidade,
         sq_cidade_origem      = w_cidade
      where sq_siw_solicitacao = w_chave;
      
      -- Atualiza a tabela de saídas      
      update mt_saida
         set sq_almoxarifado      = p_almoxarifado,
             sq_tipo_movimentacao = p_tipo_movimentacao,
             sq_unidade_origem    = p_unidade,
             sq_unidade_destino   = null,
             sq_pessoa_destino    = p_fornecedor
      where sq_siw_solicitacao = w_chave;
          
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log      where sq_siw_solicitacao = w_chave;
      
      -- Se não foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If (w_log_sol + w_log_esp) > 1 or w_menu.cancela_sem_tramite = 'S' Then
         -- Insere log de cancelamento
         Insert Into siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
             sq_siw_tramite,            data,                 devolucao, 
             observacao
            )
         (select 
             sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_usuario,
             a.sq_siw_tramite,          sysdate,              'N',
             nvl(p_observacao,'Cancelamento')
            from siw_solicitacao a
           where a.sq_siw_solicitacao = w_chave
         );
         
         -- Recupera a chave que indica que a solicitação está cancelada
         select a.sq_siw_tramite into w_chave from siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';
         
         -- Atualiza a situação da solicitação
         update siw_solicitacao set sq_siw_tramite = w_chave, conclusao = sysdate where sq_siw_solicitacao = w_chave;
      Else
         -- Monta string com a chave dos arquivos ligados à solicitação informada
         for crec in c_arquivos loop
            w_arq := w_arq || crec.sq_siw_arquivo;
         end loop;
         w_arq := substr(w_arq, 3, length(w_arq));
         
         delete siw_solic_arquivo where sq_siw_solicitacao = w_chave;
         delete siw_arquivo       where sq_siw_arquivo     in (w_arq);
         
         -- Remove os registros vinculados ao lançamento 
         delete mt_saida_item     where sq_mtsaida in (select sq_mtsaida from mt_saida where sq_siw_solicitacao = w_chave);
         delete mt_saida          where sq_siw_solicitacao = w_chave;
            
         -- Remove o log da solicitação
         delete siw_solic_log     where sq_siw_solicitacao = w_chave;

         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao   where sq_siw_solicitacao = w_chave;
      End If;
         
   End If;
       
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;

end sp_putMtBaixaBem;
/
