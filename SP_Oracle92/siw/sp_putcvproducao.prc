create or replace procedure SP_PutCVProducao
   (p_operacao             in  varchar2,
    p_pessoa               in number,
    p_chave                in number    default null,
    p_sq_area_conhecimento in number,
    p_sq_formacao          in number,
    p_nome                 in varchar2,
    p_meio                 in varchar2,
    p_data                 in varchar2
   ) is
begin
   If p_operacao = 'I' Then -- Inclus�o
      -- Insere registro na tabela de produ��o t�cnica
      insert into cv_pessoa_prod
        (sq_cvpessoa_prod,          sq_pessoa,        sq_area_conhecimento,   sq_formacao,
         nome,                      meio,             data)
      (select 
         sq_cvpessoa_prod.nextval,  p_pessoa,         p_sq_area_conhecimento, p_sq_formacao,
         p_nome,                    p_meio,           p_data
       from dual);
   Elsif p_operacao = 'A' Then -- Altera��o
      -- Atualiza a tabela de produ��o t�cnica
      update cv_pessoa_prod
         set sq_area_conhecimento = p_sq_area_conhecimento,
             sq_formacao          = p_sq_formacao,
             nome                 = p_nome,
             meio                 = p_meio,
             data                 = p_data
       where sq_cvpessoa_prod = p_chave;
   Elsif p_operacao = 'E' Then -- Exclus�o
      -- Remove o registro na tabela de produ��o t�cnica
      delete cv_pessoa_prod
       where sq_cvpessoa_prod = p_chave;
   End If;
end SP_PutCVProducao;
/

