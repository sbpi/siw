create or replace procedure SP_PutLcUnidade
   (p_operacao                 in  varchar2,
    p_chave                    in  number,
    p_cnpj                     in  varchar2  default null,
    p_licita                   in  varchar2  default null,
    p_contrata                 in  varchar2  default null,
    p_ativo                    in  varchar2  default null,
    p_padrao                   in  varchar2  default null
   ) is
begin
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
      delete lc_unidade where sq_unidade = p_chave;
   End If;
end SP_PutLcUnidade;
/

