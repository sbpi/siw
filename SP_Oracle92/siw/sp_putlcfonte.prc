create or replace procedure SP_PutLcFonte
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  number    default null,
    p_nome                     in  varchar2  default null,
    p_descricao                in  varchar2  default null,
    p_ativo                    in  varchar2  default null,
    p_padrao                   in  varchar2  default null,
    p_orcamentario             in  varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_fonte_recurso
             (sq_lcfonte_recurso,         cliente,   nome,   descricao,   orcamentario,   ativo,   padrao
             )
      (select sq_lcfonte_recurso.nextval, p_cliente, p_nome, p_descricao, p_orcamentario, p_ativo, p_padrao
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update lc_fonte_recurso set 
         nome                  = p_nome,
         descricao             = p_descricao,
         orcamentario          = p_orcamentario,
         ativo                 = p_ativo,
         padrao                = p_padrao
       where sq_lcfonte_recurso = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete lc_fonte_recurso where sq_lcfonte_recurso = p_chave;
   End If;
end SP_PutLcFonte;
/

