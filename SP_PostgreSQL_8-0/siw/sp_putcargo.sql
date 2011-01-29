create or replace FUNCTION SP_PutCargo
   (p_operacao           varchar,
    p_chave              numeric,
    p_cliente            numeric,
    p_sq_tipo            numeric,
    p_sq_formacao        numeric,
    p_nome               varchar,
    p_descricao          varchar,
    p_atividades         varchar,
    p_competencias       varchar,
    p_salario_piso       numeric,
    p_salario_teto       numeric,
    p_area_conhecimento  numeric,    
    p_ativo              varchar
   ) RETURNS VOID AS $$
DECLARE
   
BEGIN
   -- Grava uma modalidade de contratação
   If p_operacao = 'I' Then
      insert into eo_posto_trabalho
        (sq_posto_trabalho, cliente, sq_eo_tipo_posto, sq_formacao, nome, descricao, atividades, competencias, salario_piso, salario_teto, sq_area_conhecimento, ativo)
      values
        (nextVal('sq_posto_trabalho'), p_cliente, p_sq_tipo, p_sq_formacao, trim(p_nome), p_descricao, p_atividades, p_competencias, p_salario_piso, p_salario_teto, p_area_conhecimento, p_ativo);
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
      DELETE FROM eo_posto_trabalho
       where sq_posto_trabalho = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;