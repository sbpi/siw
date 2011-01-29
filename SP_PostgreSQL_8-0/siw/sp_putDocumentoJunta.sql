create or replace FUNCTION sp_putDocumentoJunta
   (p_chave                numeric,
    p_usuario              numeric
   ) RETURNS VOID AS $$
DECLARE

   w_protocolo  varchar(20);
   w_chave_pai  numeric(18);
   w_data_atual date := now();
BEGIN
   select a.sq_documento_pai, b.prefixo||'.'||substr(1000000+b.numero_documento,2,6)||'/'||b.ano||'-'||substr(100+b.digito,2,2)
     into w_chave_pai,        w_protocolo
     from pa_documento a
          inner join pa_documento b on (a.sq_documento_pai = b.sq_siw_solicitacao)
    where a.sq_siw_solicitacao = p_chave;
    
   -- Atualiza a tabela de solicitações
   Update siw_solicitacao a
      set a.ultima_alteracao = now(),
          a.sq_solic_pai     = w_chave_pai
    where a.sq_siw_solicitacao = p_chave;
      
   -- Atualiza a tabela de documentos
   Update pa_documento a
      set a.data_juntada      = w_data_atual,
          a.tipo_juntada      = 'P',
          a.data_desapensacao = null
    where a.sq_siw_solicitacao = p_chave;
      
    -- Registra os dados da apensação
    Insert Into siw_solic_log 
        (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
         sq_siw_tramite,            data,               devolucao, 
         observacao
        )
    (Select 
         nextVal('sq_siw_solic_log'),  p_chave,            p_usuario,
         a.sq_siw_tramite,          now(),            'N',
         'Apensação ao processo '||w_protocolo||'.'
        from siw_solicitacao a
       where a.sq_siw_solicitacao = p_chave
    );END; $$ LANGUAGE 'PLPGSQL' VOLATILE;