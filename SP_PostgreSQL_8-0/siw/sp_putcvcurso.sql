create or replace FUNCTION SP_PutCVCurso
   (p_operacao              varchar,
    p_pessoa               numeric,
    p_chave                numeric,
    p_sq_area_conhecimento numeric,
    p_sq_formacao          numeric,
    p_nome                 varchar,
    p_instituicao          varchar, 
    p_carga_horaria        numeric, 
    p_conclusao            varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de extensão acadêmica
      insert into cv_pessoa_curso
        (sq_cvpescurtec,             sq_pessoa,      sq_area_conhecimento, 
         sq_formacao,                nome,           instituicao,
         carga_horaria,              conclusao)
      (select 
         sq_cvpescurso.nextval,      p_pessoa,       p_sq_area_conhecimento, 
         p_sq_formacao,              p_nome,         p_instituicao,
         p_carga_horaria,            p_conclusao
       from dual);
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de extensão acadêmica
      update cv_pessoa_curso
         set sq_area_conhecimento = p_sq_area_conhecimento,
             sq_formacao          = p_sq_formacao,
             nome                 = p_nome,
             instituicao          = p_instituicao,
             carga_horaria        = p_carga_horaria,
             conclusao            = p_conclusao
       where sq_cvpescurtec = p_chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de extensão acadêmica
      DELETE FROM cv_pessoa_curso
       where sq_cvpescurtec = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;