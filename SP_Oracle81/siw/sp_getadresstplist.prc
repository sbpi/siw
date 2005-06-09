create or replace procedure SP_GetAdressTPList
   (p_result    out siw.sys_refcursor) is
begin
   -- Recupera as defici�ncias existentes
   open p_result for
      select a.sq_tipo_endereco, a.nome, a.padrao,
             decode(a.padrao,'S','Sim','N�o') padraodesc,
             decode(a.email,'S','Sim','N�o') email,
             decode(a.internet,'S','Sim','N�o') internet,
             a.ativo,
             decode(a.ativo,'S','Sim','N�o') ativodesc, b.nome sq_tipo_pessoa
        from co_tipo_endereco a, co_tipo_pessoa b
      where a.sq_tipo_pessoa = b.sq_tipo_pessoa;
end SP_GetAdressTPList;
/

