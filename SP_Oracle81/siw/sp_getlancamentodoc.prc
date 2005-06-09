create or replace procedure SP_GetLancamentoDoc
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_restricao in varchar2,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera os dados de um documento ou os documentos de um lançamento financeiro
   -- dependendo dos parâmetros informados
   open p_result for
      select a.sq_lancamento_doc, a.sq_siw_solicitacao, a.sq_tipo_documento, a.numero,
             a.data,              a.serie,              a.valor,             a.patrimonio,
             a.calcula_tributo,   a.calcula_retencao,
             decode(a.patrimonio,'S','Sim','Não') nm_patrimonio,
             b.nome nm_tipo_documento, b.sigla sg_tipo_documento
        from fn_lancamento_doc            a,
             fn_tipo_documento b
       where (a.sq_tipo_documento = b.sq_tipo_documento)
         and (p_chave     is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
         and (p_chave_aux is null or (p_chave_aux is not null and a.sq_lancamento_doc  = p_chave_aux));
End SP_GetLancamentoDoc;
/

