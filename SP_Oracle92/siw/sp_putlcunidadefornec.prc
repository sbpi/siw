create or replace procedure SP_PutLcUnidadeFornec
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  number    default null,
    p_sigla                    in  varchar2  default null,
    p_nome                     in  varchar2  default null,
    p_descricao                in  varchar2  default null,
    p_ativo                    in  varchar2  default null,
    p_padrao                   in  varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_unidade_fornec
             (sq_unidade_fornec,            cliente,   sigla, nome,   descricao,    ativo,   padrao
             )
      (select sq_unidade_fornec.nextval, p_cliente, p_sigla, p_nome, p_descricao,p_ativo, p_padrao
         from dual
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
      delete lc_unidade_fornec where sq_unidade_fornec = p_chave;
   End If;
end SP_PutLcUnidadeFornec;
/

