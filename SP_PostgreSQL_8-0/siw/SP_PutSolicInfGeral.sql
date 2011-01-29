create or replace FUNCTION SP_PutSolicInfGeral
   (p_menu                numeric,
    p_chave               numeric,
    p_pessoa              numeric,
    p_executor            numeric,
    p_inicio              date,
    p_fim                 date,
    p_valor               numeric    
   ) RETURNS VOID AS $$
DECLARE
   
   w_chave_dem     numeric(18) := null;
BEGIN
   -- Recupera a chave do log
   select nextVal('sq_siw_solic_log') into w_chave_dem;
   
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
       case when p_inicio   is null then '' else 'Início previsto: '||to_char(p_inicio,'dd/mm/yyyy')||chr(13)||chr(10) end||
       case when p_fim      is null then '' else 'Término previsto: '||to_char(p_fim,'dd/mm/yyyy')||chr(13)||chr(10) end||
       case when p_valor    is null then '' else 'Valor previsto: '||fValor(p_valor,'T')||chr(13)||chr(10) end||
       case when p_executor is null then '' else 'Executor: '||z.nome||chr(13)||chr(10) end
      from siw_solicitacao x,
           co_pessoa       z
     where x.sq_siw_solicitacao = p_chave
       and z.sq_pessoa          = coalesce(p_executor,p_pessoa)
   );
       
   -- Atualiza a situação da solicitação
   Update siw_solicitacao 
      set executor = case when p_executor is not null then p_executor else executor end,
          inicio   = case when p_inicio   is not null then p_inicio   else inicio   end,
          fim      = case when p_fim      is not null then p_fim      else fim      end,
          valor    = p_valor
   Where sq_siw_solicitacao = p_chave;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;