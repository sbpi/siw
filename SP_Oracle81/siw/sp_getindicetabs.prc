create or replace procedure SP_GetIndiceTabs
   (p_chave      in  number   default null,
    p_sq_usuario in  number   default null,
    p_sq_sistema in  number   default null,
    p_sq_tabela  in  number   default null,
    p_result     out siw.sys_refcursor) is
begin
   -- Recupera os tipos de índice
   open p_result for
     select a.sq_indice chave, a.sq_indice_tipo, a.sq_usuario, a.sq_sistema, a.nome nm_indice, a.descricao ds_indice,
            b.nome nm_indice_tipo,
            c.nome nm_usuario,
            d.sigla sg_sistema, d.nome nm_sistema,
            decode(e.sq_tabela,null,0,e.sq_tabela) sq_tabela,
            decode(e.nm_tabela,null,'---',e.nm_tabela) nm_tabela,
            IndiceCols(a.sq_indice) colunas
     from dc_indice                      a,
          dc_indice_tipo b,
          dc_usuario     c,
          dc_sistema     d,
          (select distinct w.sq_indice, z.sq_tabela, z.nome nm_tabela
                             from dc_indice                     w,
                                  dc_indice_cols x,
                                    dc_coluna      y,
                                      dc_tabela      z
                             where (w.sq_indice = x.sq_indice)
                               and (x.sq_coluna = y.sq_coluna)
                               and (y.sq_tabela = z.sq_tabela)
                          )              e 
    where (a.sq_indice_tipo = b.sq_indice_tipo)
      and (a.sq_usuario     = c.sq_usuario)
      and (a.sq_sistema     = d.sq_sistema)
      and (a.sq_indice      = e.sq_indice (+))
      and ((p_chave      is null) or (p_chave      is not null and a.sq_indice  = p_chave))
      and ((p_sq_usuario is null) or (p_sq_usuario is not null and a.sq_usuario = p_sq_usuario))
      and ((p_sq_sistema is null) or (p_sq_sistema is not null and a.sq_sistema = p_sq_sistema))
      and ((p_sq_tabela  is null) or (p_sq_tabela  is not null and e.sq_tabela  = p_sq_tabela));
end SP_GetIndiceTabs;
/

