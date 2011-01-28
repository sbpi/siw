create or replace FUNCTION sp_getUnidade_PE
   (p_cliente         numeric,
    p_chave           numeric,
    p_ativo          varchar,
    p_restricao      varchar,
    p_result         REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera as unidades de planejamento
      open p_result for 
         select a.sq_unidade chave, a.descricao, a.ativo, a.planejamento, a.execucao, a.gestao_recursos,
                case a.ativo           when 'S' then 'Sim' else 'Não' end as nm_ativo,
                case a.planejamento    when 'S' then 'Sim' else 'Não' end as nm_planejamento,
                case a.execucao        when 'S' then 'Sim' else 'Não' end as nm_execucao,
                case a.gestao_recursos when 'S' then 'Sim' else 'Não' end as nm_recursos,
                b.nome, b.sigla
           from pe_unidade                      a
                inner   join eo_unidade         b on (a.sq_unidade = b.sq_unidade)
                  inner join co_pessoa_endereco c on (b.sq_pessoa_endereco = c.sq_pessoa_endereco)
          where a.cliente = p_cliente 
            and ((p_chave is null) or (p_chave is not null and a.sq_unidade = p_chave))
            and ((p_ativo is null) or (p_ativo is not null and a.ativo   = p_ativo));         
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;