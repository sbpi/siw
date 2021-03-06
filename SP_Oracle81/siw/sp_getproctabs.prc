create or replace procedure SP_GetProcTabs
   (p_chave     in number default null,
    p_chave_aux in number default null,
    p_result    out siw.sys_refcursor
   ) is
begin
  -- Recupera os recursos alocados a uma etapa do projeto
  open p_result for
     select a.sq_procedure chave, a.sq_tabela chaveAux,
            b.nome nm_procedure,
            c.nome nm_tabela, c.descricao ds_tabela
     from     dc_proc_tabela   a,
       dc_procedure b,
       dc_tabela    c
      where (a.sq_procedure   = b.sq_procedure)
        and (a.sq_tabela = c.sq_tabela)
        and ((p_chave     is null) or (p_chave     is not null and a.sq_procedure = p_chave))
        and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_tabela   = p_chave_aux));
End SP_GetProcTabs;
/

