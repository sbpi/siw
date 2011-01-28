create or replace FUNCTION SP_PutLcCriterio
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,
    p_nome                      varchar,
    p_descricao                 varchar,
    p_item                      varchar,
    p_ativo                     varchar,
    p_padrao                    varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_julgamento
             (sq_lcjulgamento,         cliente,   nome,   descricao,   item,   ativo,   padrao
             )
      (select sq_lcjulgamento.nextval, p_cliente, p_nome, p_descricao, p_item, p_ativo, p_padrao
        
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update lc_julgamento set 
         nome                  = p_nome,
         descricao             = p_descricao,
         item                  = p_item,
         ativo                 = p_ativo,
         padrao                = p_padrao
       where sq_lcjulgamento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM lc_julgamento where sq_lcjulgamento = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;