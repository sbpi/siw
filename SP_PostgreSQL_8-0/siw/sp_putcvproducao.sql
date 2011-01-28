create or replace FUNCTION SP_PutCVProducao
   (p_operacao              varchar,
    p_pessoa               numeric,
    p_chave                numeric,
    p_sq_area_conhecimento numeric,
    p_sq_formacao          numeric,
    p_nome                 varchar,
    p_meio                 varchar,
    p_data                 varchar
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de produção técnica
      insert into cv_pessoa_prod
        (sq_cvpessoa_prod,          sq_pessoa,        sq_area_conhecimento,   sq_formacao,
         nome,                      meio,             data)
      (select 
         sq_cvpessoa_prod.nextval,  p_pessoa,         p_sq_area_conhecimento, p_sq_formacao,
         p_nome,                    p_meio,           p_data
       from dual);
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de produção técnica
      update cv_pessoa_prod
         set sq_area_conhecimento = p_sq_area_conhecimento,
             sq_formacao          = p_sq_formacao,
             nome                 = p_nome,
             meio                 = p_meio,
             data                 = p_data
       where sq_cvpessoa_prod = p_chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de produção técnica
      DELETE FROM cv_pessoa_prod
       where sq_cvpessoa_prod = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;