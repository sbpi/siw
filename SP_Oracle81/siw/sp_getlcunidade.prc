create or replace procedure SP_GetLcUnidade
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera as unidades de licitação
   open p_result for
      select a.sq_unidade chave, a.cnpj, a.licita, a.contrata, a.ativo, a.padrao,
             b.nome, b.sigla,
             decode(a.licita  ,'S','Sim','Não') nm_licita,
             decode(a.contrata,'S','Sim','Não') nm_contrata,
             decode(a.ativo   ,'S','Sim','Não') nm_ativo,
             decode(a.padrao  ,'S','Sim','Não') nm_padrao
        from lc_unidade a,
             eo_unidade         b,
             co_pessoa_endereco c
       where (a.sq_unidade = b.sq_unidade (+))
         and (b.sq_pessoa_endereco = c.sq_pessoa_endereco (+))
         and c.sq_pessoa = p_cliente
         and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));
end SP_GetLcUnidade;
/

