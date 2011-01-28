create or replace FUNCTION SP_PutCVIdioma
   (p_operacao              varchar,
    p_pessoa               numeric,
    p_chave                numeric,
    p_leitura              varchar,
    p_escrita              varchar,
    p_compreensao          varchar,
    p_conversacao          varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de idiomas do colaborador
      insert into cv_pessoa_idioma
        (sq_pessoa, sq_idioma, leitura,   escrita,   compreensao,   conversacao)
      values 
        (p_pessoa,  p_chave,   p_leitura, p_escrita, p_compreensao, p_conversacao);
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de idiomas do colaborador
      update cv_pessoa_idioma
         set leitura         = p_leitura,
             escrita         = p_escrita,
             compreensao     = p_compreensao,
             conversacao     = p_conversacao
       where sq_pessoa = p_pessoa
         and sq_idioma = p_chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de idiomas do colaborador
      DELETE FROM cv_pessoa_idioma
       where sq_pessoa = p_pessoa
         and sq_idioma = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;