create or replace procedure SP_GetImpostoDoc
   (p_cliente   in number,
    p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os impostos incidentes sobre um lançamento
   open p_result for
      select a.aliquota_total al_total, a.aliquota_retencao al_retencao, a.aliquota_normal al_normal,
             a.valor_total    vl_total, a.valor_retencao    vl_retencao, a.valor_normal    vl_normal,
             a.quitacao_retencao, a.quitacao_imposto, a.solic_retencao, a.solic_imposto,
             b.sq_lancamento_doc, b.valor vl_doc, b.data, b.numero, b.serie, b.patrimonio,
             b.calcula_tributo,
             c.cliente, c.codigo_interno,
             d.fim, d.conclusao, d.valor,
             e.sq_tipo_lancamento, e.nome nm_tipo_lancamento, e.descricao ds_tipo_lancamento,
             e.receita, e.despesa, e.ativo at_tipo_lancamento,
             f.sq_tipo_documento, f.nome nm_tipo_documento, f.sigla sg_tipo_documento,
             f.ativo at_tipo_documento,
             g.sq_imposto, g.nome nm_imposto, g.sigla sg_imposto, g.descricao ds_imposto,
             g.esfera, g.calculo, g.dia_pagamento, g.ativo at_imposto
        from fn_imposto_doc                    a,
             fn_lancamento_doc  b,
             fn_lancamento      c,
             siw_solicitacao    d,
             fn_tipo_lancamento e,
             fn_tipo_documento  f,
             fn_imposto         g
       where (a.sq_lancamento_doc  = b.sq_lancamento_doc and
              (p_chave_aux is null or (p_chave_aux is not null and b.sq_lancamento_doc  = p_chave_aux))
              )
         and (b.sq_siw_solicitacao = c.sq_siw_solicitacao and
              c.cliente            = p_cliente
             )
         and (c.sq_siw_solicitacao = d.sq_siw_solicitacao and
              (p_chave     is null or (p_chave     is not null and d.sq_siw_solicitacao = p_chave))
             )
         and (c.sq_tipo_lancamento = e.sq_tipo_lancamento)
         and (b.sq_tipo_documento  = f.sq_tipo_documento)
         and (a.sq_imposto         = g.sq_imposto);
End SP_GetImpostoDoc;
/

