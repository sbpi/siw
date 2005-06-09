create or replace procedure SP_GetLcModalidade
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera as modalidades de licitação
   open p_result for
      select a.sq_lcmodalidade chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao, a.sigla,
             a.fundamentacao,
             decode(a.ativo,'S','Sim','Não') nm_ativo,
             decode(a.padrao,'S','Sim','Não') nm_padrao
        from lc_modalidade a
       where a.cliente = p_cliente
         and ((p_chave is null) or (p_chave is not null and a.sq_lcmodalidade = p_chave));
end SP_GetLcModalidade;
/

