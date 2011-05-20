create or replace procedure sp_putDocumentoGeral
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_copia               in  number   default null,
    p_menu                in  number,
    p_unidade             in  number   default null,
    p_unid_autua          in  number   default null,
    p_solicitante         in  number   default null,
    p_cadastrador         in  number   default null,
    p_solic_pai           in  number   default null,
    p_vinculo             in  number   default null,
    p_processo            in  varchar2 default null,
    p_circular            in  varchar2 default null,
    p_especie_documento   in  number   default null,
    p_doc_original        in  varchar2 default null,
    p_inicio              in  date     default null,
    p_volumes             in  number   default null,
    p_dt_autuacao         in  date     default null,
    p_copias              in  number   default null,
    p_natureza_documento  in  number   default null,
    p_fim                 in  date     default null,
    p_data_recebimento    in  date     default null,
    p_interno             in  varchar2 default null,
    p_pessoa_origem       in  number   default null,
    p_pessoa_interes      in  number   default null,
    p_cidade              in  number   default null,
    p_assunto             in  number   default null,
    p_descricao           in  varchar2 default null,
    p_observacao          in  varchar2 default null,
    p_chave_nova          out number,
    p_codigo_interno      in out varchar2
   ) is
   w_sequencial    number(18) := 0;
   w_cliente       number(18);
   w_arq           varchar2(4000) := ', ';
   w_chave         number(18);
   w_log_sol       number(18);
   w_log_esp       number(18);
   w_ativ          number(18);
   w_cidade        number(18) := p_cidade;
   w_cont          number(18);
   w_ano           number(4);
   w_dv            number(2);
   w_reg           pa_parametro%rowtype;
   w_menu          siw_menu%rowtype;
   w_sq_caixa      pa_caixa.sq_caixa%type;
   w_dados_caixa   varchar2(4000);
   w_limite        varchar2(255);
   w_intermediario varchar2(255);
   w_final         varchar2(255);
   w_assunto       varchar2(1000);
   w_descricao     varchar2(1000);
   w_texto         varchar2(1000);

   cursor c_arquivos is
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
begin
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
      select sq_siw_solicitacao.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,          sq_siw_tramite,      solicitante, 
         cadastrador,        descricao,        inicio,              fim,
         inclusao,           ultima_alteracao, data_hora,           sq_unidade,
         sq_cidade_origem,   protocolo_siw)
      (select 
         w_Chave,            p_menu,           a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_descricao,      p_inicio,            p_fim,
         sysdate,            sysdate,          1,                   p_unidade,
         w_cidade,           p_vinculo
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Insere registro em pa_documento
      insert into pa_documento
        (sq_siw_solicitacao,   cliente,          processo,   circular,   numero_original,  interno,            sq_especie_documento, 
         sq_natureza_documento, pessoa_origem,   copias,     volumes,    unidade_autuacao, data_recebimento,   data_autuacao, unidade_int_posse)
      values
        (w_chave,               w_cliente,       p_processo, p_circular, p_doc_original,   p_interno,          p_especie_documento, 
         p_natureza_documento,  p_pessoa_origem, p_copias,   p_volumes,  p_unid_autua,     p_data_recebimento, p_dt_autuacao, p_unid_autua);
      
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
          sq_siw_solic_log.nextval,  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          sysdate,            'N',
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
      -- Atualiza o vínculo somente no protocolo indicado
      Update siw_solicitacao set protocolo_siw = p_vinculo where sq_siw_solicitacao = p_chave;

      -- Atualiza os demais dados da tabela no protocolo indicado e nos vinculados a ele
      Update siw_solicitacao set
          solicitante      = p_solicitante,
          cadastrador      = p_cadastrador,
          descricao        = coalesce(p_descricao,descricao),
          inicio           = p_inicio,
          fim              = p_fim,
          sq_unidade       = p_unidade,
          ultima_alteracao = sysdate,
          sq_cidade_origem = w_cidade
      where sq_siw_solicitacao = p_chave
         or sq_siw_solicitacao in (select x.sq_siw_solicitacao from siw_solicitacao x inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.copias is not null) where protocolo_siw = p_chave);
      
      -- Atualiza o número da cópia somente no protocolo indicado
      update pa_documento set copias = p_copias where sq_siw_solicitacao = p_chave;

      -- Atualiza a tabela de documentos
      update pa_documento set
          processo              = p_processo,
          circular              = p_circular,
          numero_original       = p_doc_original,
          data_recebimento      = p_data_recebimento,
          interno               = p_interno,
          sq_especie_documento  = p_especie_documento,
          sq_natureza_documento = p_natureza_documento,
          pessoa_origem         = p_pessoa_origem,
          volumes               = p_volumes,
          data_autuacao         = case p_processo when 'S' then data_autuacao else p_dt_autuacao end,
          unidade_autuacao      = case p_processo when 'S' then unidade_autuacao else p_unid_autua end
       where sq_siw_solicitacao = p_chave
          or sq_siw_solicitacao in (select x.sq_siw_solicitacao from siw_solicitacao x inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.copias is not null) where protocolo_siw = p_chave);

      If p_pessoa_interes is null Then
        -- Apaga o registro existente
        delete pa_documento_interessado
         where (sq_siw_solicitacao = p_chave or 
                sq_siw_solicitacao in (select x.sq_siw_solicitacao from siw_solicitacao x inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.copias is not null) where protocolo_siw = p_chave)
               );
      Else
         -- Verifica se houve alteração do interessado principal
         select count(a.sq_pessoa) into w_cont from pa_documento_interessado a where sq_siw_solicitacao = p_chave and principal = 'S' and sq_pessoa = p_pessoa_interes;
      
         -- Se houve, ajusta os registros
         if w_cont = 0 then
            -- Apaga o registro existente
            delete pa_documento_interessado a
             where (sq_siw_solicitacao = p_chave or 
                    sq_siw_solicitacao in (select x.sq_siw_solicitacao from siw_solicitacao x inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.copias is not null) where protocolo_siw = p_chave)
                   )
               and principal = 'S';
         
            -- Verifica se o novo interessado já está vinculado ao documento
            select count(a.sq_pessoa) into w_cont from pa_documento_interessado a where sq_siw_solicitacao = p_chave and principal = 'N' and sq_pessoa = p_pessoa_interes;

            -- Se estiver, coloca o interessado como principal, senão, insere registro com o novo interessado principal
            if w_cont > 0 then
               update pa_documento_interessado
                   set principal = 'S' 
                where (sq_siw_solicitacao = p_chave or 
                       sq_siw_solicitacao in (select x.sq_siw_solicitacao from siw_solicitacao x inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.copias is not null) where protocolo_siw = p_chave)
                      )
                and sq_pessoa = p_pessoa_interes;
            else 
               insert into pa_documento_interessado (sq_siw_solicitacao, sq_pessoa, principal) 
               (select sq_siw_solicitacao, p_pessoa_interes, 'S'
                  from siw_solicitacao 
                 where sq_siw_solicitacao = p_chave
                    or sq_siw_solicitacao in (select x.sq_siw_solicitacao from siw_solicitacao x inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.copias is not null) where protocolo_siw = p_chave)
               );
            end if;
         end if;
      End If;

      -- Verifica se houve alteração do assunto principal
      select count(a.sq_assunto) into w_cont from pa_documento_assunto a where sq_siw_solicitacao = p_chave and principal = 'S' and sq_assunto = p_assunto;
      
      -- Se houve, ajusta os registros
      if w_cont = 0 then
         -- Apaga o registro existente
         delete pa_documento_assunto a 
         where (sq_siw_solicitacao = p_chave or 
                sq_siw_solicitacao in (select x.sq_siw_solicitacao from siw_solicitacao x inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.copias is not null) where protocolo_siw = p_chave)
               )
           and principal = 'S';
         
         -- Verifica se o novo assunto já está vinculado ao documento
         select count(a.sq_assunto) into w_cont from pa_documento_assunto a where sq_siw_solicitacao = p_chave and principal = 'N' and sq_assunto = p_assunto;

         -- Se estiver, coloca o assunto como principal, senão, insere registro com o novo assunto principal
         if w_cont > 0 then
            update pa_documento_assunto 
               set principal = 'S' 
            where (sq_siw_solicitacao = p_chave or 
                   sq_siw_solicitacao in (select x.sq_siw_solicitacao from siw_solicitacao x inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.copias is not null) where protocolo_siw = p_chave)
                  )
              and sq_assunto = p_assunto;
         else 
            insert into pa_documento_assunto (sq_siw_solicitacao, sq_assunto, principal) 
            (select sq_siw_solicitacao, p_assunto, 'S'
               from siw_solicitacao
              where sq_siw_solicitacao = p_chave
                 or sq_siw_solicitacao in (select x.sq_siw_solicitacao from siw_solicitacao x inner join pa_documento y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and y.copias is not null) where protocolo_siw = p_chave)
            );
         end if;
      end if;

      -- Verifica se o protocolo está em uma caixa
      select sq_caixa into w_sq_caixa from pa_documento where sq_siw_solicitacao = p_chave;
      
      If w_sq_caixa is not null Then
         w_cont := 0;
         -- Se estiver em uma caixa, atualiza os dados dela
         select retornaLimiteCaixa(w_sq_caixa)||'|@|' into w_dados_caixa from dual;
         Loop
            w_cont := w_cont + 1;
            w_texto := substr(w_dados_caixa,1,instr(w_dados_caixa,'|@|')-1);
            If    w_cont = 1 Then w_limite        := w_texto;
            Elsif w_cont = 2 then w_intermediario := w_texto;
            Elsif w_cont = 3 then w_final         := w_texto;
            Elsif w_cont = 4 then w_assunto       := w_texto;
            Else                  w_descricao     := w_texto;
            End If;
            If w_cont > 4 Then Exit; End If;
            w_dados_caixa := substr(w_dados_caixa,instr(w_dados_caixa,'|@|')+3);
         End Loop;
         update pa_caixa
            set assunto             = substr(w_assunto,1,800),
                descricao           = substr(w_descricao,1,2000),
                data_limite         = w_limite,
                intermediario       = substr(w_intermediario,1,400),
                destinacao_final    = substr(w_final,1,40)
         where sq_caixa = w_sq_caixa;
      End If;
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
             sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          sysdate,              'N',
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
         delete siw_solic_arquivo where sq_siw_solicitacao = p_chave;
         delete siw_arquivo       where sq_siw_arquivo     in (w_arq);
         
         delete pa_documento_assunto     where sq_siw_solicitacao = p_chave;
         delete pa_documento_interessado where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de documentos
         delete pa_documento where sq_siw_solicitacao = p_chave;
            
         -- Remove o log da solicitação
         delete siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao where sq_siw_solicitacao = p_chave;
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
   End If;
end sp_putDocumentoGeral;
/
