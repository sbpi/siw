create or replace FUNCTION SP_PutLcUnidadeFornec
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,
    p_sigla                     varchar,
    p_nome                      varchar,
    p_descricao                 varchar,
    p_ativo                     varchar,
    p_padrao                    varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_unidade_fornec
             (sq_unidade_fornec,            cliente,   sigla, nome,   descricao,    ativo,   padrao
             )
      (select nextVal('sq_unidade_fornec'), p_cliente, p_sigla, p_nome, p_descricao,p_ativo, p_padrao
        
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update lc_unidade_fornec set 
         sigla                 = p_sigla,
         nome                  = p_nome,
         descricao             = p_descricao,
         ativo                 = p_ativo,
         padrao                = p_padrao
       where sq_unidade_fornec = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM lc_unidade_fornec where sq_unidade_fornec = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;