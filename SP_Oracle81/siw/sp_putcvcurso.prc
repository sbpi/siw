create or replace procedure SP_PutCVCurso
   (p_operacao             in  varchar2,
    p_pessoa               in number,
    p_chave                in number    default null,
    p_sq_area_conhecimento in number,
    p_sq_formacao          in number,
    p_nome                 in varchar2,
    p_instituicao          in varchar2, 
    p_carga_horaria        in number, 
    p_conclusao            in varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then -- Inclus�o
      -- Insere registro na tabela de extens�o acad�mica
      insert into cv_pessoa_curso
        (sq_cvpescurtec,             sq_pessoa,      sq_area_conhecimento, 
         sq_formacao,                nome,           instituicao,
         carga_horaria,              conclusao)
      (select 
         sq_cvpescurso.nextval,      p_pessoa,       p_sq_area_conhecimento, 
         p_sq_formacao,              p_nome,         p_instituicao,
         p_carga_horaria,            p_conclusao
       from dual);
   Elsif p_operacao = 'A' Then -- Altera��o
      -- Atualiza a tabela de extens�o acad�mica
      update cv_pessoa_curso
         set sq_area_conhecimento = p_sq_area_conhecimento,
             sq_formacao          = p_sq_formacao,
             nome                 = p_nome,
             instituicao          = p_instituicao,
             carga_horaria        = p_carga_horaria,
             conclusao            = p_conclusao
       where sq_cvpescurtec = p_chave;
   Elsif p_operacao = 'E' Then -- Exclus�o
      -- Remove o registro na tabela de extens�o acad�mica
      delete cv_pessoa_curso
       where sq_cvpescurtec = p_chave;
   End If;
end SP_PutCVCurso;
/

