create or replace procedure SP_GetStateList
   (p_pais      in number   default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os estados existentes
   open p_result for
      select a.co_uf, b.nome nome_pais, a.sq_pais sq_pais, c.nome nome_regiao,
             a.sq_regiao sq_regiao,
             a.nome, Nvl(a.codigo_ibge,'-') codigo_ibge,
             a.ativo ativo,
             decode(a.ativo,'S','Sim','Não') ativodesc,
             a.padrao,
             decode(a.padrao,'S','Sim','Não') padraodesc
        from co_uf a, co_pais b, co_regiao c
       where a.sq_pais     = b.sq_pais
         and a.sq_regiao   = c.sq_regiao
         and b.sq_pais     = p_pais;
end SP_GetStateList;
/

