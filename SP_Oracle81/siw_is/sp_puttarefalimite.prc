create or replace procedure SP_PutTarefaLimite
   (p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_custo_real          in number
   ) is
   w_chave_dem     number(18) := null;
begin
   -- Recupera a chave do log
   select siw.sq_siw_solic_log.nextval into w_chave_dem from dual;
   
   -- Insere registro na tabela de log da solicitacao
   Insert Into siw.siw_solic_log 
      (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
       sq_siw_tramite,            data,               devolucao, 
       observacao
      )
   Values
      (w_chave_dem,               p_chave,            p_pessoa,
       p_tramite,                 sysdate,            'N',
       'Definição de limite orçamentário: ' || trim(translate(to_char(p_custo_real,'999,999,999,999,990.00'),',.','.,')));
       
   -- Atualiza o registro da tarefa com os dados da conclusão.
   Update siw.gd_demanda set
      custo_real      = p_custo_real
   Where sq_siw_solicitacao = p_chave;
end SP_PutTarefaLimite;
/
