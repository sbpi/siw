create or replace FUNCTION SP_GetTipoVeiculo
   (p_chave       numeric,
    p_cliente     numeric,
    p_chave_aux   numeric,    
    p_nome        varchar,   
    p_sigla       varchar, 
    p_ativo       varchar,             
    p_result      REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os grupos de veículos
   open p_result for 
         select a.sq_tipo_veiculo chave, a.cliente,a.sq_grupo_veiculo, a.nome, a.sigla, a.descricao, a.ativo,
                case a.ativo when 'S' Then 'Sim' Else 'Não' end  nm_ativo, b.nome as nm_grupo
           from sr_tipo_veiculo             a 
                inner join sr_grupo_veiculo b on (a.sq_grupo_veiculo = b.sq_grupo_veiculo)
      where a.cliente     = p_cliente
        and ((p_chave     is null) or (p_chave     is not null and a.sq_tipo_veiculo  = p_chave))
        and ((p_chave_aux is null) or (p_chave_aux is not null and a.sq_grupo_veiculo = p_chave_aux))
        and ((p_sigla     is null) or (p_sigla     is not null and a.sigla            = p_sigla))
        and ((p_ativo     is null) or (p_ativo     is not null and a.ativo            = p_ativo))   
        and ((p_nome      is null) or (p_nome      is not null and a.nome             = p_nome));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;