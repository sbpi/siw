create or replace procedure SP_PutCVExp
   (p_operacao             in  varchar2,
    p_pessoa               in number,
    p_chave                in number    default null,
    p_sq_area_conhecimento in number,
    p_sq_cidade            in number,
    p_sq_eo_tipo_posto     in number    default null,
    p_sq_tipo_vinculo      in number    default null, 
    p_empregador           in varchar2,
    p_entrada              in date, 
    p_saida                in date      default null, 
    p_duracao_mes          in number    default null, 
    p_duracao_ano          in number    default null,    
    p_motivo_saida         in varchar2  default null,
    p_ultimo_salario       in number    default null,
    p_atividades           in varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de experiência profissional
      insert into cv_pessoa_exp
        (sq_cvpesexp,         sq_pessoa,         sq_area_conhecimento,   sq_cidade,
         sq_eo_tipo_posto,    sq_tipo_vinculo,   empregador,             entrada,
         saida,               duracao_mes,       duracao_ano,            motivo_saida,
         ultimo_salario,      atividades)
      (select 
         sq_cvpesexp.nextval, p_pessoa,          p_sq_area_conhecimento, p_sq_cidade,
         p_sq_eo_tipo_posto,  p_sq_tipo_vinculo, p_empregador,           p_entrada,
         p_saida,             p_duracao_mes,     p_duracao_ano,          p_motivo_saida,
         p_ultimo_salario,    p_atividades
       from dual);
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
      delete cv_pessoa_exp
       where sq_cvpesexp = p_chave;
   End If;
end SP_PutCVExp;
/
