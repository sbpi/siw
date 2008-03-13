create or replace procedure SP_PutAcordoGeral
   (p_operacao            in  varchar2,
    p_cliente             in  number   default null,
    p_chave               in  number   default null,
    p_copia               in  number   default null,
    p_menu                in number,
    p_unid_resp           in number    default null,
    p_solicitante         in number    default null,
    p_cadastrador         in number    default null,
    p_sqcc                in number    default null,
    p_descricao           in varchar2  default null,
    p_justificativa       in varchar2  default null,
    p_inicio              in date      default null,
    p_fim                 in date      default null,
    p_valor               in number    default null,
    p_data_hora           in varchar2  default null,
    p_aviso               in varchar2  default null,
    p_dias                in number    default null,
    p_cidade              in number    default null,
    p_projeto             in number    default null,
    p_sq_tipo_acordo      in number    default null,
    p_objeto              in varchar2  default null,
    p_sq_tipo_pessoa      in number    default null,
    p_sq_forma_pagamento  in number    default null,
    p_forma_atual         in number    default null,
    p_inicio_atual        in date      default null,
    p_etapa               in number    default null,    
    p_codigo              in varchar2  default null,
    p_titulo              in varchar2  default null,
    p_numero_empenho      in varchar2  default null,
    p_numero_processo     in varchar2  default null,
    p_assinatura          in date      default null,
    p_publicacao          in date      default null,
    p_chave_nova          out number,
    p_codigo_interno      in out varchar2
   ) is
   w_ano        number(4);
   w_sequencial number(18) := 0;
   w_existe     number(4);
   w_arq        varchar2(4000) := ', ';
   w_chave      number(18) := Nvl(p_chave,0);
   w_log_sol    number(18);
   w_log_esp    number(18);
   w_cont       number(4);
   w_reg        ac_parametro%rowtype;
   
   w_meses_vigencia_renovacao  number(4);
   w_valor_original            number(18,2);
   w_qtd_parcela_original      number(4);
   w_perc_parcela_original     float;
   w_valor                     number(18,2);
   w_inicio_vigencia_original  date;
   w_meses_parcela_original    number(4);
   w_data                      date;
   w_sigla                     varchar2(20);
   w_vincula_projeto           varchar2(1) := 'N';
   w_outra_parte               number(18);

   cursor c_arquivos is
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
   
   cursor c_parcelas is
      select * from ac_acordo_parcela where sq_siw_solicitacao = p_copia order by ordem, vencimento;

   cursor c_outra_parte is
      select distinct outra_parte, tipo from ac_acordo_outra_parte where sq_siw_solicitacao = p_copia;
   
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_solicitacao.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante, 
         cadastrador,        descricao,     justificativa,       inicio,
         fim,                inclusao,      ultima_alteracao,    valor,
         data_hora,          sq_unidade,    sq_cc,               sq_cidade_origem,
         sq_solic_pai,       titulo,        codigo_interno)
      (select 
         w_Chave,            p_menu,        a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_descricao,   p_justificativa,     p_inicio,
         p_fim,              sysdate,       sysdate,             p_valor,
         p_data_hora,        p_unid_resp,   p_sqcc,              p_cidade,
         p_projeto,          p_titulo,      p_codigo
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Verifica se é convênio ou contrato de receita. Se for, o padrão é permitir a vinculação de projeto.
      select sigla into w_sigla from siw_menu where sq_menu = p_menu;
      if substr(w_sigla,1,3) = 'GCC' or substr(w_sigla,1,3) = 'GCR' then w_vincula_projeto := 'S'; end if;
      
      -- Insere registro em AC_ACORDO
      Insert into ac_acordo
         ( sq_siw_solicitacao,  cliente,           sq_tipo_acordo,       inicio,
           fim,                 valor_inicial,     objeto,               aviso_prox_conc,     
           dias_aviso,          sq_tipo_pessoa,    sq_forma_pagamento,   empenho,
           processo,            vincula_projeto
         )
      (select
           w_chave,             p_cliente,         p_sq_tipo_acordo,     p_inicio,
           p_fim,               p_valor,           p_objeto,             p_aviso,
           p_dias,              p_sq_tipo_pessoa,  p_sq_forma_pagamento, p_numero_empenho,
           p_numero_processo,   w_vincula_projeto
        from dual
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
      
      -- Se receber p_atividade, grava na tabela de atividades de projeto
      If p_etapa is not null Then
         Insert Into pj_etapa_contrato
                (sq_etapa_contrato,         sq_projeto_etapa, sq_siw_solicitacao)
         Values (sq_etapa_contrato.nextval, p_etapa,          w_chave);
      End If;
      
      If p_codigo is null Then
         -- Recupera o código interno  do acordo, gerado por trigger
         select codigo_interno into p_codigo_interno from siw_solicitacao where sq_siw_solicitacao = w_chave;
      Else
         p_codigo_interno := p_codigo;
      End If;

      -- Se a demanda foi copiada de outra, grava os dados complementares
      If p_copia is not null Then
         -- Copia o preposto e o termo de referência do acordo original
         Update ac_acordo set 
            (outra_parte, preposto, atividades, produtos, requisitos) = 
            (select outra_parte, preposto, atividades, produtos, requisitos from ac_acordo where sq_siw_solicitacao = p_copia)
         where sq_siw_solicitacao = w_chave;
         
         -- Copia os representantes do acordo original
         Insert Into ac_acordo_representante (sq_pessoa, sq_siw_solicitacao)
         (Select sq_pessoa, w_chave
           from ac_acordo_representante a
          where a.sq_siw_solicitacao = p_copia
         );
         
         for crec in c_outra_parte loop
            -- Copia as outras partes existente no contrato de origem
            select sq_acordo_outra_parte.nextval into w_outra_parte from dual;
            insert into ac_acordo_outra_parte (sq_acordo_outra_parte, sq_siw_solicitacao, outra_parte, tipo)
            values (w_outra_parte, w_chave, crec.outra_parte, crec.tipo);
            
            -- Copia os prepostos de cada outra parte
            insert into ac_acordo_preposto
              (sq_siw_solicitacao, sq_acordo_outra_parte, sq_pessoa, cargo)
            (select distinct w_chave, w_outra_parte, a.sq_pessoa, a.cargo 
               from ac_acordo_preposto               a
                    inner join ac_acordo_outra_parte b on (a.sq_acordo_outra_parte = b.sq_acordo_outra_parte and
                                                           b.outra_parte           = crec.outra_parte
                                                          )
              where a.sq_siw_solicitacao    = p_copia
            );
            
            -- Copia os representantes de cada outra parte
            insert into ac_acordo_outra_rep
              (sq_acordo_outra_parte, sq_pessoa, sq_siw_solicitacao, cargo)
            (select distinct w_outra_parte, a.sq_pessoa, w_chave, a.cargo 
               from ac_acordo_outra_rep              a
                    inner join ac_acordo_outra_parte b on (a.sq_acordo_outra_parte = b.sq_acordo_outra_parte and
                                                           b.outra_parte           = crec.outra_parte
                                                          )
               where a.sq_siw_solicitacao = p_copia);
         end loop;
         

         -- Verifica se o acordo original tem parcelas.
         -- Se tiver, copia as parcelas, ajustando vigência e valor
         select count(distinct(valor)) into w_cont from ac_acordo_parcela a where a.sq_siw_solicitacao = p_copia;
         If w_cont > 0 Then
            -- Recupera o início e o valor do acordo original e calcula os meses da vigência atual
            select a.valor,          ceil(months_between(p_fim, p_inicio))+1, a.inicio
              into w_valor_original, w_meses_vigencia_renovacao,              w_inicio_vigencia_original
              from siw_solicitacao a 
             where a.sq_siw_solicitacao = p_copia;
             
            -- Verifica a quantidade de parcelas do acordo original
            select count(*) into w_qtd_parcela_original from ac_acordo_parcela a where a.sq_siw_solicitacao = p_copia;

            -- Para cada parcela do acordo original, tenta gravar uma parcela para o novo acordo
            for crec in c_parcelas loop
               If w_valor_original = p_valor Then
                  -- Se o acordo original tem o mesmo valor do novo acordo, as parcelas têm o mesmo valor
                  w_valor := crec.valor;
               Else
                  If w_cont = 1 Then
                     -- Se o acordo original tem parcelas de mesmo valor, o novo acordo também terá
                     -- Neste caso, o valor da parcela será o valor informado dividido pela quantidade
                     -- de parcelas do acordo original
                     w_valor := p_valor / w_qtd_parcela_original;
                  Else
                     -- Se o acordo original tem parcelas de valor diferente, 
                     -- o valor das parcelas do novo acordo será proporcional
                     w_perc_parcela_original := crec.valor / w_valor_original;
                     w_valor := p_valor * w_perc_parcela_original;
                  End If;
               End If;

               -- Verifica o número de meses entre a parcela e o início do acordo original
               w_meses_parcela_original := ceil(months_between(crec.vencimento, w_inicio_vigencia_original));
               
               -- Se o número de meses for maior que o número de meses da vigência do novo acordo,
               -- despreza a parcela.
               If w_meses_parcela_original <= w_meses_vigencia_renovacao Then
                  -- Soma o número de meses da parcela original à parcela do novo acordo.
                  -- A função já trata os dias.
                  w_data := Add_Months(p_inicio, w_meses_parcela_original);
                  -- Verifica se a data da parcela está dentro da vigência do novo acordo.
                  -- Se não estiver, ajusta.
                  If w_data < p_inicio Then w_data := p_inicio; End If;
                  If w_data > p_fim    Then w_data := p_fim;    End If;
                  
                  -- Grava a parcela do novo acordo.
                  Insert Into ac_acordo_parcela 
                     ( sq_acordo_parcela,         sq_siw_solicitacao, ordem,      emissao,
                       vencimento,                observacao,         valor )
                  Values 
                     ( sq_acordo_parcela.nextval, w_chave,            crec.ordem, crec.emissao, 
                       w_data,                    crec.observacao,    w_valor
                     );
               End If;
            end loop;
         End If;
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações

      Update siw_solicitacao set
         solicitante      = p_solicitante,
         cadastrador      = p_cadastrador,
         descricao        = trim(p_descricao), 
         justificativa    = trim(p_justificativa),
         inicio           = p_inicio,
         fim              = p_fim,
         ultima_alteracao = sysdate,
         valor            = p_valor,
         sq_unidade       = p_unid_resp,
         sq_cc            = p_sqcc,
         sq_cidade_origem = p_cidade,
         titulo           = p_titulo,
         sq_solic_pai     = p_projeto
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas
      Update ac_acordo set
          sq_tipo_acordo     = p_sq_tipo_acordo,
          sq_tipo_pessoa     = p_sq_tipo_pessoa,
          inicio             = p_inicio,
          fim                = p_fim,
          valor_inicial      = p_valor,
          duracao            = null,
          objeto             = trim(p_objeto),
          aviso_prox_conc    = p_aviso,
          dias_aviso         = p_dias,
          sq_forma_pagamento = p_sq_forma_pagamento ,
          empenho            = p_numero_empenho,
          processo           = p_numero_processo
      where sq_siw_solicitacao = p_chave;
      
      If p_codigo is not null Then
         update siw_solicitacao set codigo_interno = p_codigo where sq_siw_solicitacao = p_chave;
      End If;
      
      If Nvl(p_sq_tipo_pessoa,0) = 1 Then
         update ac_acordo set preposto = null where sq_siw_solicitacao = p_chave;
         delete ac_acordo_representante where sq_siw_solicitacao = p_chave;
      End If;

      If Nvl(p_forma_atual, p_sq_forma_pagamento) <> p_sq_forma_pagamento Then
         update ac_acordo 
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
      delete pj_etapa_contrato where sq_siw_solicitacao = p_chave;

      If p_etapa is not null then
         -- Cria a vinculação com os novos dados
         Insert Into pj_etapa_contrato 
                (sq_etapa_contrato,         sq_projeto_etapa, sq_siw_solicitacao)
         Values (sq_etapa_contrato.nextval, p_etapa,      p_chave);
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
         delete pj_etapa_contrato where sq_siw_solicitacao = p_chave;
         -- Remove os registros vinculados à demanda
         delete ac_acordo_representante where sq_siw_solicitacao = p_chave;
         delete ac_acordo_log           where sq_siw_solicitacao = p_chave;
         delete ac_acordo_parcela       where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de demandas
         delete ac_acordo          where sq_siw_solicitacao = p_chave;
            
         -- Remove o log da solicitação
         delete siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   -- O tratamento a seguir é relativo ao código interno do acordo.
   If p_codigo is not null Then
      p_codigo_interno := p_codigo;
   Elsif p_operacao                in ('I','A')  and 
        (p_inicio_atual           is null       or
         to_char(p_inicio,'yyyy') <> to_char(Nvl(p_inicio_atual, p_inicio),'yyyy')
        ) Then
      
      -- Recupera os parâmetros do cliente informado
      select * into w_reg from ac_parametro where cliente = p_cliente;

      If to_char(p_inicio,'yyyy') <  w_reg.ano_corrente Then
    
         -- Configura o ano do acordo para o ano informado na data de início.
         w_ano := to_number(to_char(p_inicio,'yyyy'));
         
         -- Verifica se já há algum acordo no ano informado na data de início.
         -- Se tiver, verifica o próximo sequencial. Caso contrário, usa 1.
         select count(*) into w_existe 
           from ac_acordo a 
          where to_char(a.inicio,'yyyy') = w_ano
            and a.sq_siw_solicitacao     <> w_chave
            and a.cliente                = p_cliente;
            
         If w_existe = 0 Then
            w_sequencial := 1;
         Else
            select nvl(max(replace(translate(a.codigo_interno,'0123456789ABCDEFGHIJKLMNOPQRSTUVWXZ-:. ','0123456789'),'/'||w_ano,'')),0)+1
              into w_sequencial
              from siw_solicitacao      a
                   inner join ac_acordo b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
             where instr(a.codigo_interno,'/'||to_char(p_inicio,'yyyy')) > 0
               and b.cliente                = p_cliente;
         End If;
         
         p_codigo_interno := Nvl(w_reg.prefixo,'')||w_sequencial||'/'||w_ano||Nvl(w_reg.sufixo,'');

         -- Atualiza o código interno do acordo para o sequencial encontrato
         update siw_solicitacao a set
            codigo_interno = p_codigo_interno
         where a.sq_siw_solicitacao = w_chave;
         
      Else
         
         -- Se não for ano anterior, recupera o próximo sequencial e atualiza AC_PARAMETRO
         w_sequencial := w_reg.sequencial;
         update ac_parametro set sequencial = w_sequencial where cliente = p_cliente;
         
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
end SP_PutAcordoGeral;
/
