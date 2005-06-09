create or replace procedure SP_PutFinanceiroConc
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_quitacao            in date,
    p_valor_real          in number,
    p_codigo_deposito     in varchar2  default null,
    p_observacao          in varchar2  default null,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null
   ) is
   w_chave         number(18) := null;
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
   Values
      (w_chave_dem,               p_chave,            p_pessoa,
       p_tramite,                 sysdate,            'N',
       'Liquidação do lançamento financeiro');
       
   -- Atualiza o registro da demanda com os dados da conclusão.
   Update fn_lancamento set
      quitacao         = p_quitacao,
      codigo_deposito  = p_codigo_deposito,
      observacao       = p_observacao
   Where sq_siw_solicitacao = p_chave;

   -- Atualiza a situação da solicitação
   Update siw_solicitacao set
      valor          = p_valor_real,
      conclusao      = sysdate,
      sq_siw_tramite = (select sq_siw_tramite 
                          from siw_tramite 
                         where sq_menu=p_menu 
                           and Nvl(sigla,'z')='AT'
                       )
   Where sq_siw_solicitacao = p_chave;

   select sq_acordo_parcela into w_chave from fn_lancamento where sq_siw_solicitacao = p_chave;
   
   -- Se o lançamento for de parcela de contrato, registra a baixa.
   If w_chave is not null Then
      Update ac_acordo_parcela 
         set quitacao = p_quitacao 
       where sq_acordo_parcela = w_chave;
   End If;
    
   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave_arq from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho)
      (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, sysdate, 
              p_tamanho,   p_tipo,        p_caminho
         from co_pessoa a
        where a.sq_pessoa = p_pessoa
      );
      
      -- Insere registro em SIW_SOLIC_LOG_ARQ
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
      values (w_chave_dem, w_chave_arq);
   End If;
end SP_PutFinanceiroConc;
/

