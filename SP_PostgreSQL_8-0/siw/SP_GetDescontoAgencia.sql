create or replace FUNCTION SP_GetDescontoAgencia
   (p_cliente          numeric,
    p_chave            numeric,
    p_agencia          numeric, 
  --p_inicio           numeric,    
  --p_fim              numeric,
    p_percentual       numeric,
    p_ativo            varchar,
  --p_desconto         numeric,
    p_result          REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as companhias de viagem
   open p_result for
      select a.sq_desconto_agencia as chave,
             a.cliente,
             a.agencia_viagem,
             b.nome,
             b.nome_resumido,
             a.faixa_inicio,
             a.faixa_fim,
             a.desconto,
             a.ativo
        from pd_desconto_agencia a
       inner join co_pessoa b on (a.agencia_viagem = b.sq_pessoa)
       where a.cliente = p_cliente
         and (p_chave      is null or (p_chave      is not null and a.sq_desconto_agencia = p_chave))
         and (p_agencia    is null or (p_agencia    is not null and a.agencia_viagem      = p_agencia))
         and (p_ativo      is null or (p_ativo      is not null and a.ativo               = p_ativo))
         and (p_percentual is null or (p_percentual is not null and p_percentual between a.faixa_inicio and a.faixa_fim));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;