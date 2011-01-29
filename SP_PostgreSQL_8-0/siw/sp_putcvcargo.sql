create or replace FUNCTION SP_PutCVCargo
   (p_operacao              varchar,
    p_chave                numeric,
    p_sq_cvpesexp          numeric,
    p_sq_area_conhecimento numeric,
    p_especialidades       varchar,
    p_inicio               date, 
    p_fim                  date      
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de cargos
      insert into cv_pessoa_cargo
        (sq_cvpescargo,         sq_cvpesexp,       sq_area_conhecimento,
         especialidades,        inicio,            fim)
      (select 
         nextVal('sq_cvpescargo'), p_sq_cvpesexp,     p_sq_area_conhecimento,
         p_especialidades,      p_inicio,          p_fim
      );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de cargos
      update cv_pessoa_cargo
         set sq_area_conhecimento = p_sq_area_conhecimento,
             especialidades       = p_especialidades,
             inicio               = p_inicio,
             fim                  = p_fim
       where sq_cvpescargo = p_chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de cargos
      DELETE FROM cv_pessoa_cargo
       where sq_cvpescargo = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;