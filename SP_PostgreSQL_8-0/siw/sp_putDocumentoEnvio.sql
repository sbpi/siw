create or replace FUNCTION sp_putDocumentoEnvio
   (p_menu                 numeric,
    p_chave                numeric,
    p_pessoa               numeric,
    p_tramite              numeric,
    p_interno              varchar,
    p_unidade_origem       numeric,
    p_unidade_destino      numeric,
    p_pessoa_destino       numeric,
    p_tipo_despacho        numeric,
    p_prefixo             numeric,
    p_numero              numeric,
    p_ano                 numeric,
    p_despacho             varchar,
    p_emite_aviso          varchar,
    p_dias_aviso           numeric,
    p_retorno_limite       date,
    p_pessoa_externa       varchar,
    p_unidade_externa      varchar,
    p_nu_guia             numeric,
    p_ano_guia            numeric,
    p_unidade_autuacao    numeric
   ) RETURNS VOID AS $$
DECLARE
   
   w_chave         numeric(18) := null;
   w_chave_dem     numeric(18) := null;
   w_tramite       numeric(18);
   w_sg_tramite    varchar(2);
   w_retorno_unid  numeric(18) := null;
   w_pai           numeric(18) := null;
   w_parametro     pa_parametro%rowtype;
   
    c_dados CURSOR FOR
      --  para recuperar o protocolo indicado e os juntados a ele
      select a.sq_siw_solicitacao as chave 
        from pa_documento               a
             inner join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
       where a.sq_siw_solicitacao = p_chave
          or b.sq_solic_pai       = p_chave;
BEGIN
   
   -- Recupera os parâmetros do módulo
   select a.* into w_parametro
     from pa_parametro        a 
          inner join siw_menu b on (a.cliente = b.sq_pessoa)
    where b.sq_menu = p_menu;
   
   -- Recupera os dados do trâmite atual
   select sigla into w_sg_tramite from siw_tramite a where a.sq_siw_tramite = p_tramite;
   
   -- Recupera a chave do protocolo informado
   If p_prefixo is not null Then
      select sq_siw_solicitacao
        into w_pai
        from pa_documento x
       where x.prefixo          = p_prefixo
         and x.numero_documento = p_numero
         and x.ano              = p_ano;
   End If;
   
  for crec in c_dados loop
     -- Recupera o trâmite para o qual está sendo enviada a solicitação
     If w_sg_tramite = 'CI' Then
        select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
           from siw_tramite a
          where a.sq_menu = p_menu
            and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = p_tramite);
  
        -- Recupera a próxima chave
        select sq_siw_solic_log.nextval into w_chave from dual;
        
        -- Se houve mudança de fase, grava o log
        Insert Into siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
             sq_siw_tramite,            data,               devolucao, 
             observacao
            )
        (Select 
             w_chave,                   crec.chave,         p_pessoa,
             p_tramite,                 now(),            'N',
             'Envio da fase "'||a.nome||'" '||' para a fase "'||b.nome||'".'
            from siw_tramite a,
                 siw_tramite b
           where a.sq_siw_tramite = p_tramite
             and b.sq_siw_tramite = w_tramite
        );
    
        Update siw_solicitacao set sq_siw_tramite = w_tramite Where sq_siw_solicitacao = crec.chave;
     End If;
  
     -- Atualiza os dados do documento
     If p_interno = 'S' Then
        -- Se tramitação interna, atualiza a unidade de posse e mantém a pessoa externa
        update pa_documento a set a.unidade_int_posse = p_unidade_destino where a.sq_siw_solicitacao = crec.chave;
     Else
        -- Se tramitação externa, mantém a unidade de posse e atualiza a pessoa externa
        update pa_documento a set a.pessoa_ext_posse = p_pessoa_destino where a.sq_siw_solicitacao = crec.chave;
     End If;
     
     -- Se anexação ou apensação, indica a que documento será juntado no recebimento
     If w_pai is not null Then
        update pa_documento a set a.sq_documento_pai = w_pai where a.sq_siw_solicitacao = crec.chave;
     End If;
     
     -- Configura dados para gravação da tramitação
     If p_retorno_limite is not null Then w_retorno_unid := p_unidade_origem; End If;
     
     -- Recupera a nova chave da tabela de encaminhamentos da demanda
     select sq_documento_log.nextval into w_chave_dem from dual;
         
     -- Insere registro na tabela de encaminhamentos do documento
     insert into pa_documento_log
       (sq_documento_log,        sq_siw_solicitacao,         sq_siw_solic_log,          sq_tipo_despacho,           interno, 
        unidade_origem,          unidade_destino,            pessoa_destino,            cadastrador,                data_inclusao,
        resumo,                  envio,                      emite_aviso,               dias_aviso,                 retorno_limite,
        retorno_unidade,         pessoa_externa,             unidade_externa,           quebra_sequencia,           nu_guia,
        ano_guia,                recebedor)
     values
       (w_chave_dem,             crec.chave,                 w_chave,                   p_tipo_despacho,            p_interno, 
        p_unidade_origem,        p_unidade_destino,          p_pessoa_destino,          p_pessoa,                   now(),
        p_despacho,              now(),                    p_emite_aviso,             coalesce(p_dias_aviso,0),   p_retorno_limite,
        w_retorno_unid,          p_pessoa_externa,           p_unidade_externa,         'N',                        p_nu_guia,
        p_ano_guia,              case p_unidade_origem when p_unidade_destino then p_pessoa else null end);
     
     If p_unidade_origem = p_unidade_destino Then
        -- Se o envio for para a própria unidade, não emite guia de tramitação e já registra o recebimento
        update pa_documento_log set recebimento = now() where sq_documento_log = w_chave_dem;
        p_nu_guia          := null;
        p_ano_guia         := null;
        p_unidade_autuacao := null;
     Else
        -- Recupera o número e o ano da guia de remessa de documentos
        select a.nu_guia, a.ano_guia, coalesce(c.sq_unidade_pai, sq_unidade)
          into p_nu_guia, p_ano_guia, p_unidade_autuacao 
         from pa_documento_log          a 
              inner   join pa_documento b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                inner join pa_unidade   c on (b.unidade_autuacao   = c.sq_unidade)
        where sq_documento_log = w_chave_dem;
     End If;
  end loop;
   END; $$ LANGUAGE 'PLPGSQL' VOLATILE;