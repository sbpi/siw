create or replace procedure SP_GetGrupoVeiculo
   (p_chave     in number default null,
    p_cliente   in number,
    p_nome      in varchar2 default null,   
    p_sigla     in varchar2 default null,   
    p_ativo     in varchar2 default null,           
    p_result    out sys_refcursor) is
begin
   -- Recupera os grupos de veículos
   open p_result for 
         select a.sq_grupo_veiculo chave, a.cliente, a.nome, a.sigla, a.descricao, a.ativo,
                case a.ativo when 'S' Then 'Sim' Else 'Não' end  nm_ativo
           from sr_grupo_veiculo   a
      where a.cliente     = p_cliente
        and ((p_chave     is null) or (p_chave     is not null and a.sq_grupo_veiculo = p_chave))
        and ((p_sigla     is null) or (p_sigla     is not null and a.sigla            = p_sigla))
        and ((p_ativo     is null) or (p_ativo     is not null and a.ativo            = p_ativo))        
        and ((p_nome      is null) or (p_nome      is not null and a.nome             = p_nome));
end SP_GetGrupoVeiculo;
/
