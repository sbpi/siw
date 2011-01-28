create or replace FUNCTION SP_GetEsquemaAtributo
   (p_restricao           varchar,
    p_sq_esquema_tabela   numeric,
    p_sq_esquema_atributo numeric,
    p_sq_coluna           numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as coluna cadastradas em uma tabela para importação
   open p_result for 
      select a.sq_esquema_atributo, a.sq_esquema_tabela, a.sq_coluna, a.ordem, a.campo_externo,
             a.mascara_data, a.valor_default,
             b.nome nm_coluna, b.tamanho, b.obrigatorio, b.ordem or_coluna, b.descricao,
             b.precisao, b.escala,
             c.nome nm_coluna_tipo
        from dc_esquema_atributo     a 
             inner join dc_coluna    b on (a.sq_coluna    = b.sq_coluna)
             inner join dc_dado_tipo c on (b.sq_dado_tipo = c.sq_dado_tipo) 
       where a.sq_esquema_tabela = p_sq_esquema_tabela
         and ((p_sq_esquema_atributo is null) or (p_sq_esquema_atributo is not null and a.sq_esquema_atributo = p_sq_esquema_tabela))
         and ((p_sq_coluna           is null) or (p_sq_coluna           is not null and a.sq_coluna           = p_sq_coluna));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;