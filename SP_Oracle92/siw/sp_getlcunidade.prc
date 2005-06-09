create or replace procedure SP_GetLcUnidade
   (p_chave     in  number default null,
    p_cliente   in  number,
    p_result    out sys_refcursor) is
begin
   -- Recupera as unidades de licitação
   open p_result for 
      select a.sq_unidade chave, a.cnpj, a.licita, a.contrata, a.ativo, a.padrao,
             b.nome, b.sigla,
             case a.licita   when 'S' then 'Sim' else 'Não' end nm_licita,
             case a.contrata when 'S' then 'Sim' else 'Não' end nm_contrata,
             case a.ativo    when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao   when 'S' then 'Sim' else 'Não' end nm_padrao
        from lc_unidade a
             left outer join eo_unidade         b on (a.sq_unidade = b.sq_unidade)
             left outer join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
       where c.sq_pessoa = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));
end SP_GetLcUnidade;
/

