create or replace procedure sp_putDocumentoCaixa
   (p_menu                in  number,
    p_chave               in  number,
    p_usuario             in  number,
    p_caixa               in  number,
    p_pasta               in varchar2
   ) is
   
begin
  -- Atualiza a tabela de documentos
  update pa_documento 
    set sq_caixa      = p_caixa,
        pasta         = p_pasta
  where sq_siw_solicitacao = p_chave;
  
  -- Registra os dados da autuação
  Insert Into siw_solic_log 
       (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
        sq_siw_tramite,            data,               devolucao, 
        observacao
       )
  (Select 
        sq_siw_solic_log.nextval,  p_chave,            p_usuario,
        a.sq_siw_tramite,          sysdate,            'N',
        'Preparação para envio ao Arquivo Central. Caixa: '||c.numero||'/'||d.sigla||', Pasta: '||p_pasta||'.'
       from siw_solicitacao         a
            inner join pa_documento b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao),
            pa_caixa                c
            inner join eo_unidade   d on (c.sq_unidade         = d.sq_unidade)
      where a.sq_siw_solicitacao = p_chave
        and c.sq_caixa           = p_caixa
  );
end sp_putDocumentoCaixa;
/
