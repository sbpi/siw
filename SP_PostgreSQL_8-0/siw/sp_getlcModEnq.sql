create or replace FUNCTION SP_GetLcModEnq
   (p_chave         numeric,
    p_chave_aux     numeric,
    p_sigla         varchar,
    p_ativo         varchar,
    p_restricao     varchar,
    p_result       REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo sigla ou sigla
      open p_result for 
      select a.sq_modalidade_artigo as chave
        from lc_modalidade_artigo a
       where a.sq_lcmodalidade    = p_chave 
         and a.sq_modalidade_artigo <> coalesce(p_chave_aux,0)
         and ((p_sigla   is null) or (p_sigla   is not null and a.sigla = p_sigla));
   Else
     open p_result for 
      select a.sq_modalidade_artigo as chave, a.sq_lcmodalidade, a.sigla, a.descricao, a.ativo, 
             case a.ativo  when 'S' then 'Sim' else 'Não' end nm_ativo
        from lc_modalidade_artigo a
       where a.sq_lcmodalidade      = p_chave 
         and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_modalidade_artigo = p_chave_aux))
         and ((p_sigla     is null) or (p_sigla     is not null and a.sigla                = p_sigla))     
         and ((p_ativo     is null) or (p_ativo     is not null and a.ativo                = p_ativo));         
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;