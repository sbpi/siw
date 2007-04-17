create or replace procedure SP_PutSolicConcTransp
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_recebedor           in number,
    p_tramite             in number,
    p_executor            in number    default null,
    p_nota_conclusao      in varchar2  default null,
    p_valor               in number    default null,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar2  default null,
    p_sq_veiculo          in number    default null,
    p_hodometro_saida     in number    default null,
    p_hodometro_chegada   in number    default null,
    p_horario_saida       in varchar2  default null,
    p_horario_chegada     in varchar2  default null,
    p_parcial             in varchar2  default null      
    
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
   Values
      (w_chave_dem,               p_chave,            p_pessoa,
       p_tramite,                 sysdate,            'N',
       'Conclusão da solicitação');
       
   -- Atualiza a situação da solicitação
   Update siw_solicitacao set
      conclusao      = to_date(p_horario_chegada,'dd/mm/yyyy, hh24:mi'), 
      recebedor      = p_recebedor,
      executor       = p_executor,
      sq_siw_tramite = (select sq_siw_tramite 
                          from siw_tramite 
                         where sq_menu=p_menu 
                           and Nvl(sigla,'z')='AT'
                       )
   Where sq_siw_solicitacao = p_chave;
   
   -- Atualiza a tabela de sr_solicitações_transporte
   Update sr_solicitacao_transporte set
       sq_veiculo        = p_sq_veiculo, 
       hodometro_saida   = p_hodometro_saida,
       hodometro_chegada = p_hodometro_chegada,
       horario_saida     = to_date(p_horario_saida,'dd/mm/yyyy, hh24:mi'),
       horario_chegada   = to_date(p_horario_chegada,'dd/mm/yyyy, hh24:mi'),
       parcial           = p_parcial
   where sq_siw_solicitacao = p_chave;   

   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave_arq from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, sysdate, 
              p_tamanho,   p_tipo,        p_caminho, p_nome_original
         from co_pessoa a
        where a.sq_pessoa = p_pessoa
      );
      
      -- Insere registro em SIW_SOLIC_LOG_ARQ
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
      values (w_chave_dem, w_chave_arq);
   End If;
end SP_PutSolicConcTransp;
/
