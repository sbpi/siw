create or replace procedure SP_PutCLConc
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number
   ) is
   w_chave_dem     number(18) := null;
   w_sg_tramite    varchar2(10);
   w_texto         varchar2(255);
begin   

   -- Recupera a chave do log
   select sq_siw_solic_log.nextval into w_chave_dem from dual;
   
   -- Insere registro na tabela de log da solicitacao
   Insert Into siw_solic_log 
      (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
       sq_siw_tramite,            data,               devolucao, 
       observacao
      )
   Values
      (w_chave_dem,               p_chave,            p_pessoa,
       p_tramite,                 sysdate,            'N',
       w_texto);
       

   -- Atualiza a situação da solicitação
   Update siw_solicitacao set
      conclusao      = sysdate,
      sq_siw_tramite = (select sq_siw_tramite 
                          from siw_tramite 
                         where sq_menu        = p_menu 
                           and Nvl(sigla,'z') = 'AT'
                       )
   Where sq_siw_solicitacao = p_chave;

end SP_PutCLConc;
/
