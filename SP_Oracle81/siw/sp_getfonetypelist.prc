create or replace procedure SP_GetFoneTypeList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera o tipos de telefones existentes
   open p_result for
      select a.sq_tipo_telefone, a.nome, a.padrao,
             decode(a.padrao,'S','Sim','Não') padraodesc,
             a.ativo,
             decode(a.ativo,'S','Sim','Não') ativodesc, b.nome sq_tipo_pessoa
        from co_tipo_telefone a, co_tipo_pessoa b
      where a.sq_tipo_pessoa = b.sq_tipo_pessoa;
end SP_GetFoneTypeList;
/

