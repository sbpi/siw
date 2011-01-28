create or replace FUNCTION SP_GetLcModalidade
   (p_chave        numeric,
    p_cliente      numeric,
    p_nome         varchar,
    p_sigla        varchar,
    p_ativo        varchar,      
   /* p_minimo_pesquisas          numeric,
    p_minimo_participantes      numeric,
    p_minimo_propostas_validas  numeric,
    p_certame                   varchar, */     
    p_restricao    varchar,    
    p_result      REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo nome ou sigla
      open p_result for 
      select a.sq_lcmodalidade chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao, a.sigla,
             a.fundamentacao,a.minimo_pesquisas,a.minimo_participantes,a.minimo_propostas_validas,a.certame,
             a.enquadramento_inicial,a.enquadramento_final,a.gera_contrato,
             case a.certame       when 'S' then 'Sim' else 'Não' end nm_certame,
             case a.gera_contrato when 'S' then 'Sim' else 'Não' end nm_gera_contrato,
             case a.ativo         when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao        when 'S' then 'Sim' else 'Não' end nm_padrao
        from lc_modalidade a
       where a.cliente = p_cliente 
         and a.sq_lcmodalidade    <> coalesce(p_chave,0)
         and ((p_nome  is null) or (p_nome  is not null and a.nome            = p_nome))
         and ((p_sigla is null) or (p_sigla is not null and a.sigla           = p_sigla));
   Else
     open p_result for 
      select a.sq_lcmodalidade chave, a.cliente, a.nome, a.descricao, a.ativo, a.padrao, a.sigla,
             a.fundamentacao,a.minimo_pesquisas,a.minimo_participantes,a.minimo_propostas_validas,a.certame,
             a.enquadramento_inicial,a.enquadramento_final,a.gera_contrato,
             case a.certame       when 'S' then 'Sim' else 'Não' end nm_certame,
             case a.gera_contrato when 'S' then 'Sim' else 'Não' end nm_gera_contrato,
             case a.ativo         when 'S' then 'Sim' else 'Não' end nm_ativo,
             case a.padrao        when 'S' then 'Sim' else 'Não' end nm_padrao
        from lc_modalidade a
       where a.cliente = p_cliente 
         and ((p_chave is null) or (p_chave is not null and a.sq_lcmodalidade = p_chave))
         and ((p_nome  is null) or (p_nome  is not null and a.nome  = p_nome))     
         and ((p_sigla is null) or (p_sigla is not null and a.sigla = p_sigla))     
         and ((p_ativo is null) or (p_ativo is not null and a.ativo = p_ativo));         
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;