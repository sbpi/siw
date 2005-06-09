create or replace procedure SP_GetVincKindList
   (p_cliente   in  number,
    p_result    out sys_refcursor) is
begin
   -- Recupera os tipos de vinculos existentes
   open p_result for 
      select a.sq_tipo_vinculo, a.nome, a.padrao,
             a.interno, a.contratado, 
             a.ativo, b.nome sq_tipo_pessoa
        from co_tipo_vinculo a, 
             co_tipo_pessoa  b
       where a.sq_tipo_pessoa = b.sq_tipo_pessoa
         and a.cliente        = p_cliente
     order by a.interno desc, b.nome, a.ordem;
end SP_GetVincKindList;
/

