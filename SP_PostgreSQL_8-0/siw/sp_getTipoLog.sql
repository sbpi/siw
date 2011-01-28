create or replace FUNCTION sp_getTipoLog
   (p_cliente   numeric,
    p_menu      numeric,
    p_chave     numeric,
    p_nome      varchar,
    p_sigla     varchar,
    p_ativo     varchar,
    p_restricao varchar,
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os tipos de log existentes
      open p_result for 
         select a.sq_tipo_log as chave, a.cliente, a.sq_menu, a.nome, a.ordem, a.ativo, 
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                b.nome as nm_menu
           from siw_tipo_log        a
                inner join siw_menu b on (a.sq_menu = b.sq_menu)
          where a.cliente            = p_cliente
            and a.sq_menu            = p_menu
            and (p_chave             is null or (p_chave   is not null and a.sq_tipo_log = p_chave))
            and (p_nome              is null or (p_nome    is not null and a.nome        = p_nome))
            and (p_ativo             is null or (p_ativo   is not null and a.ativo       = p_ativo))
         order by a.nome;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;