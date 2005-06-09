create or replace procedure SP_GetIndiceCols
   (p_sq_indice in  number   default null,
    p_sq_coluna in  number   default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os tipos de índice
   open p_result for
     select a.ordem, a.ordenacao,
            b.sq_indice chave, b.nome nm_indice, b.descricao ds_indice,
            d.nome nm_indice_tipo,
            e.sq_sistema, e.sq_tabela, e.nome nm_tabela,
            f.nome nm_usuario,
            IndiceCols(b.sq_indice) colunas
       from dc_indice_cols                a,
            dc_indice      b,
              dc_indice_tipo d,
            dc_coluna      c,
              dc_tabela      e,
                dc_usuario     f
       where (a.sq_indice      = b.sq_indice)
         and (b.sq_indice_tipo = d.sq_indice_tipo)
         and (a.sq_coluna      = c.sq_coluna)
         and (c.sq_tabela      = e.sq_tabela)
         and (e.sq_usuario     = f.sq_usuario)
         and ((p_sq_indice is null) or (p_sq_indice is not null and a.sq_indice = p_sq_indice))
         and ((p_sq_coluna is null) or (p_sq_coluna is not null and a.sq_coluna = p_sq_coluna));
end SP_GetIndiceCols;
/

