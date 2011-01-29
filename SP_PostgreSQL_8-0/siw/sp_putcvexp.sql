create or replace FUNCTION SP_PutCVExp
   (p_operacao              varchar,
    p_pessoa               numeric,
    p_chave                numeric,
    p_sq_area_conhecimento numeric,
    p_sq_cidade            numeric,
    p_sq_eo_tipo_posto     numeric,
    p_sq_tipo_vinculo      numeric, 
    p_empregador           varchar,
    p_entrada              date, 
    p_saida                date, 
    p_duracao_mes          numeric, 
    p_duracao_ano          numeric,    
    p_motivo_saida         varchar,
    p_ultimo_salario       numeric,
    p_atividades           varchar  
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de experiência profissional
      insert into cv_pessoa_exp
        (sq_cvpesexp,         sq_pessoa,         sq_area_conhecimento,   sq_cidade,
         sq_eo_tipo_posto,    sq_tipo_vinculo,   empregador,             entrada,
         saida,               duracao_mes,       duracao_ano,            motivo_saida,
         ultimo_salario,      atividades)
      (select 
         nextVal('sq_cvpesexp'), p_pessoa,          p_sq_area_conhecimento, p_sq_cidade,
         p_sq_eo_tipo_posto,  p_sq_tipo_vinculo, p_empregador,           p_entrada,
         p_saida,             p_duracao_mes,     p_duracao_ano,          p_motivo_saida,
         p_ultimo_salario,    p_atividades
      );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de experiência profissional
      update cv_pessoa_exp
         set sq_area_conhecimento = p_sq_area_conhecimento,
             sq_cidade            = p_sq_cidade,
             sq_eo_tipo_posto     = p_sq_eo_tipo_posto,
             sq_tipo_vinculo      = p_sq_tipo_vinculo,                          
             empregador           = p_empregador,
             entrada              = p_entrada,
             saida                = p_saida,
             duracao_mes          = p_duracao_mes,
             duracao_ano          = p_duracao_ano,
             motivo_saida         = p_motivo_saida,
             ultimo_salario       = p_ultimo_salario,
             atividades           = p_atividades
       where sq_cvpesexp = p_chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de experiência profissional
      DELETE FROM cv_pessoa_exp
       where sq_cvpesexp = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;