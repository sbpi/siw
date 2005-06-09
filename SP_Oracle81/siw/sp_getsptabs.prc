create or replace procedure SP_GetSPTabs
   (p_chave     in number default null,
    p_chave_aux in number default null,
    p_result    out siw.sys_refcursor
   ) is
begin
  -- Recupera os recursos alocados a uma etapa do projeto
  open p_result for
     select a.sq_stored_proc, a.sq_tabela,
            b.nome nm_tabela, b.descricao ds_tabela,
            c.nome, c.descricao, c.sq_stored_proc chave,
            d.sq_sistema, d.nome nm_sistema,
            e.nome nm_usuario,
            f.nome nm_usuario_tabela,
            g.nome nm_sp_tipo
       from dc_stored_proc                 c,
            dc_sp_tipo g,
            dc_sp_tabs a,
            dc_tabela  b,
            dc_usuario f,
            dc_sistema d,
            dc_usuario e 
      where (c.sq_sp_tipo     = g.sq_sp_tipo)
        and (c.sq_stored_proc = a.sq_stored_proc (+))
        and (a.sq_tabela      = b.sq_tabela (+))
        and (b.sq_usuario     = f.sq_usuario (+))
        and (c.sq_sistema     = d.sq_sistema)
        and (c.sq_usuario     = e.sq_usuario)
        and ((p_chave     is null) or (p_chave     is not null and a.sq_stored_proc = p_chave))
        and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_tabela      = p_chave_aux));
End SP_GetSPTabs;
/

