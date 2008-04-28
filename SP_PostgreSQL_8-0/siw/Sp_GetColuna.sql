CREATE OR REPLACE FUNCTION siw.Sp_GetColuna
   (p_cliente      numeric,
    p_chave        numeric, 
    p_sq_tabela    numeric,
    p_sq_dado_tipo varchar,
    p_sq_sistema   numeric, 
    p_sq_usuario   numeric, 
    p_nome         varchar,
    p_esq_tab      numeric)

  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera dados das colunas de uma tabela
   open p_result for 
      select a.sq_coluna as chave, a.sq_tabela, a.sq_dado_tipo, a.nome as nm_coluna, a.descricao, 
             a.ordem, a.tamanho, a.precisao, a.escala, a.obrigatorio, a.valor_padrao,
             b.nome as nm_tabela, b.descricao as ds_tabela,
             c.nome as nm_coluna_tipo,
             d.sigla as sg_sistema, d.nome as nm_sistema, d.sq_sistema,
             e.nome as nm_usuario, e.sq_usuario,
             f.nome as nm_tabela_tipo, g.sq_relacionamento,
             h.ordem as or_esquma_atributo, h.campo_externo, h.sq_esquema_tabela, 
             h.mascara_data, h.valor_default
        from siw.dc_coluna                      a
             inner      join siw.dc_tabela      b on (a.sq_tabela      = b.sq_tabela)
               inner    join siw.dc_tabela_tipo f on (b.sq_tabela_tipo = f.sq_tabela_tipo)
             inner      join siw.dc_dado_tipo   c on (a.sq_dado_tipo   = c.sq_dado_tipo) 
             inner      join siw.dc_sistema     d on (b.sq_sistema     = d.sq_sistema)
             inner      join siw.dc_usuario     e on (b.sq_usuario     = e.sq_usuario)
             left outer join siw.dc_relac_cols  g on (a.sq_coluna      = g.coluna_filha)
             left outer join (select sq_coluna, ordem, campo_externo, sq_esquema_tabela ,
                                     mascara_data, valor_default
                                from siw.dc_esquema_atributo 
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
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.Sp_GetColuna
   (p_cliente      numeric,
    p_chave        numeric, 
    p_sq_tabela    numeric,
    p_sq_dado_tipo varchar,
    p_sq_sistema   numeric, 
    p_sq_usuario   numeric, 
    p_nome         varchar,
    p_esq_tab      numeric) OWNER TO siw;
