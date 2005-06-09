create or replace procedure SP_PutLcFinalidade
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  number    default null,
    p_nome                     in  varchar2  default null,
    p_descricao                in  varchar2  default null,
    p_ativo                    in  varchar2  default null,
    p_padrao                   in  varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_finalidade
             (sq_lcfinalidade,         cliente,   nome,   descricao,   ativo,   padrao
             )
      (select sq_lcfinalidade.nextval, p_cliente, p_nome, p_descricao, p_ativo, p_padrao
         from dual
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
      delete lc_finalidade where sq_lcfinalidade = p_chave;
   End If;
end SP_PutLcFinalidade;
/

