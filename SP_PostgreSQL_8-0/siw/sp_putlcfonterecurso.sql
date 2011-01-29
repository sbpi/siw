create or replace FUNCTION SP_PutLcFonteRecurso
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,
    p_nome                      varchar,
    p_descricao                 varchar,
    p_ativo                     varchar,
    p_padrao                    varchar,
    p_orcamentario              varchar,
    p_codigo                    varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_fonte_recurso
        (sq_lcfonte_recurso, cliente, nome, descricao, 
         orcamentario, ativo, padrao, codigo)
      (select nextVal('sq_lcfonte_recurso'), p_cliente, p_nome, p_descricao, p_orcamentario, 
              p_ativo, p_padrao, p_codigo
        );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update lc_fonte_recurso set 
         nome                  = p_nome,
         descricao             = p_descricao,
         orcamentario          = p_orcamentario,
         ativo                 = p_ativo,
         padrao                = p_padrao,
         codigo                = p_codigo
       where sq_lcfonte_recurso = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM lc_fonte_recurso where sq_lcfonte_recurso = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;