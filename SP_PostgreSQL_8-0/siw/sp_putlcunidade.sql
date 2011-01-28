create or replace FUNCTION SP_PutLcUnidade
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cnpj                      varchar,
    p_licita                    varchar,
    p_contrata                  varchar,
    p_ativo                     varchar,
    p_padrao                    varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_unidade
             (sq_unidade,   cnpj,   licita,   contrata,   ativo,   padrao
             )
      (select    p_chave, p_cnpj, p_licita, p_contrata, p_ativo, p_padrao
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update lc_unidade set 
         cnpj                  = p_cnpj,
         licita                = p_licita,
         contrata              = p_contrata,
         ativo                 = p_ativo,
         padrao                = p_padrao
       where sq_unidade = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM lc_unidade where sq_unidade = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;