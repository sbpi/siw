create or replace procedure SP_PutCVIdioma
   (p_operacao             in  varchar2,
    p_pessoa               in number,
    p_chave                in number    default null,
    p_leitura              in varchar2,
    p_escrita              in varchar2,
    p_compreensao          in varchar2,
    p_conversacao          in varchar2
   ) is
begin
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
      delete cv_pessoa_idioma
       where sq_pessoa = p_pessoa
         and sq_idioma = p_chave;
   End If;
end SP_PutCVIdioma;
/

