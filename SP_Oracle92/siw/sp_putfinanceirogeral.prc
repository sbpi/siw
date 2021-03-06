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
    p_condicao            in varchar2 default null,  
    p_vinculo             in number   default null,
    p_rubrica             in number   default null,
    p_solic_apoio         in number   default null,
    p_data_autorizacao    in date     default null,
    p_texto_autorizacao   in varchar2 default null,
    p_moeda               in number   default null,
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
   w_menu       siw_menu%rowtype;
   w_inicio     date;
   w_fim        date;
   w_parcela    ac_acordo_parcela.sq_acordo_parcela%type;

   w_protocolo_siw             number(18);

   cursor c_arquivos is
      select t.sq_siw_arquivo from siw_solic_arquivo t where t.sq_siw_solicitacao = p_chave;
   
begin
   If p_menu is not null Then
      -- Recupera os dados do menu
      select * into w_menu from siw_menu where sq_menu = p_menu;
   End If;
   
   If p_operacao = 'I' Then -- Inclus�o
      -- Recupera a pr�xima chave
      select sq_siw_solicitacao.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao,            sq_menu,                sq_siw_tramite,            solicitante, 
         cadastrador,                   descricao,              fim,                       inclusao,
         ultima_alteracao,              valor,                  data_hora,                 sq_unidade,
         sq_cc,                         sq_cidade_origem,       sq_solic_pai,              sq_moeda,
         sq_solic_apoio,                data_autorizacao,       texto_autorizacao)
      (select 
         w_Chave,                       p_menu,                 a.sq_siw_tramite,          p_solicitante,
         p_cadastrador,                 p_descricao,            p_vencimento,              sysdate,
         sysdate,                       coalesce(p_valor,0),    p_data_hora,               p_sq_unidade,
         p_sqcc,                        p_cidade,               p_projeto,                 p_moeda,
         p_solic_apoio,                 p_data_autorizacao,     p_texto_autorizacao
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Se for vinculado a parcela, recupera o per�odo de refer�ncia
      if p_sq_acordo_parcela is not null then
         select inicio, fim into w_inicio, w_fim from ac_acordo_parcela where sq_acordo_parcela = p_sq_acordo_parcela;
      else
         w_inicio := p_per_ini;
         w_fim    := p_per_fim;
      end if;
      -- Insere registro em FN_LANCAMENTO
      Insert into fn_lancamento 
         ( sq_siw_solicitacao,   cliente,            sq_acordo_parcela,   sq_forma_pagamento,
           sq_tipo_lancamento,   sq_tipo_pessoa,     emissao,             vencimento,
           observacao,           aviso_prox_conc,    dias_aviso,          tipo,
           processo,             referencia_inicio,  referencia_fim,      condicoes_pagamento,
           sq_solic_vinculo,     sq_projeto_rubrica  --, sq_solic_apoio
         )
      values (
           w_chave,              p_cliente,         p_sq_acordo_parcela, p_sq_forma_pagamento,
           p_sq_tipo_lancamento, p_sq_tipo_pessoa,  sysdate,             p_vencimento,
           p_observacao,         p_aviso,           p_dias,              p_tipo_rubrica,
           p_numero_processo,    w_inicio,          w_fim,               p_condicao,
           p_vinculo,            p_rubrica          --,         p_solic_apoio
      );

      -- Insere log da solicita��o
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

      -- Recupera o c�digo interno  do acordo, gerado por trigger
      select codigo_interno into p_codigo_interno from siw_solicitacao where sq_siw_solicitacao = w_chave;
      
   Elsif p_operacao = 'A' Then -- Altera��o
      -- Atualiza a tabela de solicita��es

      -- Recupera o c�digo interno  do acordo, gerado por trigger
      select codigo_interno into p_codigo_interno from siw_solicitacao where sq_siw_solicitacao = w_chave;
      
      Update siw_solicitacao set
         solicitante           = p_solicitante,
         cadastrador           = p_cadastrador,
         descricao             = trim(p_descricao), 
         fim                   = p_vencimento,
         ultima_alteracao      = sysdate,
         valor                 = Nvl(p_valor,0),
         sq_unidade            = p_sq_unidade,
         sq_cc                 = p_sqcc,
         sq_cidade_origem      = p_cidade,
         sq_solic_pai          = p_projeto,
         sq_moeda              = p_moeda,
         sq_solic_apoio        = p_solic_apoio,
         data_autorizacao      = p_data_autorizacao,
         texto_autorizacao     = p_texto_autorizacao
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas
      Update fn_lancamento set
          sq_acordo_parcela    = p_sq_acordo_parcela,
          sq_tipo_lancamento   = p_sq_tipo_lancamento,
          sq_tipo_pessoa       = p_sq_tipo_pessoa,
          vencimento           = p_vencimento,
          observacao           = trim(p_observacao),
          aviso_prox_conc      = p_aviso,
          dias_aviso           = p_dias,
          sq_forma_pagamento   = p_sq_forma_pagamento,
          tipo                 = p_tipo_rubrica,
          processo             = p_numero_processo,
          referencia_inicio    = p_per_ini,
          referencia_fim       = p_per_fim,
          condicoes_pagamento  = p_condicao,
          sq_solic_vinculo     = p_vinculo,
          sq_projeto_rubrica   = p_rubrica --,
          --sq_solic_apoio       = p_solic_apoio
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
      
      -- Se recebeu rubrica, atualiza os itens do lan�amento que est�o com esse campo nulo
      If p_rubrica is not null Then
         update fn_documento_item
            set sq_projeto_rubrica = p_rubrica
         where sq_lancamento_doc in (select sq_lancamento_doc from fn_lancamento_doc where sq_siw_solicitacao = p_chave)
           and sq_projeto_rubrica is null;
      End If;

      /*
      -- Se recebeu fonte de financiamento, atualiza os itens do lan�amento que est�o com esse campo nulo
      If p_solic_apoio is not null Then
         update fn_documento_item
            set sq_solic_apoio = p_solic_apoio
         where sq_lancamento_doc in (select sq_lancamento_doc from fn_lancamento_doc where sq_siw_solicitacao = p_chave)
           and p_solic_apoio is null;
      End If;
      */
   Elsif p_operacao = 'E' Then -- Exclus�o
      -- Verifica a quantidade de logs da solicita��o
      select count(*) into w_log_sol from siw_solic_log      where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from fn_lancamento_log  where sq_siw_solicitacao = p_chave;
      
      -- Se n�o foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contr�rio, coloca a solicita��o como cancelada.
      If (w_log_sol + w_log_esp) > 1 or w_menu.cancela_sem_tramite = 'S' Then
         -- Insere log de cancelamento
         Insert Into siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
             sq_siw_tramite,            data,                 devolucao, 
             observacao
            )
         (select 
             sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          sysdate,              'N',
             nvl(p_observacao,'Cancelamento')
            from siw_solicitacao a
           where a.sq_siw_solicitacao = p_chave
         );
         
         -- Recupera a chave que indica que a solicita��o est� cancelada
         select a.sq_siw_tramite into w_chave from siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';
         
         -- Atualiza a situa��o da solicita��o
         update siw_solicitacao set sq_siw_tramite = w_chave, conclusao = sysdate where sq_siw_solicitacao = p_chave;
         
         -- Remove v�nculo quando o lan�amento � de imposto
         delete fn_imposto_doc where solic_imposto = p_chave;

         -- Remove data da quita��o da parcela se o lan�amento financeiro for ligado a uma parcela de contrato
         select count(*) into w_existe
           from fn_lancamento
          where sq_siw_solicitacao = p_chave
            and sq_acordo_parcela  is not null;
         
         If w_existe > 0 Then
            select sq_acordo_parcela into w_parcela
              from fn_lancamento
             where sq_siw_solicitacao = p_chave;
           
            update ac_acordo_parcela 
               set quitacao = null 
             where sq_acordo_parcela = w_parcela;
         End If;
      Else
         -- Monta string com a chave dos arquivos ligados � solicita��o informada
         for crec in c_arquivos loop
            w_arq := w_arq || crec.sq_siw_arquivo;
         end loop;
         w_arq := substr(w_arq, 3, length(w_arq));
         
         delete siw_solic_arquivo where sq_siw_solicitacao = p_chave;
         delete siw_arquivo       where sq_siw_arquivo     in (w_arq);
         
         -- Remove os registros vinculados ao lan�amento
         delete fn_documento_valores  where sq_lancamento_doc in (select sq_lancamento_doc from fn_lancamento_doc where sq_siw_solicitacao = p_chave);
         delete fn_imposto_doc        where solic_imposto  = p_chave;
         delete fn_imposto_doc        where solic_retencao = p_chave;
         delete fn_documento_item     where sq_lancamento_doc in (select sq_lancamento_doc from fn_lancamento_doc where sq_siw_solicitacao = p_chave);
         delete fn_lancamento_rubrica where sq_lancamento_doc in (select sq_lancamento_doc from fn_lancamento_doc where sq_siw_solicitacao = p_chave);
         delete fn_lancamento_log     where sq_siw_solicitacao = p_chave;
         delete fn_lancamento_doc     where sq_siw_solicitacao = p_chave;
         
         -- Remove o registro na tabela de demandas
         delete fn_lancamento         where sq_siw_solicitacao = p_chave;
            
         -- Remove o log da solicita��o
         delete siw_solic_log         where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicita��es
         delete siw_solicitacao       where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   If p_operacao in ('I','A') Then      
   
      If p_numero_processo is not null Then
         -- Recupera a chave do protocolo
         select sq_siw_solicitacao into w_protocolo_siw 
           from pa_documento 
          where p_numero_processo = prefixo||'.'||substr(1000000+numero_documento,2,6)||'/'||ano||'-'||substr(100+digito,2,2);
       
         -- Grava a chave do protocolo na solicita��o
         update siw_solicitacao a set a.protocolo_siw = w_protocolo_siw where sq_siw_solicitacao = w_chave;
      End If;

      -- Coloca o projeto ao qual o lan�amento est� vinculado.
      for crec in (select case when b2.sigla = 'PR' then b.sq_siw_solicitacao
                               when c2.sigla = 'PR' then c.sq_siw_solicitacao
                               when d2.sigla = 'PR' then d.sq_siw_solicitacao
                          end vinculo
                     from fn_lancamento                      a 
                          inner         join siw_solicitacao a1 on (a.sq_siw_solicitacao = a1.sq_siw_solicitacao)
                            inner       join siw_solicitacao b  on (a1.sq_solic_pai      = b.sq_siw_solicitacao)
                              inner     join siw_menu        b1 on (b.sq_menu            = b1.sq_menu)
                                inner   join siw_modulo      b2 on (b1.sq_modulo         = b2.sq_modulo)
                              left      join siw_solicitacao c  on (b.sq_solic_pai       = c.sq_siw_solicitacao)
                                left    join siw_menu        c1 on (c.sq_menu            = c1.sq_menu)
                                  left  join siw_modulo      c2 on (c1.sq_modulo         = c2.sq_modulo)
                              left      join siw_solicitacao d  on (c.sq_solic_pai       = d.sq_siw_solicitacao)
                                left    join siw_menu        d1 on (d.sq_menu            = d1.sq_menu)
                                  left  join siw_modulo      d2 on (d1.sq_modulo         = d2.sq_modulo)
                    where a.sq_solic_vinculo   is null
                      and a.sq_siw_solicitacao = w_chave
                  )
      loop
         update fn_lancamento set sq_solic_vinculo = crec.vinculo where sq_siw_solicitacao = w_chave;
      end loop;

      -- O tratamento a seguir � relativo ao c�digo interno do lan�amento.
      If (p_vencimento_atual is null or
          to_char(p_vencimento,'yyyy') <> to_char(Nvl(p_vencimento_atual, p_vencimento),'yyyy') or
          (to_char(p_vencimento,'yyyy') < to_char(sysdate,'yyyy') and p_codigo_interno like 'FN-0/%')
         )
      Then
        
         If w_menu.numeracao_automatica = 0 Then
            -- Recupera os par�metros do cliente informado
            select * into w_reg from fn_parametro where cliente = p_cliente;
            
            If to_char(p_vencimento,'yyyy') <  w_reg.ano_corrente Then
         
               -- Configura o ano do acordo para o ano informado na data de in�cio.
               w_ano := to_number(to_char(p_vencimento,'yyyy'));
              
               -- Verifica se j� h� algum lan�amento no ano informado na data de in�cio.
               -- Se tiver, verifica o pr�ximo sequencial. Caso contr�rio, usa 1.
               select count(*) into w_existe 
                 from fn_lancamento a 
                 where to_char(a.vencimento,'yyyy') =  w_ano
                  and a.sq_siw_solicitacao         <> w_chave
                  and a.cliente                    =  p_cliente;
                 
               If w_existe = 0 Then
                  w_sequencial := 1;
               Else
                  select Nvl(max(to_number(translate(replace(replace(replace(upper(b.codigo_interno),'/'||w_ano,''),Nvl(w_reg.prefixo,''),''),Nvl(null,''),''),'0123456789ABCDEFGHIJKLMNOPQRSTUVWXZ-:. ','0123456789'))),0)+1
                    into w_sequencial
                    from fn_lancamento              a
                         inner join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                   where b.codigo_interno like '%/'||to_char(p_vencimento,'yyyy')
                     and a.cliente        = p_cliente;
               End If;
              
               p_codigo_interno := Nvl(w_reg.prefixo,'')||w_sequencial||'/'||w_ano||Nvl(w_reg.sufixo,'');
            End If;
         Else
            -- Gera c�digo a partir da configura��o do menu      
            geraCodigoInterno(w_chave, to_number(to_char(p_vencimento,'yyyy')), p_codigo_interno);
         End If;
 
         -- Atualiza o c�digo interno para o sequencial encontrado
         update siw_solicitacao a 
            set codigo_interno = p_codigo_interno
         where a.sq_siw_solicitacao = w_chave;
      End If;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
   
   If p_operacao = 'EXCLUSAO' Then
      -- Monta string com a chave dos arquivos ligados � solicita��o informada
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
            
      -- Remove o log da solicita��o
      delete siw_solic_log where sq_siw_solicitacao = p_chave;

      -- Remove o registro na tabela de solicita��es
      delete siw_solicitacao where sq_siw_solicitacao = p_chave;
   
   End If;
end SP_PutFinanceiroGeral;
/
