create or replace procedure SP_GetVincKindData
   (p_sq_tipo_vinculo   in  number,
    p_result            out siw.sys_refcursor) is
begin
   -- Recupera os dados do tipo de vinculo
   open p_result for
      select nome, sq_tipo_pessoa, interno, contratado, ativo, padrao
      from co_tipo_vinculo
      where sq_tipo_vinculo = p_sq_tipo_vinculo;
end SP_GetVincKindData;
/

