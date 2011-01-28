create or replace FUNCTION sp_GetTipoGuarda_PA
   (p_chave             numeric,
    p_cliente           numeric,
    p_sigla             varchar,
    p_descricao         varchar,
    p_fase_corrente     varchar,
    p_fase_intermed     varchar,
    p_fase_final        varchar,
    p_destinacao_final  varchar,
    p_ativo             varchar,
    p_restricao         varchar,
    p_result           REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os tipos de guarda
      open p_result for 
         select a.sq_tipo_guarda chave, a.cliente, a.sigla, a.descricao, a.fase_corrente, 
                a.fase_intermed, a.fase_final, a.destinacao_final, a.ativo,
                case a.fase_corrente    when 'S' then 'Sim' else 'Não' end as nm_fase_corrente,
                case a.fase_intermed    when 'S' then 'Sim' else 'Não' end as nm_fase_intermed,
                case a.fase_final       when 'S' then 'Sim' else 'Não' end as nm_fase_final,
                case a.destinacao_final when 'S' then 'Sim' else 'Não' end as nm_destinacao_final,
                case a.ativo            when 'S' then 'Sim' else 'Não' end as nm_ativo
           from pa_tipo_guarda a
          where ((p_chave   is null) or (p_chave   is not null and a.sq_tipo_guarda   = p_chave))
            and ((p_cliente is null) or (p_cliente is not null and a.cliente          = p_cliente))
            and ((p_sigla   is null) or (p_sigla   is not null and upper(a.sigla)     = upper(p_sigla)))
            and ((p_descricao        is null) or (p_descricao        is not null and upper(a.descricao) like '%'||upper(p_descricao)||'%'))
            and ((p_fase_corrente    is null) or (p_fase_corrente    is not null and a.fase_corrente    = p_fase_corrente))
            and ((p_fase_intermed    is null) or (p_fase_intermed    is not null and a.fase_intermed    = p_fase_intermed))
            and ((p_fase_final       is null) or (p_fase_final       is not null and a.fase_final       = p_fase_final))
            and ((p_destinacao_final is null) or (p_destinacao_final is not null and a.destinacao_final = p_destinacao_final))
            and ((p_ativo            is null) or (p_ativo            is not null and a.ativo            = p_ativo));
   ElsIf p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com a mesma descrição ou sigla
      open p_result for 
         select a.sq_tipo_guarda chave, a.cliente, a.sigla, a.descricao, a.fase_corrente, 
                a.fase_intermed, a.fase_final, a.destinacao_final, a.ativo,
                case a.fase_corrente    when 'S' then 'Sim' else 'Não' end as nm_fase_corrente,
                case a.fase_intermed    when 'S' then 'Sim' else 'Não' end as nm_fase_intermed,
                case a.fase_final       when 'S' then 'Sim' else 'Não' end as nm_fase_final,
                case a.destinacao_final when 'S' then 'Sim' else 'Não' end as nm_destinacao_final,
                case a.ativo            when 'S' then 'Sim' else 'Não' end as nm_ativo
           from pa_tipo_guarda a
          where a.sq_tipo_guarda     <> coalesce(p_chave,0)
            and a.cliente            = p_cliente
            and ((p_sigla   is null) or (p_sigla   is not null and upper(a.sigla)     = upper(p_sigla)))
            and ((p_descricao        is null) or (p_descricao        is not null and upper(a.descricao) like '%'||upper(p_descricao)||'%'))
            and ((p_ativo            is null) or (p_ativo            is not null and a.ativo            = p_ativo));
   ElsIf p_restricao = 'VINCULADO' Then
      -- Verifica se o registro já esta vinculado
      open p_result for 
         select count(*) existe
           from pa_tipo_guarda        a
                inner join pa_assunto b on (a.sq_tipo_guarda = b.fase_corrente_guarda or
                                            a.sq_tipo_guarda = b.fase_intermed_guarda or
                                            a.sq_tipo_guarda = b.fase_final_guarda    or
                                            a.sq_tipo_guarda = b.destinacao_final)
          where a.sq_tipo_guarda = p_chave;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;