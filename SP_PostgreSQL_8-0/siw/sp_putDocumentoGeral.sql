create or replace FUNCTION sp_putDocumentoGeral
   (p_operacao             varchar,
    p_chave                numeric,
    p_copia                numeric,
    p_menu                 numeric,
    p_unidade              numeric,
    p_unid_autua           numeric,
    p_solicitante          numeric,
    p_cadastrador          numeric,
    p_solic_pai            numeric,
    p_codigo              varchar,
    p_processo             varchar,
    p_circular             varchar,
    p_especie_documento    numeric,
    p_doc_original         varchar,
    p_inicio               date,
    p_volumes              numeric,
    p_dt_autuacao          date,
    p_copias               numeric,
    p_natureza_documento   numeric,
    p_fim                  date,
    p_data_recebimento     date,
    p_interno              varchar,
    p_pessoa_origem        numeric,
    p_pessoa_interes       numeric,
    p_cidade               numeric,
    p_assunto              numeric,
    p_descricao            varchar,
    p_observacao           varchar,
    p_chave_nova          numeric,
    p_codigo_interno      varchar
   ) RETURNS VOID AS $$
DECLARE
   w_sequencial numeric(18) := 0;
   w_cliente    numeric(18);
   w_arq        varchar(4000) := ', ';
   w_chave      numeric(18);
   w_log_sol    numeric(18);
   w_log_esp    numeric(18);
   w_ativ       numeric(18);
   w_cidade     numeric(18) := p_cidade;
   w_cont       numeric(18);
   w_ano        numeric(4);
   w_dv         numeric(2);
   w_reg        pa_parametro%rowtype;
   w_menu       siw_menu%rowtype;

    c_arquivos CURSOR FOR
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
BEGIN
   -- Se for origem intena recupera a unidade da cidade
   If p_interno = 'S' and (p_operacao = 'I' or p_operacao = 'A') Then 
      select sq_cidade into w_cidade
        from eo_unidade                    a 
             inner join co_pessoa_endereco b on (a.sq_pessoa_endereco = b.sq_pessoa_endereco)
       where a.sq_unidade = p_unidade;
   End If;
       
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera o código do cliente
      select sq_pessoa into w_cliente from siw_menu where sq_menu = p_menu;

      -- Recupera a próxima chave
      select nextVal('sq_siw_solicitacao') into w_Chave;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,          sq_siw_tramite,      solicitante, 
         cadastrador,        descricao,        inicio,              fim,
         inclusao,           ultima_alteracao, data_hora,           sq_unidade,
         sq_solic_pai,       sq_cidade_origem)
      (select 
         w_Chave,            p_menu,           a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_descricao,      p_inicio,            p_fim,
         now(),            now(),          1,                   p_unidade,
         p_solic_pai,        w_cidade
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Insere registro em pa_documento
      insert into pa_documento
        (sq_siw_solicitacao,   cliente,          sq_documento_pai, processo,   circular,         numero_original,    interno,       sq_especie_documento, 
         sq_natureza_documento, pessoa_origem,   copias,           volumes,    unidade_autuacao, data_recebimento,   data_autuacao, unidade_int_posse)
      values
        (w_chave,               w_cliente,       p_solic_pai,      p_processo, p_circular,       p_doc_original,     p_interno,     p_especie_documento, 
         p_natureza_documento,  p_pessoa_origem, p_copias,         p_volumes,  p_unid_autua,     p_data_recebimento, p_dt_autuacao, p_unid_autua);
      
      -- Insere o interessado da tela principal na tabela de interessados
      If p_pessoa_interes is not null Then
         insert into pa_documento_interessado (sq_siw_solicitacao, sq_pessoa, principal) values (w_chave, p_pessoa_interes, 'S');
      End If;

      -- Insere o assunto da tela principal na tabela de assuntos
      insert into pa_documento_assunto (sq_siw_solicitacao, sq_assunto, principal) values (w_chave, p_assunto, 'S');

      -- Insere log da solicitação
      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (select 
          nextVal('sq_siw_solic_log'),  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          now(),            'N',
          a.nome
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );

      -- Se o projeto foi copiado de outra, grava os dados complementares
      If p_copia is not null Then
         -- Insere registro na tabela de interessados
         insert into pa_documento_interessado (sq_siw_solicitacao, sq_pessoa, principal)
         (select w_chave, sq_pessoa, principal from pa_documento_interessado where sq_siw_solicitacao = p_copia);

         -- Insere registro na tabela de assuntos
         insert into pa_documento_assunto (sq_siw_solicitacao, sq_assunto, principal) 
         (select w_chave, sq_assunto, principal from pa_documento_assunto where sq_siw_solicitacao = p_copia and principal = 'N');
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
          solicitante      = p_solicitante,
          cadastrador      = p_cadastrador,
          sq_solic_pai     = p_solic_pai,
          descricao        = coalesce(p_descricao,descricao),
          inicio           = p_inicio,
          fim              = p_fim,
          sq_unidade       = p_unidade,
          ultima_alteracao = now(),
          sq_cidade_origem = w_cidade
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de documentos
      update pa_documento set
          sq_documento_pai      = p_solic_pai,
          processo              = p_processo,
          circular              = p_circular,
          numero_original       = p_doc_original,
          data_recebimento      = p_data_recebimento,
          interno               = p_interno,
          sq_especie_documento  = p_especie_documento,
          sq_natureza_documento = p_natureza_documento,
          pessoa_origem         = p_pessoa_origem,
          copias                = p_copias,
          volumes               = p_volumes,
          data_autuacao         = case p_processo when 'S' then data_autuacao else p_dt_autuacao end,
          unidade_autuacao      = case p_processo when 'S' then unidade_autuacao else p_unid_autua end
       where sq_siw_solicitacao = p_chave;

      If p_pessoa_interes is null Then
        -- Apaga o registro existente
        DELETE FROM pa_documento_interessado a where sq_siw_solicitacao = p_chave;
      Else
         -- Verifica se houve alteração do interessado principal
         select count(a.sq_pessoa) into w_cont from pa_documento_interessado a where sq_siw_solicitacao = p_chave and principal = 'S' and sq_pessoa = p_pessoa_interes;
      
         -- Se houve, ajusta os registros
         if w_cont = 0 then
            -- Apaga o registro existente
            DELETE FROM pa_documento_interessado a where sq_siw_solicitacao = p_chave and principal = 'S';
         
            -- Verifica se o novo interessado já está vinculado ao documento
            select count(a.sq_pessoa) into w_cont from pa_documento_interessado a where sq_siw_solicitacao = p_chave and principal = 'N' and sq_pessoa = p_pessoa_interes;

            -- Se estiver, coloca o interessado como principal, senão, insere registro com o novo interessado principal
            if w_cont > 0 then
               update pa_documento_interessado set principal = 'S' where sq_siw_solicitacao = p_chave and sq_pessoa = p_pessoa_interes;
            else 
               insert into pa_documento_interessado (sq_siw_solicitacao, sq_pessoa, principal) values (p_chave, p_pessoa_interes, 'S');
            end if;
         end if;
      End If;

      -- Verifica se houve alteração do assunto principal
      select count(a.sq_assunto) into w_cont from pa_documento_assunto a where sq_siw_solicitacao = p_chave and principal = 'S' and sq_assunto = p_assunto;
      
      -- Se houve, ajusta os registros
      if w_cont = 0 then
         -- Apaga o registro existente
         DELETE FROM pa_documento_assunto a where sq_siw_solicitacao = p_chave and principal = 'S';
         
         -- Verifica se o novo assunto já está vinculado ao documento
         select count(a.sq_assunto) into w_cont from pa_documento_assunto a where sq_siw_solicitacao = p_chave and principal = 'N' and sq_assunto = p_assunto;

         -- Se estiver, coloca o assunto como principal, senão, insere registro com o novo assunto principal
         if w_cont > 0 then
            update pa_documento_assunto set principal = 'S' where sq_siw_solicitacao = p_chave and sq_assunto = p_assunto;
         else 
            insert into pa_documento_assunto (sq_siw_solicitacao, sq_assunto, principal) values (p_chave, p_assunto, 'S');
         end if;
      end if;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Recupera os dados do menu
      select * into w_menu from siw_menu where sq_menu = p_menu;
      
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from pa_documento_log where sq_siw_solicitacao = p_chave;
      select count(*) into w_ativ    from siw_solicitacao where sq_solic_pai      = p_chave;
      
      -- Se não é referenciado por outro documento nem foi enviado para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca o documento como cancelado.
      If (w_log_sol + w_log_esp + w_ativ) > 1 or w_menu.cancela_sem_tramite = 'S' Then
         -- Insere log de cancelamento
         Insert Into siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
             sq_siw_tramite,            data,                 devolucao, 
             observacao
            )
         (select 
             nextVal('sq_siw_solic_log'),  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          now(),              'N',
             'Cancelamento'||case when p_observacao is not null then '. Observação: '||p_observacao else '' end
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
         
         -- Remove os registros vinculados ao projeto
         DELETE FROM siw_solic_arquivo where sq_siw_solicitacao = p_chave;
         DELETE FROM siw_arquivo       where sq_siw_arquivo     in (w_arq);
         
         DELETE FROM pa_documento_assunto     where sq_siw_solicitacao = p_chave;
         DELETE FROM pa_documento_interessado where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de documentos
         DELETE FROM pa_documento where sq_siw_solicitacao = p_chave;
            
         -- Remove o log da solicitação
         DELETE FROM siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         DELETE FROM siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   If p_operacao = 'I' Then
          
      -- Recupera os parâmetros do cliente informado
      select * into w_reg from pa_parametro where cliente = w_cliente;
  
      If to_char(p_data_recebimento,'yyyy') <=  2009 Then
        
         -- Configura o ano do registro para o ano informado na data de início.
         w_ano := to_number(to_char(p_data_recebimento,'yyyy'));
             
         -- Verifica se já há algum registro no ano informado na data de recebimento.
         -- Se tiver, verifica o próximo sequencial. Caso contrário, usa 1.
         select count(*) into w_cont 
           from pa_documento a 
          where to_char(a.data_recebimento,'yyyy') = w_ano
            and a.cliente                            = w_cliente;
                
         If w_cont = 0 Then
            w_sequencial := 1;
         Else
            select coalesce(max(b.numero_documento),0)+1 into w_sequencial
              from siw_solicitacao         a
                   inner join pa_documento b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
             where b.ano     = w_ano
               and b.cliente = w_cliente;
         End If;
             
         -- Recupera o código interno  do acordo, gerado por trigger
         select prefixo||'.'||substr(1000000+w_sequencial,2,6)||'/'||w_ano into p_codigo_interno 
           from pa_documento 
          where sq_siw_solicitacao = w_chave;
         
         -- Calcula o DV do protocolo
         w_dv             := validaCnpjCpf(p_codigo_interno,'gerar');
         
         -- Gera o número do protocolo para devolver à rotina de gravação
         p_codigo_interno := p_codigo_interno||'-'||w_dv;
         
         update pa_documento
            set numero_documento = w_sequencial,
                ano              = w_ano,
                digito           = w_dv
          where sq_siw_solicitacao = w_chave;
      Else
         -- Recupera o código interno  do acordo, gerado por trigger
         select prefixo||'.'||substr(1000000+numero_documento,2,6)||'/'||ano||'-'||substr(100+digito,2,2) into p_codigo_interno 
           from pa_documento 
          where sq_siw_solicitacao = w_chave;
      End If;
   Elsif p_operacao <> 'E' Then
      -- Recupera o código interno  do acordo, gerado por trigger
      select prefixo||'.'||substr(1000000+numero_documento,2,6)||'/'||ano||'-'||substr(100+digito,2,2) into p_codigo_interno 
        from pa_documento 
       where sq_siw_solicitacao = p_chave;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;