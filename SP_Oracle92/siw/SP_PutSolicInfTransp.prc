create or replace procedure SP_PutSolicInfTransp
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_executor            in number    default null,
    p_sq_veiculo          in number    default null
   ) is
   
   w_chave_dem     number(18) := null;
   w_chave_arq     number(18) := null;
begin
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
       x.sq_siw_tramite,          sysdate,            'N',
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

end SP_PutSolicInfTransp;
/
