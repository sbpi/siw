create or replace procedure sp_putDocumentoEnvio
   (p_menu                in  number,
    p_chave               in  number,
    p_pessoa              in  number,
    p_tramite             in  number,
    p_interno             in  varchar2,
    p_unidade_origem      in  number,
    p_unidade_destino     in  number   default null,
    p_pessoa_destino      in  number   default null,
    p_tipo_despacho       in  number,
    p_prefixo             in number   default null,
    p_numero              in number   default null,
    p_ano                 in number   default null,
    p_despacho            in  varchar2,
    p_emite_aviso         in  varchar2,
    p_dias_aviso          in  number   default null,
    p_retorno_limite      in  date     default null,
    p_pessoa_externa      in  varchar2  default null,
    p_unidade_externa     in  varchar2 default null,
    p_nu_guia             in out number,
    p_ano_guia            in out number,
    p_unidade_autuacao    in out number
   ) is
   
   w_chave         number(18) := null;
   w_chave_dem     number(18) := null;
   w_tramite       number(18);
   w_sg_tramite    siw_menu.sigla%type;
   w_retorno_unid  number(18) := null;
   w_pai           number(18) := null;
   w_parametro     pa_parametro%rowtype;
   w_data          date := sysdate;
   w_destino       siw_solicitacao.codigo_interno%type := '';
   
   cursor c_dados is
      -- cursor para recuperar o protocolo indicado e os juntados a ele
      select a.sq_siw_solicitacao as chave, sigla as sg_tramite_atual
        from pa_documento                 a
             inner   join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
               inner join siw_tramite     c on (b.sq_siw_tramite     = c.sq_siw_tramite)
       where a.sq_siw_solicitacao = p_chave
          or b.sq_solic_pai       = p_chave;
begin
   -- Recupera os parâmetros do módulo
   select a.* into w_parametro
     from pa_parametro        a 
          inner join siw_menu b on (a.cliente = b.sq_pessoa)
    where b.sq_menu = p_menu;
   
   -- Recupera os dados do trâmite atual
   select sigla into w_sg_tramite from siw_tramite a where a.sq_siw_tramite = p_tramite;
   
   -- Recupera a chave do protocolo informado
   If p_prefixo is not null Then
      select sq_siw_solicitacao, ' (RECEBEDOR: '||x.prefixo||'.'||substr(1000000+x.numero_documento,2,6)||'/'||x.ano||'-'||substr(100+x.digito,2,2)||')'
        into w_pai,  w_destino
        from pa_documento x
       where x.prefixo          = p_prefixo
         and x.numero_documento = p_numero
         and x.ano              = p_ano;
   End If;
   
  for crec in c_dados loop
     -- Se o protocolo estiver arquivado setorialmente, desarquiva automaticamente
     If crec.sg_tramite_atual = 'AS' Then
        -- Coloca o protocolo em tramitação
        select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
           from siw_tramite a
          where a.sq_menu = p_menu
            and a.ordem   = 2;
        
        -- Atualiza a tabela de solicitações
        Update siw_solicitacao set sq_siw_tramite = w_tramite Where sq_siw_solicitacao = crec.chave;

        -- Atualiza a tabela de documentos
        update pa_documento set observacao_setorial = null, data_setorial = null, sq_caixa = null, pasta = null where sq_siw_solicitacao = crec.chave;

         -- Registra os dados do desarquivamento
         Insert Into siw_solic_log 
             (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
              sq_siw_tramite,            data,                 devolucao, 
              observacao
             )
         (Select 
              sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_pessoa,
              a.sq_siw_tramite,          w_data,               'N',
              'Desarquivamento setorial automático.'
             from siw_solicitacao a
            where a.sq_siw_solicitacao = crec.chave
         );
     End If;
     
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
             p_tramite,                 w_data,             'N',
             'Envio da fase "'||a.nome||'" '||' para a fase "'||b.nome||'".'
            from siw_tramite a,
                 siw_tramite b
           where a.sq_siw_tramite = p_tramite
             and b.sq_siw_tramite = w_tramite
        );
    
        Update siw_solicitacao set sq_siw_tramite = w_tramite Where sq_siw_solicitacao = crec.chave;
     End If;
  
     -- Atualiza os dados do documento
     If p_interno = 'S' or (p_interno is null and p_unidade_destino is not null) Then
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
        p_unidade_origem,        p_unidade_destino,          p_pessoa_destino,          p_pessoa,                   w_data,
        p_despacho||w_destino,   w_data,                     p_emite_aviso,             coalesce(p_dias_aviso,0),   p_retorno_limite,
        w_retorno_unid,          p_pessoa_externa,           p_unidade_externa,         'N',                        p_nu_guia,
        p_ano_guia,              case p_unidade_origem when p_unidade_destino then p_pessoa else null end);
     
     If p_unidade_origem = p_unidade_destino Then
        -- Se o envio for para a própria unidade, não emite guia de tramitação e já registra o recebimento
        update pa_documento_log set recebimento = w_data where sq_documento_log = w_chave_dem;
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
   
end sp_putDocumentoEnvio;
/
