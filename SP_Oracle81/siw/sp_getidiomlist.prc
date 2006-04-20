create or replace procedure SP_GetIdiomList
   (p_nome      in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os bancos existentes
   open p_result for
      select sq_idioma, nome, padrao, ativo,
             decode(padrao,'S','Sim','Não') padraodesc,
             decode(ativo,'S','Sim','Não') ativodesc
       from co_idioma
       where (p_nome  is null or (p_nome  is not null and acentos(nome) like '%'||acentos(p_nome)||'%'))
         and (p_ativo is null or (p_ativo is not null and ativo = p_ativo));
end SP_GetIdiomList;
/
