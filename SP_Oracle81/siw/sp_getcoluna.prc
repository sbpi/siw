create or replace procedure Sp_GetColuna
   (p_cliente      in  number,
    p_chave        in  number   default null,
    p_sq_tabela    in  number   default null,
    p_sq_dado_tipo in  varchar2 default null,
    p_sq_sistema   in  number   default null,
    p_sq_usuario   in  number   default null,
    p_nome         in  varchar2 default null,
    p_result       out siw.sys_refcursor) is
begin
   -- Recupera dados das colunas de uma tabela
   open p_result for
      select a.sq_coluna chave, a.sq_tabela, a.sq_dado_tipo, a.nome nm_coluna, a.descricao,
             a.ordem, a.tamanho, a.precisao, a.escala, a.obrigatorio, a.valor_padrao,
             b.nome nm_tabela, b.descricao ds_tabela,
             c.nome nm_coluna_tipo,
             d.sigla sg_sistema, d.nome nm_sistema, d.sq_sistema,
             e.nome nm_usuario, e.sq_usuario,
             f.nome nm_tabela_tipo, g.sq_relacionamento,
             h.ordem or_esquma_atributo, h.campo_externo
        from dc_coluna                      a,
             dc_tabela      b,
               dc_tabela_tipo f,
             dc_dado_tipo   c,
             dc_sistema     d,
             dc_usuario     e,
             dc_relac_cols  g,
             dc_esquema_atributo h
       where (a.sq_tabela      = b.sq_tabela)
         and (b.sq_tabela_tipo = f.sq_tabela_tipo)
         and (a.sq_dado_tipo   = c.sq_dado_tipo)
         and (b.sq_sistema     = d.sq_sistema)
         and (b.sq_usuario     = e.sq_usuario)
         and (a.sq_coluna      = g.coluna_filha (+))
         and (a.sq_coluna = h.sq_coluna (+))
         and d.cliente = p_cliente
         and ((p_chave        is null) or (p_chave        is not null and a.sq_coluna    = p_chave))
         and ((p_sq_tabela    is null) or (p_sq_tabela    is not null and b.sq_tabela    = p_sq_tabela))
         and ((p_sq_dado_tipo is null) or (p_sq_dado_tipo is not null and a.sq_dado_tipo = p_sq_dado_tipo))
         and ((p_sq_sistema   is null) or (p_sq_sistema   is not null and b.sq_sistema   = p_sq_sistema))
         and ((p_sq_usuario   is null) or (p_sq_usuario   is not null and b.sq_usuario   = p_sq_usuario))
         and ((p_nome         is null) or (p_nome         is not null and upper(a.nome)  like '%'||upper(p_nome)||'%'))
         and ((p_sq_tabela    is null) or (p_sq_tabela    is not null and b.sq_tabela    = p_sq_tabela));
end SP_GetColuna;
/

