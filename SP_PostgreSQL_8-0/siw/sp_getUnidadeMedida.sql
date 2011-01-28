create or replace FUNCTION sp_getUnidadeMedida
   (p_cliente   numeric,
    p_chave     numeric,
    p_nome      varchar,
    p_sigla     varchar,
    p_ativo     varchar,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao = 'REGISTROS' Then
      -- Recupera os tipos de recurso existentes
      open p_result for 
         select a.sq_unidade_medida as chave, a.cliente, a.nome,
                a.sigla, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from co_unidade_medida a
          where a.cliente            = p_cliente
            and (p_chave             is null or (p_chave is not null and a.sq_unidade_medida = p_chave))
            and (p_nome              is null or (p_nome is not null and a.nome = p_nome))
            and (p_sigla             is null or (p_sigla is not null and a.sigla = upper(p_sigla)))
            and (p_ativo             is null or (p_ativo is not null and a.ativo = p_ativo))
         order by a.nome;
   Elsif p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo nome ou sigla
      open p_result for 
         select a.sq_unidade_medida as chave, a.cliente, a.nome,
                a.sigla, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from co_unidade_medida a
          where a.cliente           = p_cliente
            and a.sq_unidade_medida <> coalesce(p_chave,0)
            and (p_nome             is null or (p_nome is not null and acentos(a.nome) = acentos(p_nome)))
            and (p_sigla            is null or (p_sigla is not null and acentos(a.sigla) = acentos(p_sigla)))
            and (p_ativo            is null or (p_ativo is not null and a.ativo = p_ativo))
         order by a.nome;
   Elsif p_restricao = 'VINCULADO' Then
      -- Verifica se o registro está vinculado a um recurso
      open p_result for 
         select a.sq_unidade_medida as chave, a.cliente, a.nome,
                a.sigla, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from co_unidade_medida              a
                inner join eo_recurso          b on (a.sq_unidade_medida = b.sq_unidade_medida)
          where a.cliente                = p_cliente
            and a.sq_unidade_medida    = p_chave
         order by a.nome;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;