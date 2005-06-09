create or replace procedure SP_GetEtapaOrder
   (p_chave     in  number,
    p_chave_pai in  number default null,
    p_result    out siw.sys_refcursor
   ) is
begin
   -- Recupera o número de ordem das outras opções irmãs à informada
   open p_result for
      select a.ordem, a.titulo
        from pj_projeto_etapa a
       where a.sq_siw_solicitacao = p_chave
          and ((p_chave_pai       is null and a.sq_etapa_pai is null) or
               (p_chave_pai       is not null and a.sq_etapa_pai = p_chave_pai))
      order by a.ordem;
end SP_GetEtapaOrder;
/

