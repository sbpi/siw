create or replace function siw.SP_PutViagemEnvio
   (p_menu                numeric,
    p_chave               numeric,
    p_pessoa              numeric,
    p_tramite             numeric,
    p_novo_tramite        numeric    ,
    p_devolucao           varchar,
    p_despacho            varchar,
    p_justificativa       varchar
   )RETURNS character varying AS


$BODY$declare
   w_chave         numeric(18) := null;
   w_chave_dem     numeric(18) := null;
   w_tramite       numeric(18);
   w_sg_tramite    varchar(2);
begin
   -- Recupera o trâmite para o qual está sendo enviada a solicitação
   If p_devolucao = 'N' Then
      select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
         from siw.siw_tramite a
        where a.sq_menu = p_menu
          and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = p_tramite);
   Else
      select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
         from  siw.siw_tramite a
        where a.sq_siw_tramite = p_novo_tramite;
   End If;

   -- Recupera a próxima chave
   select sq_siw_solic_log.nextval into w_chave from dual;

   -- Se houve mudança de fase, grava o log
   Insert Into  siw.siw_solic_log
       (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa,
        sq_siw_tramite,            data,               devolucao,
        observacao
       )
   (Select
        w_chave,                   p_chave,            p_pessoa,
        p_tramite,                 sysdate,            p_devolucao,
        case p_devolucao when 'S' then 'Devolução da fase "' else 'Envio da fase "' end ||a.nome||'" '||
        ' para a fase "'||b.nome||'".'
       from  siw.siw_tramite a,
             siw.siw_tramite b
      where a.sq_siw_tramite = p_tramite
        and b.sq_siw_tramite = w_tramite
   );

   Update  siw.siw_solicitacao set
      sq_siw_tramite        = w_tramite,
      conclusao             = null,
      justificativa         = coalesce(p_justificativa, justificativa)
   Where sq_siw_solicitacao = p_chave;

   -- Atualiza o situacao da demanda para não concluída
   Update  siw.gd_demanda set
      concluida      = 'N',
      inicio_real    = null,
      fim_real       = null,
      data_conclusao = null,
      nota_conclusao = null,
      custo_real     = 0
   Where sq_siw_solicitacao = p_chave;

   -- Se um despacho foi informado, insere em GD_DEMANDA_LOG.
   If p_despacho is not null Then
      -- Recupera a nova chave da tabela de encaminhamentos da demanda
      select sq_demanda_log.nextval into w_chave_dem from dual;

      -- Insere registro na tabela de encaminhamentos da demanda
      Insert into  siw.gd_demanda_log
         (sq_demanda_log,            sq_siw_solicitacao, cadastrador,
          destinatario,              data_inclusao,      observacao,
          despacho,                  sq_siw_solic_log
         )
      Values (
          w_chave_dem,               p_chave,            p_pessoa,
          null,                      now(),            null,
          p_despacho,                w_chave
       );
   End If;

   commit;

end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
