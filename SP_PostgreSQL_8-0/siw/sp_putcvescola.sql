create or replace FUNCTION SP_PutCVEscola
   (p_operacao              varchar,
    p_pessoa               numeric,
    p_chave                numeric,
    p_sq_area_conhecimento numeric,
    p_sq_pais              numeric,
    p_sq_formacao          numeric,
    p_nome                 varchar,
    p_instituicao          varchar, 
    p_inicio               varchar, 
    p_fim                  varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de formação acadêmica
      insert into cv_pessoa_escol
        (sq_cvpessoa_escol,          sq_pessoa,      sq_area_conhecimento, 
         sq_pais,                    sq_formacao,    nome, 
         instituicao,                inicio,         fim)
      (select 
         nextVal('sq_cvpessoa_escol'),  p_pessoa,       p_sq_area_conhecimento, 
         p_sq_pais,                  p_sq_formacao,  p_nome, 
         p_instituicao,              p_inicio,       p_fim
      );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de formação acadêmica
      update cv_pessoa_escol
         set sq_area_conhecimento = p_sq_area_conhecimento,
             sq_pais              = p_sq_pais,
             sq_formacao          = p_sq_formacao,
             nome                 = p_nome,
             instituicao          = p_instituicao,
             inicio               = p_inicio,
             fim                  = p_fim
       where sq_cvpessoa_escol = p_chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de formação acadêmica
      DELETE FROM cv_pessoa_escol
       where sq_cvpessoa_escol = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;