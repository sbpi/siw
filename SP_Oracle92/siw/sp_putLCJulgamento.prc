create or replace procedure sp_putLCJulgamento
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  number    default null,
    p_nome                     in  varchar2  default null,
    p_descricao                in  varchar2  default null,
    p_ativo                    in  varchar2  default null,
    p_padrao                   in  varchar2  default null,
    p_item                     in  varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_julgamento
        (sq_lcjulgamento, cliente, nome, descricao, item, ativo, padrao)
      (select sq_lcjulgamento.nextval, p_cliente, p_nome, p_descricao, p_item, 
              p_ativo, p_padrao
         from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update lc_julgamento set 
         nome                  = p_nome,
         descricao             = p_descricao,
         item                  = p_item,
         ativo                 = p_ativo,
         padrao                = p_padrao
       where sq_lcJulgamento = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete lc_julgamento where sq_lcJulgamento = p_chave;
   End If;
end sp_putLCJulgamento;
/
