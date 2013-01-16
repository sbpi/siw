create or replace procedure sp_GetTipoDespacho_PA
   (p_chave     in  number   default null,
    p_cliente   in  number   default null,
    p_nome      in  varchar2 default null,
    p_sigla     in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_restricao in  varchar2 default null,
    p_result  out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os tipos de despachos
      open p_result for 
         select a.sq_tipo_despacho chave, a.cliente, a.nome, a.sigla, a.descricao, a.ativo, a.despacho_original,
                case a.despacho_original when 'S' then 'Sim' else 'Não' end as nm_despacho_original,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from pa_tipo_despacho a
          where a.cliente   = p_cliente
            and ((p_chave   is null) or (p_chave   is not null and a.sq_tipo_despacho = p_chave))
            and ((p_nome    is null) or (p_nome    is not null and upper(a.nome)      like '%'||upper(p_nome)||'%'))
            and ((p_sigla   is null) or (p_sigla   is not null and upper(a.sigla)     = upper(p_sigla)))
            and ((p_ativo   is null) or (p_ativo   is not null and a.ativo            = p_ativo));
   Elsif p_restricao = 'TODOS' Then
      -- Recupera os tipos de despachos, menos os definidos na tabela de parâmetros
      open p_result for 
         select a.sq_tipo_despacho chave, a.cliente, a.nome, a.sigla, a.descricao, a.ativo, a.despacho_original,
                case a.despacho_original when 'S' then 'Sim' else 'Não' end as nm_despacho_original,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from pa_tipo_despacho       a
          where a.cliente   = p_cliente
            and ((p_chave   is null) or (p_chave   is not null and a.sq_tipo_despacho = p_chave))
            and ((p_nome    is null) or (p_nome    is not null and upper(a.nome)      like '%'||upper(p_nome)||'%'))
            and ((p_sigla   is null) or (p_sigla   is not null and upper(a.sigla)     = upper(p_sigla)));
   Elsif p_restricao = 'SELECAO' or p_restricao = 'SELECAOCAD' Then
      -- Recupera os tipos de despachos, menos os definidos na tabela de parâmetros
      open p_result for 
         select a.sq_tipo_despacho chave, a.cliente, a.nome, a.sigla, a.descricao, a.ativo, a.despacho_original,
                case a.despacho_original when 'S' then 'Sim' else 'Não' end as nm_despacho_original,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from pa_tipo_despacho       a
                left join pa_parametro c on (a.cliente = c.cliente and a.sq_tipo_despacho = c.despacho_emprestimo)
          where a.cliente   = p_cliente
            and c.cliente   is null
            and ((p_chave   is null) or (p_chave   is not null and a.sq_tipo_despacho = p_chave))
            and ((p_nome    is null) or (p_nome    is not null and upper(a.nome)      like '%'||upper(p_nome)||'%'))
            and ((p_sigla   is null) or (p_sigla   is not null and upper(a.sigla)     = upper(p_sigla)))
            and ((p_ativo   is null) or (p_ativo   is not null and a.ativo            = p_ativo))
            and (p_restricao = 'SELECAO' or (p_restricao = 'SELECAOCAD' and a.despacho_original = 'S'));
   Elsif p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com o mesmo nome ou sigla
      open p_result for 
         select a.sq_tipo_despacho chave, a.cliente, a.nome, a.sigla, a.descricao, a.ativo, a.despacho_original,
                case a.despacho_original when 'S' then 'Sim' else 'Não' end as nm_despacho_original,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from pa_tipo_despacho a
          where a.sq_tipo_despacho   <> coalesce(p_chave,0)
            and a.cliente            = p_cliente
            and ((p_nome             is null) or (p_nome    is not null and upper(a.nome)      = upper(p_nome)))
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
end sp_GetTipoDespacho_PA;
/
