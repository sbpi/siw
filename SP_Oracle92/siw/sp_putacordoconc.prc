create or replace procedure SP_PutAcordoConc
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_inicio_real         in date      default null,
    p_fim_real            in date      default null,
    p_nota_conclusao      in varchar2  default null,
    p_custo_real          in number    default null,
    p_tipo                in number    default null
   ) is
   w_chave_dem     number(18) := null;
   w_cont          number(18) := 0;
   w_sg_tramite    varchar2(10);
   w_texto         varchar2(255);
   w_valor         number(18,2);
begin
   -- Verifica o tipo de conclusão para configurar alguns dados
   If p_tipo = 2 Then -- Rescisão de contrato
      w_texto      := 'Rescisão do contrato.';
      w_sg_tramite := 'CR';
      
      -- O valor do contrato passa a ser a soma das parcelas com vencimento
      -- anterior à rescisão.
      select sum(a.valor)
        into w_valor
        from ac_acordo_parcela a
       where a.sq_siw_solicitacao = p_chave
         and a.vencimento < p_fim_real;
   Else
      w_texto := 'Encerramento do contrato.';
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
       p_tramite,                 sysdate,            'N',
       w_texto);
       
   -- Atualiza o registro do acordo com os dados da conclusão.
   Update ac_acordo a set observacao = p_nota_conclusao Where sq_siw_solicitacao = p_chave;

   -- Atualiza a situação da solicitação
   Update siw_solicitacao set
      conclusao      = sysdate,
      sq_siw_tramite = (select sq_siw_tramite 
                          from siw_tramite 
                         where sq_menu        = p_menu 
                           and Nvl(sigla,'z') = w_sg_tramite
                       )
   Where sq_siw_solicitacao = p_chave;

end SP_PutAcordoConc;
/
