create or replace FUNCTION SP_GetStoredProcedure
   (p_cliente     numeric,
    p_chave      numeric,
    p_chave_aux  numeric,
    p_sq_sp_tipo numeric,
    p_sq_usuario numeric,
    p_sq_sistema numeric,
    p_nome       varchar,
    p_restricao  varchar,
    p_result     REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os dados da stored procedure informada
      open p_result for 
      select a.sq_stored_proc chave, a.sq_sp_tipo, a.sq_usuario, a.sq_sistema, a.nome nm_sp, a.descricao ds_sp,
             b.nome nm_sp_tipo,
             c.nome nm_usuario, c.descricao ds_usuario,
             d.sigla sg_sistema, d.nome nm_sistema
        from dc_stored_proc         a
             inner join dc_sp_tipo  b on (a.sq_sp_tipo     = b.sq_sp_tipo)
             inner join dc_usuario  c on (a.sq_usuario     = c.sq_usuario)
             inner join dc_sistema  d on (a.sq_sistema     = d.sq_sistema)
       where d.cliente = p_cliente
         and ((p_chave      is null) or (p_chave      is not null and a.sq_stored_proc = p_chave))
         and ((p_sq_sp_tipo is null) or (p_sq_sp_tipo is not null and a.sq_sp_tipo     = p_sq_sp_tipo))
         and ((p_sq_usuario is null) or (p_sq_usuario is not null and a.sq_usuario     = p_sq_usuario))
         and ((p_sq_sistema is null) or (p_sq_sistema is not null and a.sq_sistema     = p_sq_sistema))
         and ((p_nome       is null) or (p_nome       is not null and upper(a.nome) like '%'||upper(p_nome)||'%'));
   Elsif p_restricao = 'DCCDSP' Then
      -- Recupera os tipos de Tabela
      open p_result for 
      select a.sq_stored_proc chave, a.sq_sp_tipo, a.sq_usuario, a.sq_sistema, a.nome nm_sp, a.descricao ds_sp,
             b.nome nm_sp_tipo,
             c.nome nm_usuario,
             d.sigla sg_sistema
      from dc_stored_proc     a
        inner join dc_sp_tipo b on (a.sq_sp_tipo = b.sq_sp_tipo)
        inner join dc_usuario c on (a.sq_usuario = c.sq_usuario)
        inner join dc_sistema d on (a.sq_sistema = d.sq_sistema)
      where d.cliente = p_cliente
        and 0 = (select count(*) from dc_sp_sp where sp_filha = p_chave_aux and sp_pai = a.sq_stored_proc)
        and ((p_chave      is null) or (p_chave      is not null and a.sq_stored_proc = p_chave))
        and ((p_sq_sp_tipo is null) or (p_sq_sp_tipo is not null and a.sq_sp_tipo     = p_sq_sp_tipo))
        and ((p_sq_usuario is null) or (p_sq_usuario is not null and a.sq_usuario     = p_sq_usuario))
        and ((p_sq_sistema is null) or (p_sq_sistema is not null and a.sq_sistema     = p_sq_sistema))
        and ((p_nome       is null) or (p_nome       is not null and upper(a.nome) like '%'||upper(p_nome)||'%'));
   Elsif p_restricao = 'DCCDTABS' then
      -- Recupera os tipos de Tabela
      open p_result for 
      select a.sq_stored_proc chave, a.sq_sp_tipo, a.sq_usuario, a.sq_sistema, a.nome nm_sp, a.descricao ds_sp,
             b.nome nm_sp_tipo,
             c.nome nm_usuario, c.descricao ds_usuario,
             d.sigla sg_sistema, d.nome nm_sistema--,
             --e.nome nm_tabela
      from dc_stored_proc         a
           inner join dc_sp_tipo  b on (a.sq_sp_tipo = b.sq_sp_tipo)
           inner join dc_usuario  c on (a.sq_usuario = c.sq_usuario)
           inner join dc_sistema  d on (a.sq_sistema = d.sq_sistema)
     --      inner join dc_sp_tabs  f on (a.sq_stored_proc = f.sq_stored_proc)
     --        left outer join dc_tabela e on (f.sq_tabela = e.sq_tabela)
      where d.cliente = p_cliente
        and ((p_chave      is null) or (p_chave      is not null and a.sq_stored_proc = p_chave))
        and ((p_sq_sp_tipo is null) or (p_sq_sp_tipo is not null and a.sq_sp_tipo     = p_sq_sp_tipo))
        and ((p_sq_usuario is null) or (p_sq_usuario is not null and a.sq_usuario     = p_sq_usuario))
--        and ((p_sq_usuario is null) or (p_sq_usuario is not null and e.sq_usuario     = p_sq_usuario))        
        and ((p_sq_sistema is null) or (p_sq_sistema is not null and a.sq_sistema     = p_sq_sistema))
--        and ((p_sq_sistema is null) or (p_sq_sistema is not null and e.sq_sistema     = p_sq_sistema))        
        and ((p_nome       is null) or (p_nome       is not null and upper(a.nome) like '%'||upper(p_nome)||'%'));
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;