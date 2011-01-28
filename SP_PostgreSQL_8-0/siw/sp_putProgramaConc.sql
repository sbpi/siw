create or replace FUNCTION sp_putProgramaConc
   (p_menu                numeric,
    p_chave               numeric,
    p_pessoa              numeric,
    p_tramite             numeric,
    p_inicio_real         date,
    p_fim_real            date,
    p_nota_conclusao      varchar,
    p_custo_real          numeric    
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   -- Insere registro na tabela de log da solicitacao
   Insert Into siw_solic_log 
      (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
       sq_siw_tramite,            data,               devolucao, 
       observacao
      )
   Values
      (sq_siw_solic_log.nextval,  p_chave,            p_pessoa,
       p_tramite,                 now(),            'N',
       'Conclusão do programa');
       
   -- Atualiza o registro do programa com os dados da conclusão.
   Update pe_programa set
      inicio_real     = p_inicio_real,
      fim_real        = p_fim_real,
      nota_conclusao  = p_nota_conclusao,
      custo_real      = p_custo_real
   Where sq_siw_solicitacao = p_chave;

   -- Atualiza a situação da solicitação
   Update siw_solicitacao set
      sq_siw_tramite = (select sq_siw_tramite 
                          from siw_tramite 
                         where sq_menu=p_menu 
                           and Nvl(sigla,'z')='AT'
                       )
   Where sq_siw_solicitacao = p_chave;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;