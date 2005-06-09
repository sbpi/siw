create or replace procedure SP_PutCVEscola
   (p_operacao             in  varchar2,
    p_pessoa               in number,
    p_chave                in number    default null,
    p_sq_area_conhecimento in number    default null,
    p_sq_pais              in number,
    p_sq_formacao          in number,
    p_nome                 in varchar2  default null,
    p_instituicao          in varchar2, 
    p_inicio               in varchar2, 
    p_fim                  in varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de formação acadêmica
      insert into cv_pessoa_escol
        (sq_cvpessoa_escol,          sq_pessoa,      sq_area_conhecimento, 
         sq_pais,                    sq_formacao,    nome, 
         instituicao,                inicio,         fim)
      (select 
         sq_cvpessoa_escol.nextval,  p_pessoa,       p_sq_area_conhecimento, 
         p_sq_pais,                  p_sq_formacao,  p_nome, 
         p_instituicao,              p_inicio,       p_fim
       from dual);
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
      delete cv_pessoa_escol
       where sq_cvpessoa_escol = p_chave;
   End If;
end SP_PutCVEscola;
/

