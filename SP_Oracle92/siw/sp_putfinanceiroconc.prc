create or replace procedure SP_PutFinanceiroConc
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_quitacao            in date,
    p_valor_real          in number,
    p_codigo_deposito     in varchar2  default null,
    p_conta               in varchar2  default null,
    p_tipo_lancamento     in number    default null,
    p_rubrica             in number    default null,
    p_observacao          in varchar2  default null,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar2  default null
   ) is
   w_chave         number(18) := null;
   w_chave_doc     number(18) := null;
   w_chave_dem     number(18) := null;
   w_chave_arq     number(18) := null;
   w_cont          number(18);
   w_cliente       number(18);
begin
   -- Recupera o cliente
   select sq_pessoa into w_cliente from siw_menu where sq_menu = p_menu;
   
   -- Recupera a chave do log
   select sq_siw_solic_log.nextval into w_chave_dem from dual;
   
   -- Insere registro na tabela de log da solicitacao
   Insert Into siw_solic_log 
      (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
       sq_siw_tramite,            data,               devolucao, 
       observacao
      )
   Values
      (w_chave_dem,               p_chave,            p_pessoa,
       p_tramite,                 sysdate,            'N',
       'Liquidação do lançamento financeiro');
       
   -- Atualiza o registro da demanda com os dados da conclusão.
   Update fn_lancamento a
      set quitacao           = p_quitacao,
          codigo_deposito    = p_codigo_deposito,
          sq_pessoa_conta    = p_conta,
          observacao         = p_observacao,
          sq_tipo_lancamento = coalesce(p_tipo_lancamento,sq_tipo_lancamento)
   Where sq_siw_solicitacao = p_chave;

   -- Atualiza a situação da solicitação
   Update siw_solicitacao set
      valor          = p_valor_real,
      conclusao      = sysdate,
      sq_siw_tramite = (select sq_siw_tramite 
                          from siw_tramite 
                         where sq_menu=p_menu 
                           and Nvl(sigla,'z')='AT'
                       )
   Where sq_siw_solicitacao = p_chave;
   
   -- Atualiza o documento vinculado à solicitação (sempre será apenas um)
   update fn_lancamento_doc set valor = p_valor_real where sq_siw_solicitacao = p_chave;

   select sq_acordo_parcela into w_chave from fn_lancamento where sq_siw_solicitacao = p_chave;
   
   -- Se o lançamento for de parcela de contrato, registra a baixa.
   If w_chave is not null Then
      Update ac_acordo_parcela 
         set quitacao = p_quitacao 
       where sq_acordo_parcela = w_chave;
   End If;
   
   -- Se foi informada rubrica, cria ou atualiza os itens de documentos
   If p_rubrica is not null Then
      -- Verifica se existe documento lançado para o fundo fixo
      select count(*) into w_cont from fn_lancamento_doc a where a.sq_siw_solicitacao = p_chave;
      If w_cont = 0 Then
         -- Recupera a chave do documento
         select sq_lancamento_doc.nextval into w_chave_doc from dual;
         
         -- Insere o documento
         insert into fn_lancamento_doc
           (sq_lancamento_doc, sq_siw_solicitacao, sq_tipo_documento,   numero, data,  valor)
         (select w_chave_doc,  p_chave,            a.sq_tipo_documento, 's/n',  c.fim, p_valor_real
            from fn_tipo_documento   a
                 inner join siw_menu b on (a.cliente = b.sq_pessoa),
                 siw_solicitacao     c
           where b.sq_menu            = p_menu
             and c.sq_siw_solicitacao = p_chave
             and a.sigla              = 'RE' -- Recibo
         );

        -- Insere o item
        insert into fn_documento_item
          (sq_documento_item,         sq_lancamento_doc, sq_projeto_rubrica, ordem, descricao,              quantidade, valor_unitario, valor_total,  valor_cotacao)
        values
          (sq_documento_item.nextval, w_chave_doc,       p_rubrica,          1,     'Suprimento de fundos', 1,          p_valor_real,   p_valor_real, 0);
      Else
         for crec in (select x.sq_lancamento_doc, x.valor from fn_lancamento_doc x where x.sq_siw_solicitacao = p_chave) loop
           select count(*) into w_cont from fn_documento_item a where a.sq_lancamento_doc = crec.sq_lancamento_doc;
           -- Se o item existe, atualiza a rubrica; caso contrário, insere o item
           If w_cont > 0 Then
              update fn_documento_item set sq_projeto_rubrica = p_rubrica where sq_lancamento_doc = crec.sq_lancamento_doc;
           Else
              insert into fn_documento_item
                (sq_documento_item,         sq_lancamento_doc,      sq_projeto_rubrica, ordem, descricao,   quantidade, valor_unitario, valor_total, valor_cotacao)
              values
                (sq_documento_item.nextval, crec.sq_lancamento_doc, p_rubrica,          1,     'Reembolso', 1,          crec.valor,     crec.valor,  0);
           End If;
         End Loop;
      End If;
   End If;
   
   -- Executa a rotina de criação e envio de protocolo do lançamento para a contabilidade
   sp_enviaProtocoloFinanceiro(w_cliente, p_chave, null);
    
   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave_arq from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, sysdate, 
              p_tamanho,   p_tipo,        p_caminho, p_nome_original
         from co_pessoa a
        where a.sq_pessoa = p_pessoa
      );
      
      -- Insere registro em SIW_SOLIC_LOG_ARQ
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
      values (w_chave_dem, w_chave_arq);
   End If;
end SP_PutFinanceiroConc;
/
