create or replace FUNCTION SP_GetMeioTransporte
   (p_cliente           numeric,
    p_restricao         varchar,
    p_chave             numeric,
    p_ativo             varchar,
    p_nome              varchar,
    p_result            REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os grupos de veículos
   open p_result for 
      select a.sq_meio_transporte as chave, 
             a.nome, a.aereo, a.rodoviario, a.ferroviario, a.aquaviario, a.ativo,
             case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
        from pd_meio_transporte a
       where a.cliente = p_cliente
         and ((p_chave is null)  or (p_chave is not null and a.sq_meio_transporte = p_chave))
         and ((p_ativo is null)  or (p_ativo is not null and a.ativo              = p_ativo))
         and ((p_nome is null)   or (p_nome  is not null and acentos(a.nome)      = acentos(p_nome)));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;