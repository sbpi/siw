create or replace procedure SP_GetLancamentoItem
   (p_sq_documento_item  in number   default null,
    p_sq_lancamento_doc  in number   default null,
    p_chave              in number   default null,
    p_sq_projeto         in number   default null,
    p_restricao          in varchar2,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
      open p_result for 
         select a.sq_documento_item,  a.sq_lancamento_doc, a.descricao, a.sq_projeto_rubrica,
                a.quantidade, a.valor_unitario, a.valor_total, a.ordem,
                b.sq_siw_solicitacao, b.sq_tipo_documento, 
                c.nome nm_rubrica, c.codigo codigo_rubrica
           from fn_documento_item a,
                fn_lancamento_doc b,
                pj_rubrica        c
          where (a.sq_lancamento_doc  = b.sq_lancamento_doc (+))
            and (a.sq_projeto_rubrica = c.sq_projeto_rubrica (+))
            and (p_sq_lancamento_doc  is null or (p_sq_lancamento_doc  is not null and a.sq_lancamento_doc  = p_sq_lancamento_doc))
            and (p_sq_documento_item  is null or (p_sq_documento_item  is not null and a.sq_documento_item  = p_sq_documento_item))
            and (p_chave              is null or (p_chave              is not null and b.sq_siw_solicitacao = p_chave));
   Elsif p_restricao = 'RUBRICA' Then
      open p_result for 
         select sum(b.valor_total) valor_total, a.nome nm_rubrica, a.codigo codigo_rubrica,
                decode(nvl(a.codigo,'nulo'),'nulo','Não informado',a.codigo||' - '||a.nome)rubrica
           from pj_rubrica        a,
                fn_documento_item b,
                fn_lancamento_doc c
          where (a.sq_projeto_rubrica = b.sq_projeto_rubrica (+))
            and (b.sq_lancamento_doc  = c.sq_lancamento_doc (+))
            and (a.sq_siw_solicitacao = p_sq_projeto or c.sq_siw_solicitacao = p_chave)
       group by a.sq_projeto_rubrica, a.codigo, a.nome;
   End If;
End SP_GetLancamentoItem;
/
