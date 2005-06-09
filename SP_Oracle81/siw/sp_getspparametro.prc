create or replace procedure SP_GetSPParametro
   (p_chave          in  number  default null,
    p_chave_aux      in  number  default null,
    p_sq_dado_tipo   in  number  default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os tipos de Tabela
   open p_result for
   select a.nome nm_sp, a.descricao ds_sp, a.sq_stored_proc chave,
          b.nome nm_sistema, b.descricao ds_sistema, b.sq_sistema,
          c.nome nm_usuario, c.descricao ds_usuario, c.sq_usuario,
          d.nome nm_tipo, d.descricao ds_tipo,
          e.nome nm_sp_param, e.descricao ds_sp_param, e.tipo tp_sp_param,
          e.ordem ord_sp_param, e.sq_sp_param, e.sq_sp_param chave_aux,
          decode(e.tipo,'E','IN','S','OUT','BOTH') nm_tipo_param,
          f.nome nm_dado_tipo, f.descricao ds_dado_tipo, f.sq_dado_tipo
     from dc_stored_proc                 a,
          dc_sistema   b,
          dc_usuario   c,
          dc_sp_tipo   d,
          dc_sp_param  e,
          dc_dado_tipo f 
   where (a.sq_sistema     = b.sq_sistema)
     and (a.sq_usuario     = c.sq_usuario)
     and (a.sq_sp_tipo     = d.sq_sp_tipo)
     and (a.sq_stored_proc = e.sq_stored_proc)
     and (f.sq_dado_tipo   = e.sq_dado_tipo)
     and ((p_chave        is null) or (p_chave        is not null and e.sq_stored_proc = p_chave))
     and ((p_chave_aux    is null) or (p_chave_aux    is not null and e.sq_sp_param    = p_chave_aux))
     and ((p_sq_dado_tipo is null) or (p_sq_dado_tipo is not null and e.sq_dado_tipo   = p_sq_dado_tipo));
end SP_GetSPParametro;
/

