create or replace FUNCTION SP_PutLcFinalidade
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,
    p_nome                      varchar,
    p_descricao                 varchar,
    p_ativo                     varchar,
    p_padrao                    varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_finalidade
             (sq_lcfinalidade,         cliente,   nome,   descricao,   ativo,   padrao
             )
      (select nextVal('sq_lcfinalidade'), p_cliente, p_nome, p_descricao, p_ativo, p_padrao
        
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update lc_finalidade set 
         nome                  = p_nome,
         descricao             = p_descricao,
         ativo                 = p_ativo,
         padrao                = p_padrao
       where sq_lcfinalidade = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM lc_finalidade where sq_lcfinalidade = p_chave;
   End If;