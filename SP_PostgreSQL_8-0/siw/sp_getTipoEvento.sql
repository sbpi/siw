create or replace FUNCTION sp_getTipoEvento
   (p_cliente   numeric,
    p_servico   numeric,
    p_chave     numeric,
    p_nome      varchar,
    p_sigla     varchar,
    p_ativo     varchar,
    p_restricao varchar,
    p_result    REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao = 'REGISTROS' Then
      -- Recupera os tipos de Evento existentes
      open p_result for 
         select a.sq_tipo_evento as chave, a.sq_menu, a.nome,
                a.ordem, a.sigla, a.descricao, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                b.nome||' ('||c.nome||')' as nm_servico
           from siw_tipo_evento         a
                inner   join siw_menu   b on (a.sq_menu   = b.sq_menu)
                  inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
          where b.sq_pessoa  = p_cliente
            and (p_servico   is null or (p_servico is not null and a.sq_menu        = p_servico))
            and (p_chave     is null or (p_chave   is not null and a.sq_tipo_evento = p_chave))
            and (p_nome      is null or (p_nome    is not null and a.nome           = p_nome))
            and (p_sigla     is null or (p_sigla   is not null and a.sigla          = upper(p_sigla)))
            and (p_ativo     is null or (p_ativo   is not null and a.ativo          = p_ativo))
         order by a.ordem, a.nome;
   Elsif p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo nome ou sigla
      open p_result for 
         select a.sq_tipo_evento as chave, a.sq_menu, a.nome,
                a.ordem, a.sigla, a.descricao, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from siw_tipo_evento         a
                inner   join siw_menu   b on (a.sq_menu   = b.sq_menu)
                  inner join siw_modulo c on (b.sq_modulo = c.sq_modulo)
          where a.sq_menu           = p_servico
            and a.sq_tipo_evento    <> coalesce(p_chave,0)
            and (p_nome             is null or (p_nome    is not null and acentos(a.nome)  = acentos(p_nome)))
            and (p_sigla            is null or (p_sigla   is not null and acentos(a.sigla) = acentos(p_sigla)))
            and (p_ativo            is null or (p_ativo   is not null and a.ativo          = p_ativo))
         order by a.ordem, a.nome;
   Elsif p_restricao = 'VINCULADO' Then
      -- Verifica se o registro está vinculado a um Evento
      open p_result for 
         select a.sq_tipo_evento as chave, a.sq_menu, a.nome,
                a.ordem, a.sigla, a.descricao, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from siw_tipo_evento               a
                inner   join siw_solicitacao  c on (a.sq_tipo_evento = c.sq_tipo_evento)
                  inner join siw_menu         d on (c.sq_menu        = d.sq_menu)
          where d.sq_pessoa         = p_cliente
            and a.sq_menu           = p_servico
            and a.sq_tipo_evento  = p_chave
         order by a.ordem, a.nome;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;