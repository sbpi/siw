create or replace procedure sp_putDocumentoAnexa
   (p_chave               in  number,
    p_usuario             in  number
   ) is

   w_protocolo  varchar2(20);
   w_chave_pai  number(18);
   w_data_atual date := sysdate;
begin
   select a.sq_documento_pai, b.prefixo||'.'||substr(1000000+b.numero_documento,2,6)||'/'||b.ano||'-'||substr(100+b.digito,2,2)
     into w_chave_pai,        w_protocolo
     from pa_documento a
          inner join pa_documento b on (a.sq_documento_pai = b.sq_siw_solicitacao)
    where a.sq_siw_solicitacao = p_chave;
    
   -- Atualiza a tabela de solicita��es
   Update siw_solicitacao a
      set a.ultima_alteracao = w_data_atual,
          a.sq_solic_pai     = w_chave_pai
    where a.sq_siw_solicitacao = p_chave;
      
   -- Atualiza a tabela de documentos
   Update pa_documento a
      set a.data_juntada = w_data_atual,
          a.tipo_juntada = 'A'
    where a.sq_siw_solicitacao = p_chave;
      
    -- Registra os dados da Anexa��o
    Insert Into siw_solic_log 
        (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
         sq_siw_tramite,            data,               devolucao, 
         observacao
        )
    (Select 
         sq_siw_solic_log.nextval,  p_chave,            p_usuario,
         a.sq_siw_tramite,          w_data_atual,       'N',
         'Anexa��o ao processo '||w_protocolo||'.'
        from siw_solicitacao a
       where a.sq_siw_solicitacao = p_chave
    );
end sp_putDocumentoAnexa;
/
