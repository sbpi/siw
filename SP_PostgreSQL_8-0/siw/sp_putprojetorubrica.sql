create or replace FUNCTION SP_PutProjetoRubrica
   (p_operacao              varchar,
    p_chave                numeric,
    p_chave_aux            numeric,
    p_sq_cc                numeric, 
    p_codigo               varchar,
    p_nome                 varchar,
    p_descricao            varchar,
    p_ativo                varchar,
    p_aplicacao_financeira varchar,
    p_copia                numeric    
   ) RETURNS VOID AS $$
DECLARE
   w_chave   numeric(18);
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera o valor da próxima chave
      select nextVal('sq_projeto_rubrica') into  w_chave;
      
      -- Insere registro na tabela de recursos
      Insert Into pj_rubrica
         ( sq_projeto_rubrica, sq_siw_solicitacao,       sq_cc,        codigo, nome, descricao, ativo, aplicacao_financeira)
      Values 
         ( w_chave,            p_chave,                p_sq_cc,      p_codigo, p_nome, p_descricao, p_ativo, p_aplicacao_financeira);
         
      -- Se for cópia, herda o cronograma desembolso
      If p_copia is not null Then
         insert into pj_rubrica_cronograma
           (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real)
         (select nextVal('sq_rubrica_cronograma'), w_chave, inicio, fim, valor_previsto, valor_real
            from pj_rubrica_cronograma a
           where a.sq_projeto_rubrica = p_copia
         );
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de recursos
      Update pj_rubrica set
          sq_cc                = p_sq_cc,
          codigo               = p_codigo,
          nome                 = p_nome,
          descricao            = p_descricao,
          ativo                = p_ativo,
          aplicacao_financeira = p_aplicacao_financeira                       
      where sq_siw_solicitacao = p_chave
        and sq_projeto_rubrica = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de recursos
      DELETE FROM pj_rubrica 
       where sq_siw_solicitacao  = p_chave
         and sq_projeto_rubrica  = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;