create or replace FUNCTION SP_GetGrupoVeiculo
   (p_chave     numeric,
    p_cliente   numeric,
    p_nome      varchar,   
    p_sigla     varchar,   
    p_ativo     varchar,           
    p_result    REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
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
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;