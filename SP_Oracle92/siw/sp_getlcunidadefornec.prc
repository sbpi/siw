create or replace procedure SP_GetLcUnidadeFornec
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out sys_refcursor) is
begin
   -- Recupera as fontes de recurso de licitações
   open p_result for 
      select a.sq_unidade_fornec chave, a.cliente, a.sigla, a.nome, a.descricao, a.ativo, a.padrao,
             case a.ativo         when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao        when 'S' then 'Sim' else 'Não' end nm_padrao
        from lc_unidade_fornec a
       where a.cliente = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_unidade_fornec = p_chave));
end SP_GetLcUnidadeFornec;
/

