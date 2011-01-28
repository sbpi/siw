create or replace FUNCTION sp_GetTipoDespacho_PA
   (p_chave      numeric,
    p_cliente    numeric,
    p_nome       varchar,
    p_sigla      varchar,
    p_ativo      varchar,
    p_restricao  varchar,
    p_result  REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera os tipos de despachos
      open p_result for 
         select a.sq_tipo_despacho chave, a.cliente, a.nome, a.sigla, a.descricao, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from pa_tipo_despacho a
          where a.cliente   = p_cliente
            and ((p_chave   is null) or (p_chave   is not null and a.sq_tipo_despacho = p_chave))
            and ((p_nome    is null) or (p_nome    is not null and upper(a.nome)      like '%'||upper(p_nome)||'%'))
            and ((p_sigla   is null) or (p_sigla   is not null and upper(a.sigla)     = upper(p_sigla)))
            and ((p_ativo   is null) or (p_ativo   is not null and a.ativo            = p_ativo));
   Elsif p_restricao = 'SELECAO' Then
      -- Recupera os tipos de despachos, menos os definidos na tabela de parâmetros
      open p_result for 
         select a.sq_tipo_despacho chave, a.cliente, a.nome, a.sigla, a.descricao, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from pa_tipo_despacho       a
                left join pa_parametro c on (a.cliente = c.cliente and a.sq_tipo_despacho = c.despacho_emprestimo)
          where a.cliente   = p_cliente
            and c.cliente   is null
            and ((p_chave   is null) or (p_chave   is not null and a.sq_tipo_despacho = p_chave))
            and ((p_nome    is null) or (p_nome    is not null and upper(a.nome)      like '%'||upper(p_nome)||'%'))
            and ((p_sigla   is null) or (p_sigla   is not null and upper(a.sigla)     = upper(p_sigla)))
            and ((p_ativo   is null) or (p_ativo   is not null and a.ativo            = p_ativo));
   Elsif p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo nome ou sigla
      open p_result for 
         select a.sq_tipo_despacho chave, a.cliente, a.nome, a.sigla, a.descricao, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from pa_tipo_despacho a
          where a.sq_tipo_despacho   <> coalesce(p_chave,0)
            and a.cliente            = p_cliente
            and ((p_nome             is null) or (p_nome    is not null and upper(a.nome)      like '%'||upper(p_nome)||'%'))
            and ((p_sigla            is null) or (p_sigla   is not null and upper(a.sigla)     = upper(p_sigla)))
            and ((p_ativo            is null) or (p_ativo   is not null and a.ativo            = p_ativo));   
   Elsif p_restricao = 'VINCULADO' Then
      -- Verifica se o registro já esta vinculado
      open p_result for 
         select count(*) existe
           from pa_tipo_despacho        a
                inner join pa_parametro b on (a.sq_tipo_despacho = b.despacho_arqcentral or
                                              a.sq_tipo_despacho = b.despacho_emprestimo or
                                              a.sq_tipo_despacho = b.despacho_devolucao)
          where a.sq_tipo_despacho = p_chave;   
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;