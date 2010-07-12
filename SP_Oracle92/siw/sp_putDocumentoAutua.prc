create or replace procedure sp_putDocumentoAutua
   (p_chave               in  number,
    p_unidade             in  number,
    p_usuario             in  number,
    p_descricao           in  varchar2
   ) is
   
   cursor c_log is
     -- Recupera o tr�mite mais atual que solicitou a autua��o do processo
     select l.sq_documento_log
       from (select w.sq_siw_solicitacao, max(w.envio) as envio
               from pa_documento_log              w
                    inner   join siw_solicitacao  x  on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
                    inner   join pa_tipo_despacho y  on (w.sq_tipo_despacho = y.sq_tipo_despacho)
              where y.sigla   = 'AUTUAR'
             group by w.sq_siw_solicitacao
            )                                        k
            inner   join pa_documento_log            l on (k.sq_siw_solicitacao = l.sq_siw_solicitacao and
                                                           k.envio              = l.envio
                                                          )
      where k.sq_siw_solicitacao = p_chave;
begin
   -- Atualiza a tabela de solicita��es
   Update siw_solicitacao set descricao = p_descricao, ultima_alteracao = sysdate where sq_siw_solicitacao = p_chave;
      
   -- Atualiza a tabela de documentos
   update pa_documento set
       processo              = 'S',
       unidade_autuacao      = (select sq_unidade from sg_autenticacao where sq_pessoa = p_usuario),
       data_autuacao         = sysdate
    where sq_siw_solicitacao = p_chave;

    -- Registra os dados da autua��o
    Insert Into siw_solic_log 
        (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
         sq_siw_tramite,            data,               devolucao, 
         observacao
        )
    (Select 
         sq_siw_solic_log.nextval,  p_chave,            p_usuario,
         a.sq_siw_tramite,          sysdate,            'N',
         'Autua��o de processo.'
        from siw_solicitacao a
       where a.sq_siw_solicitacao = p_chave
    );
    
    -- Atualiza o tr�mite que solicitou a autua��o do processo para a unidade indicada pelo usu�rio autuador
    for crec in c_log loop
        update pa_documento_log set unidade_origem = p_unidade where sq_documento_log = crec.sq_documento_log;
    end loop;
end sp_putDocumentoAutua;
/
