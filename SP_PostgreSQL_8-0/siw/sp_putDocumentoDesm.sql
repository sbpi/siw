create or replace FUNCTION sp_putDocumentoDesm
   (p_chave                numeric,
    p_usuario              numeric
   ) RETURNS VOID AS $$
DECLARE

   w_protocolo  varchar(20);
   w_chave_pai  numeric(18);
   w_data_atual date := now();
BEGIN
   select a.sq_documento_pai, b.prefixo||'.'||substr(cast(1000000+b.numero_documento as varchar),2,6)||'/'||b.ano||'-'||substr(cast(100+b.digito as varchar),2,2)
     into w_chave_pai,        w_protocolo
     from pa_documento a
          inner join pa_documento b on (a.sq_documento_pai = b.sq_siw_solicitacao)
    where a.sq_siw_solicitacao = p_chave;
    
   -- Atualiza a tabela de solicitações
   Update siw_solicitacao a
      set a.ultima_alteracao = now(),
          a.sq_solic_pai     = null
    where a.sq_siw_solicitacao = p_chave;
      
   -- Atualiza a tabela de documentos
   Update pa_documento a
      set a.sq_documento_pai  = null,
          a.data_desapensacao = w_data_atual,
          a.tipo_juntada      = 'N'
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
         'Desmembramento do processo '||w_protocolo||'.'
        from siw_solicitacao a
       where a.sq_siw_solicitacao = p_chave
    );END; $$ LANGUAGE 'PLPGSQL' VOLATILE;