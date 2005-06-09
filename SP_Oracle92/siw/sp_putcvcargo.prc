create or replace procedure SP_PutCVCargo
   (p_operacao             in  varchar2,
    p_chave                in number    default null,
    p_sq_cvpesexp          in number,
    p_sq_area_conhecimento in number,
    p_especialidades       in varchar2,
    p_inicio               in date, 
    p_fim                  in date      default null
   ) is
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de cargos
      insert into cv_pessoa_cargo
        (sq_cvpescargo,         sq_cvpesexp,       sq_area_conhecimento,
         especialidades,        inicio,            fim)
      (select 
         sq_cvpescargo.nextval, p_sq_cvpesexp,     p_sq_area_conhecimento,
         p_especialidades,      p_inicio,          p_fim
       from dual);
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
      delete cv_pessoa_cargo
       where sq_cvpescargo = p_chave;
   End If;
end SP_PutCVCargo;
/

