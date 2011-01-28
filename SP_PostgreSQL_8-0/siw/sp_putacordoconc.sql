create or replace FUNCTION SP_PutAcordoConc
   (p_menu                numeric,
    p_chave               numeric,
    p_pessoa              numeric,
    p_tramite             numeric,
    p_inicio_real         date,
    p_fim_real            date,
    p_nota_conclusao      varchar,
    p_custo_real          numeric,
    p_tipo                numeric    
   ) RETURNS VOID AS $$
DECLARE
   w_chave_dem     numeric(18) := null;
   w_cont          numeric(18) := 0;
   w_sg_tramite    varchar(10);
   w_texto         varchar(255);
   w_valor         numeric(18,2);
BEGIN
   -- Verifica o tipo de conclusão para configurar alguns dados
   If p_tipo = 2 Then -- Rescisão de contrato
      w_texto      := 'Rescisão.';
      w_sg_tramite := 'CR';
      
      -- O valor do contrato passa a ser a soma das parcelas com vencimento
      -- anterior à rescisão.
      select sum(a.valor)
        into w_valor
        from ac_acordo_parcela a
       where a.sq_siw_solicitacao = p_chave
         and a.vencimento < p_fim_real;
   Else
      w_texto := 'Encerramento na data prevista.';
      w_valor := p_custo_real;

      -- Verifica se há parcelas em aberto para o acordo
      select count(*) into w_cont from ac_acordo_parcela a where a.quitacao is null and a.sq_siw_solicitacao=p_chave;
      If w_cont = 0 Then
         w_sg_tramite := 'AT';
      Else
         w_sg_tramite := 'ER';
      End If;
     
   End If;
   
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
       p_tramite,                 now(),            'N',
       w_texto);
       
   -- Atualiza o registro do acordo com os dados da conclusão.
   Update ac_acordo a set observacao = p_nota_conclusao Where sq_siw_solicitacao = p_chave;

   -- Atualiza a situação da solicitação
   Update siw_solicitacao set
      conclusao      = now(),
      sq_siw_tramite = (select sq_siw_tramite 
                          from siw_tramite 
                         where sq_menu        = p_menu 
                           and Nvl(sigla,'z') = w_sg_tramite
                       )
   Where sq_siw_solicitacao = p_chave;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;