create or replace FUNCTION SP_GetCargo
   (p_cliente      numeric,
    p_chave        numeric,
    p_sq_tipo      numeric,
    p_nome         varchar,
    p_sq_formacao  numeric,
    p_ativo        varchar,
    p_restricao    varchar,
    p_result       REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE

BEGIN
   If p_restricao = 'VERIFICACONTRATO' Then
      open p_result for                  
         select count(*) existe
           from gp_contrato_colaborador a 
          where a.sq_posto_trabalho = p_chave;
   ElsIf p_restricao = 'VERIFICANOME' Then
         If p_chave is not null then
            open p_result for
            select count(*) existe
              from eo_posto_trabalho a
             where a.cliente = p_cliente
               and (a.sq_posto_trabalho <> p_chave)
               and (a.nome = p_nome);
         else
            open p_result for
            select count(*) existe
               from eo_posto_trabalho a
              where a.cliente = p_cliente
                and (a.nome = p_nome);
         End If;
   ElsIf p_restricao is null Then
      -- Recupera todos os cargos
      open p_result for
         select a.sq_posto_trabalho chave, a.cliente, a.nome, a.descricao, a.atividades, a.competencias,
                a.salario_piso, a.salario_teto, a.ativo,
                b.nome nm_tipo_posto, b.descricao ds_tipo_posto, b.sq_eo_tipo_posto sq_tipo_posto,
                c.nome nm_formacao, c.sq_formacao, d.nome as nm_area, d.codigo_cnpq
           from         eo_posto_trabalho    a
             inner join eo_tipo_posto        b on (a.sq_eo_tipo_posto = b.sq_eo_tipo_posto)
             inner join co_formacao          c on (a.sq_formacao      = c.sq_formacao)
              left join co_area_conhecimento d on (a.sq_area_conhecimento = d.sq_area_conhecimento)
          where a.cliente = p_cliente
            and ((p_chave       is null) or (p_chave       is not null and a.sq_posto_trabalho = p_chave))
            and ((p_sq_tipo     is null) or (p_sq_tipo     is not null and a.sq_eo_tipo_posto  = p_sq_tipo))
            and ((p_nome        is null) or (p_nome        is not null and a.nome              = p_nome))
            and ((p_sq_formacao is null) or (p_sq_formacao is not null and a.sq_formacao       = p_sq_formacao))
            and ((p_ativo       is null) or (p_ativo       is not null and a.ativo             = p_ativo));
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;