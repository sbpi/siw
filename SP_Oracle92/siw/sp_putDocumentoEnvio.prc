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
   w_sg_tramite    varchar2(2);
   w_retorno_unid  number(18) := null;
begin
   -- Recupera os dados do tr�mite atual
   select sigla into w_sg_tramite from siw_tramite a where a.sq_siw_tramite = p_tramite;
   
   -- Recupera o tr�mite para o qual est� sendo enviada a solicita��o
   If w_sg_tramite = 'CI' Then
      select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = p_tramite);

      -- Recupera a pr�xima chave
      select sq_siw_solic_log.nextval into w_chave from dual;
      
      -- Se houve mudan�a de fase, grava o log
      Insert Into siw_solic_log 
          (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
           sq_siw_tramite,            data,               devolucao, 
           observacao
          )
      (Select 
           w_chave,                   p_chave,            p_pessoa,
           p_tramite,                 sysdate,            'N',
           'Envio da fase "'||a.nome||'" '||' para a fase "'||b.nome||'".'
          from siw_tramite a,
               siw_tramite b
         where a.sq_siw_tramite = p_tramite
           and b.sq_siw_tramite = w_tramite
      );
  
      Update siw_solicitacao set sq_siw_tramite = w_tramite Where sq_siw_solicitacao = p_chave;
   End If;

   -- Atualiza os dados do documento
   If p_interno = 'S' Then
      -- Se tramita��o interna, atualiza a unidade de posse e mant�m a pessoa externa
      update pa_documento a set a.unidade_int_posse = p_unidade_destino where a.sq_siw_solicitacao = p_chave;
   Else
      -- Se tramita��o externa, mant�m a unidade de posse e atualiza a pessoa externa
      update pa_documento a set a.pessoa_ext_posse = p_pessoa_destino where a.sq_siw_solicitacao = p_chave;
   End If;
   
   -- Configura dados para grava��o da tramita��o
   If p_retorno_limite is not null Then w_retorno_unid := p_unidade_origem; End If;
   
   -- Recupera a nova chave da tabela de encaminhamentos da demanda
   select sq_documento_log.nextval into w_chave_dem from dual;
       
   -- Insere registro na tabela de encaminhamentos do documento
   insert into pa_documento_log
     (sq_documento_log,        sq_siw_solicitacao,         sq_siw_solic_log,          sq_tipo_despacho,           interno, 
      unidade_origem,          unidade_destino,            pessoa_destino,            cadastrador,                data_inclusao,
      resumo,                  envio,                      emite_aviso,               dias_aviso,                 retorno_limite,
      retorno_unidade,         pessoa_externa,             unidade_externa,           quebra_sequencia,           nu_guia,
      ano_guia)
   values
     (w_chave_dem,             p_chave,                    w_chave,                   p_tipo_despacho,            p_interno, 
      p_unidade_origem,        p_unidade_destino,          p_pessoa_destino,          p_pessoa,                   sysdate,
      p_despacho,              sysdate,                    p_emite_aviso,             coalesce(p_dias_aviso,0),   p_retorno_limite,
      w_retorno_unid,          p_pessoa_externa,           p_unidade_externa,         'N',                        p_nu_guia,
      p_ano_guia);
   
   -- Recupera o n�mero e o ano da guia de remessa de documentos
   select a.nu_guia, a.ano_guia, b.unidade_autuacao 
     into p_nu_guia, p_ano_guia, p_unidade_autuacao 
    from pa_documento_log        a 
         inner join pa_documento b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao) 
   where sq_documento_log = w_chave_dem;
   
end sp_putDocumentoEnvio;
/
