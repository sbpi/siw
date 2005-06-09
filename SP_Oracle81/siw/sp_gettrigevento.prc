create or replace procedure SP_GetTrigEvento
   (p_chave     in number,
    p_chave_aux in number   default null,
    p_result    out siw.sys_refcursor
   ) is
begin
  -- Recupera os recursos alocados a uma etapa do projeto
  open p_result for
     select a.sq_trigger, a.sq_tabela, a.sq_usuario, a.sq_sistema, a.nome, a.descricao,
            b.sq_evento, b.nome nm_evento, b.descricao ds_evento,
            c.sq_trigger existe,
            d.nome nm_tabela,
            e.nome nm_sistema,
            f.nome nm_usuario
       from dc_trigger a,
            dc_tabela d,
            dc_sistema e,
            dc_usuario f,
            dc_evento  b,
            dc_trigger_evento c 
      where (a.sq_tabela = d.sq_tabela)
        and (a.sq_sistema = e.sq_sistema)
        and (a.sq_usuario = f.sq_usuario)
        and (b.sq_evento  = c.sq_evento (+) and 
             c.sq_trigger  (+) = p_chave)
        and a.sq_trigger = p_chave;
End SP_GetTrigEvento;
/

