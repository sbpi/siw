create or replace procedure SP_PutViagemEnvio
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_novo_tramite        in number    default null,    
    p_devolucao           in varchar2,
    p_despacho            in varchar2,
    p_justificativa       in varchar2
   ) is
   w_chave         number(18) := null;
   w_chave_dem     number(18) := null;
   w_tramite       number(18);
   w_sg_tramite    varchar2(2);
begin
   -- Recupera a próxima chave
   select sq_siw_solic_log.nextval into w_chave from dual;
   
   -- Recupera o trâmite para o qual está sendo enviada a solicitação
   If p_devolucao = 'N' Then
      select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = p_tramite);
   Else
      select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
         from siw_tramite a
        where a.sq_siw_tramite = p_novo_tramite;   
   End If;
   
   -- Recupera a próxima chave
   select sq_siw_solic_log.nextval into w_chave from dual;
    
   -- Se houve mudança de fase, grava o log
   Insert Into siw_solic_log 
       (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
        sq_siw_tramite,            data,               devolucao, 
        observacao
       )
   (Select 
        w_chave,                   p_chave,            p_pessoa,
        p_tramite,                 sysdate,            p_devolucao,
        case p_devolucao when 'S' then 'Devolução da fase "' else 'Envio da fase "' end ||a.nome||'" '||
        ' para a fase "'||b.nome||'".'
       from siw_tramite a,
            siw_tramite b
      where a.sq_siw_tramite = p_tramite
        and b.sq_siw_tramite = w_tramite
   );

   -- Atualiza a situação da demanda
   Update siw_solicitacao set
      sq_siw_tramite        = w_tramite,
      justificativa         = coalesce(p_justificativa, justificativa)
   Where sq_siw_solicitacao = p_chave;

   -- Se um despacho foi informado, insere em GD_DEMANDA_LOG.
   If p_despacho is not null Then
      -- Recupera a nova chave da tabela de encaminhamentos da demanda
      select sq_demanda_log.nextval into w_chave_dem from dual;
       
      -- Insere registro na tabela de encaminhamentos da demanda
      Insert into gd_demanda_log 
         (sq_demanda_log,            sq_siw_solicitacao, cadastrador, 
          destinatario,              data_inclusao,      observacao, 
          despacho,                  sq_siw_solic_log
         )
      Values (
          w_chave_dem,               p_chave,            p_pessoa,
          null,                      sysdate,            null,
          p_despacho,                w_chave
       );
   End If;
   
   commit;
      
end SP_PutViagemEnvio;
/
