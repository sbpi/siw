create or replace FUNCTION sp_GetGPFeriasDias
  (p_chave          numeric,
   p_cliente        numeric,
   p_ativo          varchar,
   p_result         REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
  open p_result for 
  select sq_ferias_dias as chave, 
         cliente,
         faixa_inicio,
         faixa_fim,
         dias_ferias,
         ativo
    from gp_ferias_dias
   where cliente = p_cliente 
     and((p_chave is null) or p_chave is not null and sq_ferias_dias = p_chave)
     and ((p_ativo is null) or p_ativo is not null and  ativo = p_ativo)
   order by faixa_inicio;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;