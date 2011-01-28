create or replace FUNCTION sp_putDocumentoAutua
   (p_chave                numeric,
    p_unidade              numeric,
    p_usuario              numeric,
    p_descricao            varchar
   ) RETURNS VOID AS $$
DECLARE
   
    c_log CURSOR FOR
     -- Recupera o trâmite mais atual que solicitou a autuação do processo
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
BEGIN
   -- Atualiza a tabela de solicitações
   Update siw_solicitacao set descricao = p_descricao, ultima_alteracao = now() where sq_siw_solicitacao = p_chave;
      
   -- Atualiza a tabela de documentos
   update pa_documento set
       processo              = 'S',
       unidade_autuacao      = (select sq_unidade from sg_autenticacao where sq_pessoa = p_usuario),
       data_autuacao         = now()
    where sq_siw_solicitacao = p_chave;

    -- Registra os dados da autuação
    Insert Into siw_solic_log 
        (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
         sq_siw_tramite,            data,               devolucao, 
         observacao
        )
    (Select 
         sq_siw_solic_log.nextval,  p_chave,            p_usuario,
         a.sq_siw_tramite,          now(),            'N',
         'Autuação de processo.'
        from siw_solicitacao a
       where a.sq_siw_solicitacao = p_chave
    );
    
    -- Atualiza o trâmite que solicitou a autuação do processo para a unidade indicada pelo usuário autuador
    for crec in c_log loop
        update pa_documento_log set unidade_origem = p_unidade where sq_documento_log = crec.sq_documento_log;
    end loop;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;