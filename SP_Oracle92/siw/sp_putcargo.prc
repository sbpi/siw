create or replace procedure SP_PutCargo
   (p_operacao          in  varchar2,
    p_chave             in  number    default null,
    p_cliente           in  number,
    p_sq_tipo           in  number,
    p_sq_formacao       in  number,
    p_nome              in  varchar2,
    p_descricao         in  varchar2 default null,
    p_atividades        in  varchar2 default null,
    p_competencias      in  varchar2 default null,
    p_salario_piso      in  number default null,
    p_salario_teto      in  number default null,
    p_area_conhecimento in  number default null,    
    p_ativo             in  varchar2
   ) is
   
begin
   -- Grava uma modalidade de contratação
   If p_operacao = 'I' Then
      insert into eo_posto_trabalho
        (sq_posto_trabalho, cliente, sq_eo_tipo_posto, sq_formacao, nome, descricao, atividades, competencias, salario_piso, salario_teto, sq_area_conhecimento, ativo)
      values
        (sq_posto_trabalho.nextVal, p_cliente, p_sq_tipo, p_sq_formacao, trim(p_nome), p_descricao, p_atividades, p_competencias, p_salario_piso, p_salario_teto, p_area_conhecimento, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_posto_trabalho
         set cliente              = p_cliente,
             sq_eo_tipo_posto     = p_sq_tipo,
             sq_formacao          = p_sq_formacao,
             nome                 = trim(p_nome),
             descricao            = p_descricao,
             atividades           = p_atividades,
             competencias         = p_competencias,
             salario_piso         = p_salario_piso,
             salario_teto         = p_salario_teto,
             sq_area_conhecimento = p_area_conhecimento,
             ativo                = p_ativo
       where sq_posto_trabalho    = p_chave;
   Elsif p_operacao = 'E' Then
      delete eo_posto_trabalho
       where sq_posto_trabalho = p_chave;
   End If;
end SP_PutCargo;
/
