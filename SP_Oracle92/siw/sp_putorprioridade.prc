create or replace procedure SP_PutOrPrioridade
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  number    default null,
    p_codigo                   in  varchar2  default null,
    p_nome                     in  varchar2  default null,
    p_responsavel              in  varchar2  default null,
    p_telefone                 in  varchar2  default null,
    p_email                    in  varchar2  default null,
    p_ordem                    in  number    default null,
    p_ativo                    in  varchar2  default null,
    p_padrao                   in  varchar2  default null
   ) is
begin
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
      delete or_prioridade where sq_orprioridade = p_chave;
   End If;
end SP_PutOrPrioridade;
/

