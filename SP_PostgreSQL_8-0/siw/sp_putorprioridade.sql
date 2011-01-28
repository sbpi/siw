create or replace FUNCTION SP_PutOrPrioridade
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_cliente                   numeric,
    p_codigo                    varchar,
    p_nome                      varchar,
    p_responsavel               varchar,
    p_telefone                  varchar,
    p_email                     varchar,
    p_ordem                     numeric,
    p_ativo                     varchar,
    p_padrao                    varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into or_prioridade
             (sq_orprioridade,     cliente,            codigo,     nome,      responsavel, 
              telefone,            email,              ordem,      ativo,      padrao
             )
      (select sq_orprioridade.nextval, p_cliente,      p_codigo,   p_nome,     p_responsavel, 
              p_telefone,          p_email,            p_ordem,    p_ativo,    p_padrao
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update or_prioridade set 
         codigo                = p_codigo,
         nome                  = p_nome,
         responsavel           = p_responsavel,
         telefone              = p_telefone,
         email                 = p_email,
         ordem                 = p_ordem,
         ativo                 = p_ativo,
         padrao                = p_padrao
       where sq_orprioridade = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM or_prioridade where sq_orprioridade = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;