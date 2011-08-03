create or replace procedure sp_getValores
   (p_cliente   in number,
    p_menu      in number   default null,
    p_chave     in number   default null,
    p_nome      in varchar2 default null,
    p_tipo      in varchar2 default null,
    p_ativo     in varchar2 default null,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os tipos de acréscimos e supressões existentes
      open p_result for 
         select a.sq_valores, a.nome, a.tipo, a.codigo_externo, a.ativo,
                case a.tipo  when 'S' then 'Supressão' else 'Acréscimo' end as nm_tipo,
                case a.ativo when 'S' then 'Sim'       else 'Não'       end as nm_ativo,
                acentos(nome) as ordena
           from fn_valores                a
                left join fn_valores_vinc b on (a.sq_valores = b.sq_valores)
          where a.cliente            = p_cliente
            and (p_menu              is null or (p_menu    is not null and b.sq_menu     = p_menu))
            and (p_chave             is null or (p_chave   is not null and a.sq_valores  = p_chave))
            and (p_nome              is null or (p_nome    is not null and a.nome        = p_nome))
            and (p_tipo              is null or (p_tipo    is not null and a.tipo        = p_tipo))
            and (p_ativo             is null or (p_ativo   is not null and a.ativo       = p_ativo))
         order by a.nome;
   End If;
end sp_getValores;
/
