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
    p_processo            in  varchar2 default null,
    p_circular            in  varchar2 default null,
    p_especie_documento   in  number   default null,
    p_doc_original        in  varchar2 default null,
    p_inicio              in  date     default null,
    p_volumes             in  number   default null,
    p_copias              in  number   default null,
    p_natureza_documento  in  number   default null,
    p_fim                 in  date     default null,
    p_data_recebimento    in  date     default null,
    p_interno             in  varchar2 default null,
    p_tipo_pessoa         in  varchar2 default null,
    p_pessoa_origem       in  number   default null,
    p_cidade              in  number   default null,
    p_assunto             in  number   default null,
    p_descricao           in  varchar2 default null,
    p_chave_nova          out number
   ) is
   w_cliente number(18);
   w_arq     varchar2(4000) := ', ';
   w_chave   number(18);
   w_chave1  number(18);
   w_log_sol number(18);
   w_log_esp number(18);
   w_ativ    number(18);
   i         number(10) := 0;
   w_cidade  number(18) := p_cidade;
   w_cont    number(18);

   type tb_recurso_pai is table of number(10) index by binary_integer;
   w_recurso_pai tb_recurso_pai;
    
   type rec_etapa is record (
       sq_chave_destino       number(10) := null,
       sq_chave_origem        number(10) := null,
       sq_chave_pai_origem    number(10) := null
      );
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
       
   If p_operacao = 'I' Then -- Inclus�o
      -- Recupera o c�digo do cliente
      select sq_pessoa into w_cliente from siw_menu where sq_menu = p_menu;

      -- Recupera a pr�xima chave
      select sq_siw_solicitacao.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
         sq_siw_solicitacao, sq_menu,          sq_siw_tramite,      solicitante, 
         cadastrador,        descricao,        inicio,              fim,
         inclusao,           ultima_alteracao, data_hora,           sq_unidade,
         sq_solic_pai,       sq_cidade_origem)
      (select 
         w_Chave,            p_menu,           a.sq_siw_tramite,    p_solicitante,
         p_cadastrador,      p_descricao,      p_inicio,            p_fim,
         sysdate,            sysdate,          1,                   p_unidade,
         p_solic_pai,        w_cidade
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
      
      -- Insere registro em pa_documento
      insert into pa_documento
        (sq_siw_solicitacao,   cliente,          sq_documento_pai, processo,   circular,         numero_original,   interno,   sq_especie_documento, 
         sq_natureza_documento, pessoa_origem,   copias,           volumes,    unidade_autuacao, data_recebimento)
      values
        (w_chave,               w_cliente,       p_solic_pai,      p_processo, p_circular,       p_doc_original,    p_interno, p_especie_documento, 
         p_natureza_documento,  p_pessoa_origem, p_copias,         p_volumes,  p_unid_autua,     p_data_recebimento);
      
      -- Insere o assunto da tela principal na tabela de assuntos
      insert into pa_documento_assunto (sq_siw_solicitacao, sq_assunto, principal) values (w_chave, p_assunto, 'S');

      -- Insere log da solicita��o
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
   Elsif p_operacao = 'A' Then -- Altera��o
      -- Atualiza a tabela de solicita��es
      Update siw_solicitacao set
          solicitante      = p_solicitante,
          cadastrador      = p_cadastrador,
          sq_solic_pai     = p_solic_pai,
          descricao        = coalesce(p_descricao,descricao),
          inicio           = p_inicio,
          fim              = p_fim,
          sq_unidade       = p_unidade,
          ultima_alteracao = sysdate,
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
          unidade_autuacao      = p_unid_autua
       where sq_siw_solicitacao = p_chave;

      -- Verifica se houve altera��o do assunto principal
      select count(a.sq_assunto) into w_cont from pa_documento_assunto a where sq_siw_solicitacao = p_chave and principal = 'S' and sq_assunto = p_assunto;
      
      -- Se houve, ajusta os registros
      if w_cont = 0 then
         -- Apaga o registro existente
         delete pa_documento_assunto a where sq_siw_solicitacao = p_chave and principal = 'S';
         
         -- Verifica se o novo assunto j� est� vinculado ao documento
         select count(a.sq_assunto) into w_cont from pa_documento_assunto a where sq_siw_solicitacao = p_chave and principal = 'N' and sq_assunto = p_assunto;

         -- Se estiver, coloca o assunto como principal, sen�o, insere registro com o novo assunto principal
         if w_cont > 0 then
            update pa_documento_assunto set principal = 'S' where sq_siw_solicitacao = p_chave and sq_assunto = p_assunto;
         else 
            insert into pa_documento_assunto (sq_siw_solicitacao, sq_assunto, principal) values (p_chave, p_assunto, 'S');
         end if;
      end if;

   Elsif p_operacao = 'E' Then -- Exclus�o
      -- Verifica a quantidade de logs da solicita��o
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from pa_documento_log where sq_siw_solicitacao = p_chave;
      select count(*) into w_ativ    from siw_solicitacao where sq_solic_pai      = p_chave;
      
      -- Se n�o � referenciado por outro documento nem foi enviado para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contr�rio, coloca o documento como cancelado.
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
         
         -- Recupera a chave que indica que a solicita��o est� cancelada
         select a.sq_siw_tramite into w_chave from siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';
         
         -- Atualiza a situa��o da solicita��o
         update siw_solicitacao set sq_siw_tramite = w_chave where sq_siw_solicitacao = p_chave;
      Else
         -- Monta string com a chave dos arquivos ligados � solicita��o informada
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
            
         -- Remove o log da solicita��o
         delete siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicita��es
         delete siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end sp_putDocumentoGeral;
/
