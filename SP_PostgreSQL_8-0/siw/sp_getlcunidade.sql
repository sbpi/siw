create or replace FUNCTION SP_GetLcUnidade
   (p_chave      numeric,
    p_cliente    numeric,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as unidades de licitação
   open p_result for 
      select a.sq_unidade chave, a.cnpj, a.licita, a.contrata, a.ativo, a.padrao,
             b.nome, b.sigla,
             case a.licita   when 'S' then 'Sim' else 'Não' end nm_licita,
             case a.contrata when 'S' then 'Sim' else 'Não' end nm_contrata,
             case a.ativo    when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao   when 'S' then 'Sim' else 'Não' end nm_padrao
        from lc_unidade                    a
             left  join eo_unidade         b on (a.sq_unidade = b.sq_unidade)
       where b.sq_pessoa = p_cliente
         and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;