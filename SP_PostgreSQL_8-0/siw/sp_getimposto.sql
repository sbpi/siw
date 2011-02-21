create or replace FUNCTION SP_GetImposto
   (p_chave     numeric,
    p_cliente   numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
  -- Recupera os tipos de contrato do cliente
  open p_result for 
     select a.sq_imposto chave, a.nome, a.descricao, a.sigla, a.dia_pagamento, a.esfera,  
            a.calculo, a.ativo, a.tipo_beneficiario, a.tipo_vinculo, a.sq_beneficiario,
            a.sq_cc_vinculo, a.sq_solic_vinculo,
            case a.calculo 
                 when 0 then 'Nominal' 
                 when 1 then 'Retenção' 
            end  as nm_calculo,
            case a.esfera  
                 when 'F' then 'Federal' 
                 when 'E' then 'Estadual' 
                 when 'M' then 'Municipal' 
            end  as nm_esfera,                               
            case a.ativo
                 when 'S' then 'Sim' 
                 else          'Não' 
            end as nm_ativo,
            case a.tipo_beneficiario
                 when 0 Then 'Igual lançamento'
                 When 1 Then a1.nome_resumido
                 When 2 Then d.nome_resumido
                 When 3 Then '---'
            end as nm_tipo_beneficiario,
            case a.tipo_vinculo
                 When 0 Then 'Igual lançamento'
                 When 1 Then case when a.sq_solic_vinculo is null 
                                  then case when e.sq_cc is null
                                            then '???'
                                            else 'Classif: '||e.nome 
                                       end
                                  else dados_solic(a.sq_solic_vinculo) 
                             end
                 When 2 Then '---'
            end as nm_tipo_vinculo,
            b.sq_tipo_lancamento,            b.nome    as nm_tipo_lancamento, b.descricao as ds_tipo_lancamento, 
            b.receita as lancamento_receita, b.despesa as lancamento_despesa, b.ativo     as lancamento_ativo,
            c.sq_tipo_documento,             c.nome    as nm_tipo_documento,  c.sigla     as sg_tipo_documento,
            c.ativo   as documento_ativo,    c.detalha_item,
            d.nome    as nm_benef,           d.nome_resumido as nm_benef_res
       from fn_imposto                    a
            inner join co_pessoa         a1 on (a.cliente            = a1.sq_pessoa)
            left  join fn_tipo_lancamento b on (a.sq_tipo_lancamento = b.sq_tipo_lancamento)
            left  join fn_tipo_documento  c on (a.sq_tipo_documento  = c.sq_tipo_documento)
            left  join co_pessoa          d on (a.sq_beneficiario    = d.sq_pessoa)
            left  join ct_cc              e on (a.sq_cc_vinculo      = e.sq_cc)
            left  join siw_solicitacao    f on (a.sq_solic_vinculo   = f.sq_siw_solicitacao)
      where a.cliente = p_cliente 
        and ((p_chave is null) or (p_chave is not null and a.sq_imposto = p_chave));

  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;