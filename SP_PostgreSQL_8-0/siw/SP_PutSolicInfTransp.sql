create or replace FUNCTION SP_PutSolicInfTransp
   (p_menu                numeric,
    p_chave               numeric,
    p_pessoa              numeric,
    p_executor            numeric,
    p_sq_veiculo          numeric    
   ) RETURNS VOID AS $$
DECLARE
   
   w_chave_dem     numeric(18) := null;
   w_chave_arq     numeric(18) := null;
BEGIN
   -- Recupera a chave do log
   select sq_siw_solic_log.nextval into w_chave_dem from dual;
   
   -- Insere registro na tabela de log da solicitacao
   Insert Into siw_solic_log 
      (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
       sq_siw_tramite,            data,               devolucao, 
       observacao
      )
   (select
       w_chave_dem,               p_chave,            p_pessoa,
       x.sq_siw_tramite,          now(),            'N',
       'Informações sobre o atendimento da solicitação.'||chr(13)||chr(10)||
       'Veículo: '||substr(y.placa,1,3)||'-'||substr(y.placa,4)||chr(13)||chr(10)||
       'Motorista: '||z.nome
      from siw_solicitacao x,
           sr_veiculo      y,
           co_pessoa       z
     where x.sq_siw_solicitacao = p_chave
       and y.sq_veiculo         = p_sq_veiculo
       and z.sq_pessoa          = p_executor
   );
       
   -- Atualiza a situação da solicitação
   Update siw_solicitacao set executor = p_executor Where sq_siw_solicitacao = p_chave;
   
   -- Atualiza a tabela de sr_solicitações_transporte
   Update sr_solicitacao_transporte set sq_veiculo = p_sq_veiculo where sq_siw_solicitacao = p_chave;   
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;