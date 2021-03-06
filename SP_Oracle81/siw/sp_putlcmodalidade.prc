create or replace procedure SP_PutLcModalidade
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  number    default null,
    p_nome                     in  varchar2  default null,
    p_sigla                    in  varchar2  default null,
    p_descricao                in  varchar2  default null,
    p_fundamentacao            in  varchar2  default null,
    p_ativo                    in  varchar2  default null,
    p_padrao                   in  varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_modalidade
             (sq_lcmodalidade,         cliente,   nome,   sigla,   descricao,   fundamentacao, ativo,   padrao
             )
      (select sq_lcmodalidade.nextval, p_cliente, p_nome, p_sigla, p_descricao, p_fundamentacao, p_ativo, p_padrao
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update lc_modalidade set 
         nome                  = p_nome,
         sigla                 = p_sigla,
         descricao             = p_descricao,
         fundamentacao         = p_fundamentacao,
         ativo                 = p_ativo,
         padrao                = p_padrao
       where sq_lcmodalidade = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete lc_modalidade where sq_lcmodalidade = p_chave;
   End If;
end SP_PutLcModalidade;
/

