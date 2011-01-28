create or replace FUNCTION SP_PutLcModEnq
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_chave_aux                 numeric,
    p_sigla                     varchar,
    p_descricao                 varchar,
    p_ativo                     varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
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
      DELETE FROM lc_modalidade_artigo where sq_modalidade_artigo = p_chave_aux;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;