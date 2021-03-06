create or replace FUNCTION SP_GetCLParametro
   (p_cliente   numeric,
    p_chave_aux numeric,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os parametros do modulo de compras e licitações de um cliente
      open p_result for 
         select a.cliente, a.ano_corrente, a.dias_validade_pesquisa, a.dias_aviso_pesquisa, a.percentual_acrescimo, a.compra_central, a.pesquisa_central, 
                a.contrato_central, a.banco_ata_central, a.banco_preco_central, a.codificacao_central, a.cadastrador_geral,
                case compra_central      when 'S' then 'Sim' else 'Não' end as nm_compra_central,
                case pesquisa_central    when 'S' then 'Sim' else 'Não' end as nm_pesquisa_central,
                case contrato_central    when 'S' then 'Sim' else 'Não' end as nm_contrato_central,
                case banco_ata_central   when 'S' then 'Sim' else 'Não' end as nm_banco_ata_central,
                case banco_preco_central when 'S' then 'Sim' else 'Não' end as nm_banco_preco_central,
                case codificacao_central when 'S' then 'Sim' else 'Não' end as nm_codificacao_central,
                case cadastrador_geral   when 'S' then 'Sim' else 'Não' end as nm_cadastrador_geral
           from cl_parametro a
          where a.cliente = p_cliente;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;