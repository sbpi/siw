create or replace FUNCTION SP_GetCategoriaDiaria
   (p_cliente          numeric,
    p_chave            numeric,
    p_nome             varchar,
    p_ativo            varchar,
    p_restricao        varchar,
    p_result          REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as categorias de diárias
   open p_result for
      select a.sq_categoria_diaria as chave, a.cliente, a.nome, a.ativo, a.tramite_especial, 
             a.dias_prestacao_contas,        a.valor_complemento,
             case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
             case a.tramite_especial when 'S' then 'Sim' else 'Não' end as nm_tramite_especial
        from pd_categoria_diaria a
       where a.cliente = p_cliente
         and (p_chave      is null or (p_chave      is not null and a.sq_categoria_diaria = p_chave))
         and (p_nome       is null or (p_nome       is not null and upper(acentos(a.nome)) like upper(acentos(p_nome))))
         and (p_ativo      is null or (p_ativo      is not null and a.ativo  = p_ativo));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;