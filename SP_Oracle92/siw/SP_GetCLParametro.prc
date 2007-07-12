create or replace procedure SP_GetCLParametro
   (p_cliente   in number,
    p_chave_aux in number    default null,
    p_restricao in varchar2  default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os parametros do modulo de compras e licitações de um cliente
      open p_result for 
         select a.cliente, a.ano_corrente, a.dias_validade_pesquisa, a.dias_aviso_pesquisa, a.percentual_acrescimo, a.compra_central, a.pesquisa_central, 
                a.contrato_central, a.banco_ata_central, a.banco_preco_central, a.codificacao_central,
                case compra_central      when 'S' then 'Sim' else 'Não' end as nm_compra_central,
                case pesquisa_central    when 'S' then 'Sim' else 'Não' end as nm_pesquisa_central,
                case contrato_central    when 'S' then 'Sim' else 'Não' end as nm_contrato_central,
                case banco_ata_central   when 'S' then 'Sim' else 'Não' end as nm_banco_ata_central,
                case banco_preco_central when 'S' then 'Sim' else 'Não' end as nm_banco_preco_central,
                case codificacao_central when 'S' then 'Sim' else 'Não' end as nm_codificacao_central
           from cl_parametro a
          where a.cliente = p_cliente;
   End If;
end SP_GetCLParametro;
/
