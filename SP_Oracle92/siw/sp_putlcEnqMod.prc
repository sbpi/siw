create or replace procedure SP_PutLcModEnq
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_chave_aux                in  number    default null,
    p_sigla                    in  varchar2  default null,
    p_descricao                in  varchar2  default null,
    p_ativo                    in  varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_modalidade_artigo
             (sq_modalidade_artigo,         sq_lcmodalidade, sigla,   descricao,   ativo
             )
      (select sq_modalidade_artigo.nextval, p_chave,         p_sigla, p_descricao, p_ativo
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update lc_modalidade_artigo set 
         sigla                 = p_sigla,
         descricao             = p_descricao,
         ativo                 = p_ativo
       where sq_modalidade_artigo = p_chave_aux;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete lc_modalidade_artigo where sq_modalidade_artigo = p_chave_aux;
   End If;
end SP_PutLcModEnq;
/
