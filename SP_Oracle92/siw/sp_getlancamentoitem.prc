create or replace procedure SP_GetLancamentoItem
   (p_sq_documento_item  in number   default null,
    p_sq_lancamento_doc  in number   default null,
    p_chave              in number   default null,
    p_sq_projeto         in number   default null,
    p_restricao          in varchar2,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      open p_result for 
         select a.sq_documento_item,  a.sq_lancamento_doc, a.descricao, a.sq_projeto_rubrica,
                a.quantidade, a.valor_unitario, a.valor_total, a.ordem, a.data_cotacao, a.valor_cotacao,
                b.sq_siw_solicitacao, b.sq_tipo_documento, 
                c.nome as nm_rubrica, c.codigo as codigo_rubrica
           from fn_documento_item           a
                left join fn_lancamento_doc b on (a.sq_lancamento_doc  = b.sq_lancamento_doc)
                left join pj_rubrica        c on (a.sq_projeto_rubrica = c.sq_projeto_rubrica)
          where (p_sq_lancamento_doc  is null or (p_sq_lancamento_doc  is not null and a.sq_lancamento_doc  = p_sq_lancamento_doc))
            and (p_sq_documento_item  is null or (p_sq_documento_item  is not null and a.sq_documento_item  = p_sq_documento_item))
            and (p_chave              is null or (p_chave              is not null and b.sq_siw_solicitacao = p_chave));
   Elsif p_restricao = 'RUBRICA' Then
      open p_result for 
         select sum(b.valor_total) as valor_total, coalesce(d.nome,e.nome) as nm_rubrica, coalesce(d.codigo,e.codigo) as codigo_rubrica,
                case coalesce(coalesce(d.codigo,e.codigo),'nulo') when 'nulo' then 'Não informado' else coalesce(d.codigo,e.codigo)||' - '||coalesce(d.nome,e.nome) end as rubrica,
                sum(c.valor) as valor_rubrica, coalesce(d.sq_projeto_rubrica,e.sq_projeto_rubrica) as sq_projeto_rubrica
           from fn_lancamento_doc                       a
                left outer join   fn_documento_item     b on (a.sq_lancamento_doc  = b.sq_lancamento_doc)
                  left outer join pj_rubrica            e on (b.sq_projeto_rubrica = e.sq_projeto_rubrica)
                left outer join   fn_lancamento_rubrica c on (a.sq_lancamento_doc  = c.sq_lancamento_doc)
                  left outer join pj_rubrica            d on (c.sq_rubrica_origem  = d.sq_projeto_rubrica)
          where a.sq_siw_solicitacao = p_chave
            and a.sq_acordo_nota     is null
          group by d.sq_projeto_rubrica, d.codigo, d.nome, e.sq_projeto_rubrica, e.codigo, e.nome;
   End If;
End SP_GetLancamentoItem;
/
