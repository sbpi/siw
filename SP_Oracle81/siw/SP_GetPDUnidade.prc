create or replace procedure SP_GetPDUnidade
   (p_chave     in  number   default null,
    p_cliente   in  number,
    p_ativo     in  varchar2 default null,
    p_ano       in  number   default null,
    p_result    out siw.sys_refcursor) is
begin
   -- Recupera as unidades de modulo de passagens e diárias
   open p_result for 
      select a.sq_unidade chave, a.limite_passagem, a.limite_diaria, a.ativo, a.ano, 
             decode(a.ativo,'S','Sim','Não') nm_ativo,
             b.nome, b.sigla
        from pd_unidade         a,
             eo_unidade         b, 
             co_pessoa_endereco c 
       where (a.sq_unidade = b.sq_unidade (+))
         and (b.sq_pessoa_endereco = c.sq_pessoa_endereco (+))
         and c.sq_pessoa = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave))
         and ((p_ativo is null) or (p_ativo is not null and a.ativo = p_ativo))
         and ((p_ano   is null) or (p_ano   is not null and a.ano   = p_ano));         
end SP_GetPDUnidade;
/
