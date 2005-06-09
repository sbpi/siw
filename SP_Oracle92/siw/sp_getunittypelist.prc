create or replace procedure SP_GetUnitTypeList
   (p_sq_pessoa   in  number,
    p_result      out sys_refcursor
   ) is
begin
   --Recupera a lista dos tipos de unidade
   open p_result for
      select sq_tipo_unidade, nome, ativo
        from eo_tipo_unidade
       where sq_pessoa = p_sq_pessoa;
end SP_GetUnitTypeList;
/

