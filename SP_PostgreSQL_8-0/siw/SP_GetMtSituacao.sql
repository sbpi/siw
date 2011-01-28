create or replace FUNCTION SP_GetMtSituacao
   (p_cliente           numeric,
    p_restricao         varchar,
    p_chave             numeric,
    p_ativo             varchar,
    p_nome              varchar,
    p_sigla             varchar,
    p_result            REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os grupos de veículos
   if p_restricao is null then
     open p_result for 
        select a.sq_mtsituacao as chave, a.cliente, a.nome, a.sigla, a.entrada, a.saida, a.estorno, a.consumo, a.permanente, a.inativa_bem, a.situacao_fisica, a.ativo 
        from mt_situacao a where 
             a.cliente      = p_cliente
              and (p_chave is null   or (p_chave  is not null and a.sq_mtsituacao      = p_chave))
              and (p_ativo is null   or (p_ativo  is not null and a.ativo              = p_ativo))
              and (p_sigla is null   or (p_sigla  is not null and acentos(a.sigla)     = acentos(p_sigla)))
              and (p_nome is null    or (p_nome  is not null and acentos(a.nome)       = acentos(p_nome)));
   end if;         
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;