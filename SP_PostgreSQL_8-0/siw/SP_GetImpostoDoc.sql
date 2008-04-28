CREATE OR REPLACE FUNCTION siw.SP_GetImpostoDoc
   (p_cliente   numeric,
    p_chave     numeric,
    p_chave_aux numeric,
    p_restricao varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os impostos incidentes sobre um lançamento
   open p_result for 
      select a.aliquota_total as al_total, a.aliquota_retencao as al_retencao, a.aliquota_normal as al_normal,
             a.valor_total   as vl_total, a.valor_retencao as   vl_retencao, a.valor_normal  as  vl_normal,
             a.quitacao_retencao, a.quitacao_imposto, a.solic_retencao, a.solic_imposto,
             b.sq_lancamento_doc, b.valor as vl_doc, b.data, b.numero, b.serie, b.patrimonio, 
             b.calcula_tributo,
             c.cliente, d.codigo_interno,
             d.fim, d.conclusao, d.valor,
             e.sq_tipo_lancamento, e.nome as nm_tipo_lancamento, e.descricao as ds_tipo_lancamento, 
             e.receita, e.despesa, e.ativo as at_tipo_lancamento,
             f.sq_tipo_documento, f.nome as nm_tipo_documento, f.sigla as sg_tipo_documento, 
             f.ativo as at_tipo_documento,
             g.sq_imposto, g.nome as nm_imposto, g.sigla as sg_imposto, g.descricao as ds_imposto, 
             g.esfera, g.calculo, g.dia_pagamento, g.ativo as at_imposto
        from siw.fn_imposto_doc                    a
             inner     join siw.fn_lancamento_doc  b on (a.sq_lancamento_doc  = b.sq_lancamento_doc and
                                                     (p_chave_aux is null or (p_chave_aux is not null and b.sq_lancamento_doc  = p_chave_aux))
                                                    )
               inner   join siw.fn_lancamento      c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao and
                                                     c.cliente            = p_cliente
                                                    )
                 inner join siw.siw_solicitacao    d on (c.sq_siw_solicitacao = d.sq_siw_solicitacao and
                                                     (p_chave     is null or (p_chave     is not null and d.sq_siw_solicitacao = p_chave))
                                                    )
                 inner join siw.fn_tipo_lancamento e on (c.sq_tipo_lancamento = e.sq_tipo_lancamento)
               inner   join siw.fn_tipo_documento  f on (b.sq_tipo_documento  = f.sq_tipo_documento)
             inner     join siw.fn_imposto         g on (a.sq_imposto         = g.sq_imposto);
             return p_result;
End 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetImpostoDoc
   (p_cliente   numeric,
    p_chave     numeric,
    p_chave_aux numeric,
    p_restricao varchar) OWNER TO siw;
