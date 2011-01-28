create or replace FUNCTION Sp_GetColuna
   (p_cliente       numeric,
    p_chave         numeric, 
    p_sq_tabela     numeric,
    p_sq_dado_tipo  varchar,
    p_sq_sistema    numeric, 
    p_sq_usuario    numeric, 
    p_nome          varchar,
    p_esq_tab       numeric,
    p_result       REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera dados das colunas de uma tabela
   open p_result for 
      select a.sq_coluna chave, a.sq_tabela, a.sq_dado_tipo, a.nome nm_coluna, a.descricao, 
             a.ordem, a.tamanho, a.precisao, a.escala, a.obrigatorio, a.valor_padrao,
             b.nome nm_tabela, b.descricao ds_tabela,
             c.nome nm_coluna_tipo,
             d.sigla sg_sistema, d.nome nm_sistema, d.sq_sistema,
             e.nome nm_usuario, e.sq_usuario,
             f.nome nm_tabela_tipo, g.sq_relacionamento,
             h.ordem or_esquma_atributo, h.campo_externo, h.sq_esquema_tabela, 
             h.mascara_data, h.valor_default
        from dc_coluna                      a
             inner      join dc_tabela      b on (a.sq_tabela      = b.sq_tabela)
               inner    join dc_tabela_tipo f on (b.sq_tabela_tipo = f.sq_tabela_tipo)
             inner      join dc_dado_tipo   c on (a.sq_dado_tipo   = c.sq_dado_tipo) 
             inner      join dc_sistema     d on (b.sq_sistema     = d.sq_sistema)
             inner      join dc_usuario     e on (b.sq_usuario     = e.sq_usuario)
             left outer join dc_relac_cols  g on (a.sq_coluna      = g.coluna_filha)
             left outer join (select sq_coluna, ordem, campo_externo, sq_esquema_tabela,
                                     mascara_data, valor_default
                                from dc_esquema_atributo 
                               where sq_esquema_tabela = nvl(p_esq_tab, sq_esquema_tabela)
                             )              h on (a.sq_coluna = h.sq_coluna)
       where d.cliente        = p_cliente
         and ((p_chave        is null) or (p_chave        is not null and a.sq_coluna         = p_chave))
         and ((p_sq_tabela    is null) or (p_sq_tabela    is not null and b.sq_tabela         = p_sq_tabela))
         and ((p_sq_dado_tipo is null) or (p_sq_dado_tipo is not null and a.sq_dado_tipo      = p_sq_dado_tipo))
         and ((p_sq_sistema   is null) or (p_sq_sistema   is not null and b.sq_sistema        = p_sq_sistema))
         and ((p_sq_usuario   is null) or (p_sq_usuario   is not null and b.sq_usuario        = p_sq_usuario))
         and ((p_nome         is null) or (p_nome         is not null and upper(a.nome)       like '%'||upper(p_nome)||'%'))
         and ((p_sq_tabela    is null) or (p_sq_tabela    is not null and b.sq_tabela         = p_sq_tabela));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;