create or replace procedure SP_PutFinanceiroGeral
   (p_operacao            in varchar2,
    p_cliente             in number   default null,
    p_chave               in number   default null,
    p_menu                in number,
    p_sq_unidade          in number   default null,
    p_solicitante         in number   default null,
    p_cadastrador         in number   default null,
    p_sqcc                in number   default null,
    p_descricao           in varchar2 default null,
    p_vencimento          in date     default null,
    p_valor               in number   default null,
    p_data_hora           in varchar2 default null,
    p_aviso               in varchar2 default null,
    p_dias                in number   default null,
    p_cidade              in number   default null,
    p_projeto             in number   default null,
    p_sq_acordo_parcela   in number   default null,
    p_observacao          in varchar2 default null,
    p_sq_tipo_lancamento  in number   default null,
    p_sq_forma_pagamento  in number   default null,
    p_sq_tipo_pessoa      in number   default null,
    p_forma_atual         in number   default null,
    p_vencimento_atual    in date     default null,
    p_tipo_rubrica        in number   default null,
    p_numero_processo     in varchar2 default null,
    p_per_ini             in date     default null,
    p_per_fim             in date     default null,  
    p_chave_nova          out         number,
    p_codigo_interno      in out      varchar2
   ) is
   w_ano        number(4);
   w_sequencial number(18) := 0;
   w_existe     number(4);
   w_arq        varchar2(4000) := ', ';
   w_chave      number(18) := Nvl(p_chave,0);
   w_log_sol    number(18);
   w_log_esp    number(18);
   w_reg        fn_parametro%rowtype;
   w_inicio     date;
   w_fim        date;

   cursor c_arquivos is
      select t.sq_siw_arquivo from siw_solic_arquivo t where t.sq_siw_solicitacao = p_chave;
   
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_solicitacao.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,          sq_siw_tramite,   solicitante, 
         cadastrador,        descricao,        fim,              inclusao,
         ultima_alteracao,   valor,            data_hora,        sq_unidade,
         sq_cc,              sq_cidade_origem, sq_solic_pai)
      (select 
         w_Chave,            p_menu,           a.sq_siw_tramite, p_solicitante,
         p_cadastrador,      p_descricao,      p_vencimento,     sysdate,
         sysdate,            nvl(p_valor,0),   p_data_hora,      p_sq_unidade,
         p_sqcc,             p_cidade,         p_projeto
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Se for vinculado a parcela, recupera o período de referência
      if p_sq_acordo_parcela is not null then
         select inicio, fim into w_inicio, w_fim from ac_acordo_parcela where sq_acordo_parcela = p_sq_acordo_parcela;
      else
         w_inicio := null;
         w_fim    := null;
      end if;
      -- Insere registro em FN_LANCAMENTO
      Insert into fn_lancamento 
         ( sq_siw_solicitacao,   cliente,           sq_acordo_parcela,   sq_forma_pagamento,
           sq_tipo_lancamento,   sq_tipo_pessoa,    emissao,             vencimento,
           observacao,           aviso_prox_conc,   dias_aviso,          tipo,
           processo,             referencia_inicio, referencia_fim
         )
      values (
           w_chave,              p_cliente,         p_sq_acordo_parcela, p_sq_forma_pagamento,
           p_sq_tipo_lancamento, p_sq_tipo_pessoa,  sysdate,             p_vencimento,
           p_observacao,         p_aviso,           p_dias,              p_tipo_rubrica,
           p_numero_processo,    w_inicio,          w_fim
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
           
      -- Recupera o código interno  do acordo, gerado por trigger
      select codigo_interno into p_codigo_interno from siw_solicitacao where sq_siw_solicitacao = w_chave;

   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações

      Update siw_solicitacao set
         solicitante      = p_solicitante,
         cadastrador      = p_cadastrador,
         descricao        = trim(p_descricao), 
         fim              = p_vencimento,
         ultima_alteracao = sysdate,
         valor            = Nvl(p_valor,0),
         sq_unidade       = p_sq_unidade,
         sq_cc            = p_sqcc,
         sq_cidade_origem = p_cidade,
         sq_solic_pai     = p_projeto
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas
      Update fn_lancamento set
          sq_acordo_parcela  = p_sq_acordo_parcela,
          sq_tipo_lancamento = p_sq_tipo_lancamento,
          sq_tipo_pessoa     = p_sq_tipo_pessoa,
          vencimento         = p_vencimento,
          observacao         = trim(p_observacao),
          aviso_prox_conc    = p_aviso,
          dias_aviso         = p_dias,
          sq_forma_pagamento = p_sq_forma_pagamento,
          tipo               = p_tipo_rubrica,
          processo           = p_numero_processo,
          referencia_inicio  = p_per_ini,
          referencia_fim     = p_per_fim
      where sq_siw_solicitacao = p_chave;
      
      If Nvl(p_forma_atual, p_sq_forma_pagamento) <> p_sq_forma_pagamento Then
         update fn_lancamento 
           set sq_agencia       = null,
               operacao_conta   = null,
               numero_conta     = null,
               sq_pais_estrang  = null,
               aba_code         = null,
               swift_code       = null,
               endereco_estrang = null,
               banco_estrang    = null,
               agencia_estrang  = null,
               cidade_estrang   = null,
               informacoes      = null,
               codigo_deposito  = null
         where sq_siw_solicitacao = p_chave;
      End If;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from ac_acordo_log  where sq_siw_solicitacao = p_chave;
      
      -- Se não foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If (w_log_sol + w_log_esp) > 1 Then
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
         update siw_solicitacao set sq_siw_tramite = w_chave, conclusao = sysdate where sq_siw_solicitacao = p_chave;
      Else
         -- Monta string com a chave dos arquivos ligados à solicitação informada
         for crec in c_arquivos loop
            w_arq := w_arq || crec.sq_siw_arquivo;
         end loop;
         w_arq := substr(w_arq, 3, length(w_arq));
         
         delete siw_solic_arquivo where sq_siw_solicitacao = p_chave;
         delete siw_arquivo       where sq_siw_arquivo     in (w_arq);
         
         -- Remove os registros vinculados à demanda
         delete fn_documento_item where sq_lancamento_doc in (select sq_lancamento_doc from fn_lancamento_doc where sq_siw_solicitacao = p_chave);
         delete fn_lancamento_rubrica where sq_lancamento_doc in (select sq_lancamento_doc from fn_lancamento_doc where sq_siw_solicitacao = p_chave);
         delete fn_lancamento_log where sq_siw_solicitacao = p_chave;
         delete fn_lancamento_doc where sq_siw_solicitacao = p_chave;
         
         -- Remove o registro na tabela de demandas
         delete fn_lancamento     where sq_siw_solicitacao = p_chave;
            
         -- Remove o log da solicitação
         delete siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   -- Recupera os parâmetros do cliente informado
   select * into w_reg from fn_parametro where cliente = p_cliente;

   -- O tratamento a seguir é relativo ao código interno do lançamento.
   If p_operacao               in ('I','A')  and 
      (to_char(p_vencimento,'yyyy') <> to_char(Nvl(p_vencimento_atual, p_vencimento),'yyyy') and
       to_char(p_vencimento,'yyyy') <> w_reg.ano_corrente
      )
      Then
      
      If to_char(p_vencimento,'yyyy') <  w_reg.ano_corrente Then
    
         -- Configura o ano do acordo para o ano informado na data de início.
         w_ano := to_number(to_char(p_vencimento,'yyyy'));
         
         -- Verifica se já há algum acordo no ano informado na data de início.
         -- Se tiver, verifica o próximo sequencial. Caso contrário, usa 1.
         select count(*) into w_existe 
           from fn_lancamento a 
          where to_char(a.vencimento,'yyyy') = w_ano
            and a.sq_siw_solicitacao     <> w_chave
            and a.cliente                = p_cliente;
            
         If w_existe = 0 Then
            w_sequencial := 1;
         Else
            select Nvl(max(to_number(replace(replace(replace(b.codigo_interno,'/'||w_ano,''),Nvl(w_reg.prefixo,''),''),Nvl(w_reg.sufixo,''),''))),0)+1
              into w_sequencial
              from fn_lancamento a
                   inner join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
             where b.codigo_interno like '%/'||to_char(p_vencimento,'yyyy')
               and a.cliente                = p_cliente;
         End If;
         
         p_codigo_interno := Nvl(w_reg.prefixo,'')||w_sequencial||'/'||w_ano||Nvl(w_reg.sufixo,'');

         -- Atualiza o código interno do acordo para o sequencial encontrato
         update siw_solicitacao a set
            codigo_interno = p_codigo_interno
         where a.sq_siw_solicitacao = w_chave;
         
      Else
         
         -- Se não for ano anterior, recupera o próximo sequencial e atualiza AC_PARAMETRO
         w_sequencial := w_reg.sequencial + 1;
         update fn_parametro set sequencial = w_sequencial where cliente = p_cliente;
         
         p_codigo_interno := Nvl(w_reg.prefixo,'')||w_sequencial||'/'||w_reg.ano_corrente||Nvl(w_reg.sufixo,'');

         -- Atualiza o código interno do acordo para o sequencial encontrato
         update siw_solicitacao a set
            codigo_interno = p_codigo_interno
         where a.sq_siw_solicitacao = w_chave;
         
      End If;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
   
   If p_operacao = 'EXCLUSAO' Then
      -- Monta string com a chave dos arquivos ligados à solicitação informada
      for crec in c_arquivos loop
         w_arq := w_arq || crec.sq_siw_arquivo;
      end loop;
      w_arq := substr(w_arq, 3, length(w_arq));
   
      delete siw_solic_arquivo where sq_siw_solicitacao = p_chave;
      delete siw_arquivo       where sq_siw_arquivo     in (w_arq);
         
      -- Remove os registros vinculados ao lancamento
      delete fn_documento_item where sq_lancamento_doc in (select sq_lancamento_doc from fn_lancamento_doc where sq_siw_solicitacao = p_chave);
      delete fn_lancamento_rubrica where sq_lancamento_doc in (select sq_lancamento_doc from fn_lancamento_doc where sq_siw_solicitacao = p_chave);
      delete fn_lancamento_log where sq_siw_solicitacao = p_chave;
      delete fn_lancamento_doc where sq_siw_solicitacao = p_chave;
         
      -- Remove o registro na tabela de lncamento
      delete fn_lancamento     where sq_siw_solicitacao = p_chave;
            
      -- Remove o log da solicitação
      delete siw_solic_log where sq_siw_solicitacao = p_chave;

      -- Remove o registro na tabela de solicitações
      delete siw_solicitacao where sq_siw_solicitacao = p_chave;
   
   End If;
end SP_PutFinanceiroGeral;
/
