create or replace FUNCTION SP_GetLcCriterio
   (p_chave         numeric,
    p_cliente       numeric,
    p_nome          varchar,
    p_ativo         varchar,    
    p_padrao        varchar,
    p_item          varchar,
    p_restricao     varchar,
    p_result       REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo nome ou sigla
      open p_result for 
      select a.sq_lcjulgamento chave
        from lc_julgamento a
       where a.cliente = p_cliente 
         and a.sq_lcjulgamento    <> coalesce(p_chave,0)
         and ((p_nome   is null) or (p_nome   is not null and a.nome            = p_nome))
         and ((p_padrao is null) or (p_padrao is not null and a.padrao          = p_padrao));
   Else
     open p_result for 
      select a.sq_lcjulgamento chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao,
             a.item, 
             case a.ativo  when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao when 'S' then 'Sim' else 'Não' end nm_padrao,
             case a.item when 'S' then 'Sim' else 'Não' end nm_item
        from lc_julgamento a
       where a.cliente = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_lcjulgamento = p_chave))
         and ((p_nome  is null) or (p_nome  is not null and a.nome  = p_nome))     
         and ((p_ativo is null) or (p_ativo is not null and a.ativo = p_ativo))
         and ((p_padrao is null) or (p_padrao is not null and a.padrao = p_padrao));         
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;