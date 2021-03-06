﻿create or replace FUNCTION sp_getUnidade_CL
   (p_cliente         numeric,
    p_chave           numeric,
    p_ativo          varchar,
    p_restricao      varchar,
    p_result         REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera as unidades do módulo de compras e licitação
      open p_result for 
         select a.sq_unidade chave, a.realiza_compra, a.solicita_compra, a.registra_pesquisa, a.registra_contrato,
                a.registra_judicial, a.controla_banco_ata, a.controla_banco_preco, a.codifica_item, a.codificacao_restrita,
                a.sq_unidade_pai, a.ativo, a.unidade_padrao,
                a.ativo, a.sq_unidade_pai,
                case a.ativo                when 'S' then 'Sim' else 'Não' end as nm_ativo,
                case a.realiza_compra       when 'S' then 'Sim' else 'Não' end as nm_realiza_compra,
                case a.solicita_compra      when 'S' then 'Sim' else 'Não' end as nm_solicita_compra,
                case a.registra_pesquisa    when 'S' then 'Sim' else 'Não' end as nm_registra_pesquisa,
                case a.registra_contrato    when 'S' then 'Sim' else 'Não' end as nm_registra_contrato,
                case a.registra_judicial    when 'S' then 'Sim' else 'Não' end as nm_registra_judicial,
                case a.controla_banco_ata   when 'S' then 'Sim' else 'Não' end as nm_controla_banco_ata,
                case a.controla_banco_preco when 'S' then 'Sim' else 'Não' end as nm_controla_banco_preco,
                case a.codifica_item        when 'S' then 'Sim' else 'Não' end as nm_codifica_item,
                case a.codificacao_restrita when 'S' then 'Sim' else 'Não' end as nm_codificacao_restrita,
                case a.unidade_padrao       when 'S' then 'Sim' else 'Não' end as nm_padrao,
                b.nome, b.sigla,
                e.nome nm_unidade_pai, e.sigla sg_unidade_pai,
                case a.unidade_padrao when 'S' then '0' else '1' end||e.nome||coalesce(b.nome,'0') as ordena,
                case when d.sq_unidade_pai is null then b.sigla else e.sigla end as sg_unidade_pai
           from cl_unidade                      a
                inner   join eo_unidade         b on (a.sq_unidade         = b.sq_unidade)
                left    join cl_unidade         d on (a.sq_unidade_pai     = d.sq_unidade)
                  left  join eo_unidade         e on (d.sq_unidade         = e.sq_unidade)
          where a.cliente = p_cliente 
            and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave))
            and ((p_ativo is null) or (p_ativo is not null and a.ativo   = p_ativo));         
   Elsif p_restricao = 'NUMERADORA' Then
      -- Recupera as unidades numeradoras de compras e licitação (campo sq_unidade_pai nulo)
      open p_result for 
         select a.sq_unidade chave, a.realiza_compra, a.solicita_compra, a.registra_pesquisa, a.registra_contrato,
                a.registra_judicial, a.controla_banco_ata, a.controla_banco_preco, a.codifica_item, a.codificacao_restrita,
                a.sq_unidade_pai, a.ativo, a.unidade_padrao,
                a.ativo,
                case a.ativo                when 'S' then 'Sim' else 'Não' end as nm_ativo,
                case a.realiza_compra       when 'S' then 'Sim' else 'Não' end as nm_realiza_compra,
                case a.solicita_compra      when 'S' then 'Sim' else 'Não' end as nm_solicita_compra,
                case a.registra_pesquisa    when 'S' then 'Sim' else 'Não' end as nm_registra_pesquisa,
                case a.registra_contrato    when 'S' then 'Sim' else 'Não' end as nm_registra_contrato,
                case a.registra_judicial    when 'S' then 'Sim' else 'Não' end as nm_registra_judicial,
                case a.controla_banco_ata   when 'S' then 'Sim' else 'Não' end as nm_controla_banco_ata,
                case a.controla_banco_preco when 'S' then 'Sim' else 'Não' end as nm_controla_banco_preco,
                case a.codifica_item        when 'S' then 'Sim' else 'Não' end as nm_codifica_item,
                case a.codificacao_restrita when 'S' then 'Sim' else 'Não' end as nm_codificacao_restrita,
                case a.unidade_padrao       when 'S' then 'Sim' else 'Não' end as nm_padrao,
                b.nome, b.sigla
           from cl_unidade                      a
                inner   join eo_unidade         b on (a.sq_unidade = b.sq_unidade)
          where a.cliente        = p_cliente 
            and a.sq_unidade_pai is null
            and a.ativo          = 'S'
            and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));
   End If;

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;