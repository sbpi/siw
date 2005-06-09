create or replace procedure SP_GetSPSP
   (p_chave     in  number  default null,
    p_chave_aux in  number  default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os vínculos de uma sp
   open p_result for
   select a.sq_stored_proc chave_pai, a.nome nm_pai, a.descricao ds_pai,
          b.nome nm_sistema_pai, b.descricao ds_sistema_pai, b.sq_sistema sq_sistema_pai,
          c.nome nm_usuario_pai, c.descricao ds_usuario_pai,
          d.nome nm_tipo_pai, d.descricao ds_tipo_pai, e.sp_pai,
          f.sq_stored_proc chave_filha, f.nome nm_filha, f.descricao ds_filha,
          g.nome nm_sistema_filha, g.descricao ds_sistema_filha,
          h.nome nm_usuario_filha, h.descricao ds_usuario_filha,
          i.nome nm_tipo_filha, i.descricao ds_tipo_filha,
          'PAI' tipo
     from dc_stored_proc                       a,
          dc_sistema       b,
          dc_usuario       c,
          dc_sp_tipo       d,
          dc_sp_sp         e,
          dc_stored_proc   f,
          dc_sistema       g,
          dc_usuario       h,
          dc_sp_tipo       i
    where (a.sq_sistema     = b.sq_sistema)
      and (a.sq_usuario     = c.sq_usuario)
      and (a.sq_sp_tipo     = d.sq_sp_tipo)
      and (a.sq_stored_proc = e.sp_pai)
      and (e.sp_filha       = f.sq_stored_proc)
      and (f.sq_sistema     = g.sq_sistema)
      and (f.sq_usuario     = h.sq_usuario)
      and (f.sq_sp_tipo     = i.sq_sp_tipo)
      and ((p_chave      is null) or (p_chave      is not null and a.sq_stored_proc = p_chave))
      and ((p_chave_aux  is null) or (p_chave_aux  is not null and f.sq_stored_proc = p_chave_aux))
   UNION
   select a.sq_stored_proc chave_pai, a.nome nm_pai, a.descricao ds_pai,
          b.nome nm_sistema_pai, b.descricao ds_sistema_pai, b.sq_sistema sq_sistema_pai,
          c.nome nm_usuario_pai, c.descricao ds_usuario_pai,
          d.nome nm_tipo_pai, d.descricao ds_tipo_pai, e.sp_filha,
          f.sq_stored_proc chave_filha, f.nome nm_filha, f.descricao ds_filha,
          g.nome nm_sistema_filha, g.descricao ds_sistema_filha,
          h.nome nm_usuario_filha, h.descricao ds_usuario_filha,
          i.nome nm_tipo_filha, i.descricao ds_tipo_filha,
          'FILHA' tipo
     from dc_stored_proc                       a,
          dc_sistema       b,
          dc_usuario       c,
          dc_sp_tipo       d,
          dc_sp_sp         e,
          dc_stored_proc   f,
          dc_sistema       g,
          dc_usuario       h,
          dc_sp_tipo       i
    where (a.sq_sistema     = b.sq_sistema)
      and (a.sq_usuario     = c.sq_usuario)
      and (a.sq_sp_tipo     = d.sq_sp_tipo)
      and (a.sq_stored_proc = e.sp_filha)
      and (e.sp_pai         = f.sq_stored_proc)
      and (f.sq_sistema     = g.sq_sistema)
      and (f.sq_usuario     = h.sq_usuario)
      and (f.sq_sp_tipo     = i.sq_sp_tipo)
      and ((p_chave      is null) or (p_chave      is not null and a.sq_stored_proc = p_chave))
      and ((p_chave_aux  is null) or (p_chave_aux  is not null and f.sq_stored_proc = p_chave_aux));

end SP_GetSPSP;
/

