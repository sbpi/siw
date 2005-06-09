create or replace procedure SP_GetBankList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera os bancos existentes
   open p_result for
      select sq_banco, codigo, nome, ativo, codigo||' - '||nome descricao, padrao
        from co_banco
      order by padrao desc, codigo;
end SP_GetBankList;
/

