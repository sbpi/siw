create or replace procedure SP_GetLancamentoDoc
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
begin
   -- Recupera os dados de um documento ou os documentos de um lançamento financeiro
   -- dependendo dos parâmetros informados
   open p_result for 
      select a.sq_lancamento_doc, a.sq_siw_solicitacao, a.sq_tipo_documento, a.numero, 
             a.data,              a.serie,              a.valor,             a.patrimonio,
             a.calcula_tributo,   a.calcula_retencao,
             case a.patrimonio when 'S' then 'Sim' else 'Não' end nm_patrimonio,
             b.nome nm_tipo_documento, b.sigla sg_tipo_documento,
             c.total_item
        from fn_lancamento_doc            a
             inner join fn_tipo_documento b on (a.sq_tipo_documento = b.sq_tipo_documento)
             left  join (select x.sq_lancamento_doc, sum(x.valor_total) total_item
                           from fn_documento_item x
                          group by x.sq_lancamento_doc
                         )                c on (a.sq_lancamento_doc = c.sq_lancamento_doc)
       where (p_chave     is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
         and (p_chave_aux is null or (p_chave_aux is not null and a.sq_lancamento_doc  = p_chave_aux));
End SP_GetLancamentoDoc;
/
