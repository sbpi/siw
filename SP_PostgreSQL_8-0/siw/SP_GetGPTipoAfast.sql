CREATE OR REPLACE FUNCTION siw.SP_GetGPTipoAfast
   (p_cliente   numeric,
    p_chave     numeric,
    p_sigla     varchar,
    p_nome      varchar,
    p_ativo     varchar,
    p_chave_aux numeric,
    p_restricao varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   If p_restricao is null Then
      -- Recupera todas ou um dos tipos de afastamento
      open p_result for
         select a.sq_tipo_afastamento as chave, a.cliente, a.nome, a.sigla,
                a.limite_dias, a.sexo, a.percentual_pagamento, a.contagem_dias, a.periodo,
                a.sobrepoe_ferias, a.ativo,
                case sexo when 'M' then 'Masculino' else case sexo when 'F' then 'Feminino' else 'Ambos' end end as nm_sexo
           from siw.gp_tipo_afastamento  a
          where a.cliente = p_cliente
            and ((p_chave is null) or (p_chave is not null and sq_tipo_afastamento   = p_chave))
            and ((p_sigla is null) or (p_sigla is not null and a.sigla               = p_sigla))
            and ((p_nome  is null) or (p_nome  is not null and a.nome                = p_nome))
            and ((p_ativo is null) or (p_ativo is not null and a.ativo               = p_ativo));
   ElsIf p_restricao = 'VERIFICASIGLANOME' Then
      open p_result for
         select count(*) as existe
           from siw.gp_tipo_afastamento  a
          where a.cliente = p_cliente
            and ((p_chave is null) or (p_chave is not null and sq_tipo_afastamento         <> p_chave))
            and (((p_sigla is null) or (p_sigla is not null and a.sigla                     = upper(trim(p_sigla))))
            or  ((p_nome  is null) or (p_nome  is not null and acentos(upper(a.nome),null) = acentos(upper(p_nome)))));

   ElsIf p_restricao = 'VERIFICAAFASTAMENTO' Then
      open p_result for
         select count(*) as existe
           from siw.gp_afastamento a
          where a.sq_tipo_afastamento = p_chave;

   ElsIf p_restricao = 'AFASTAMENTO' Then
      open p_result for
      select distinct a.sq_tipo_afastamento as chave, a.nome, a.ativo
       from siw.gp_tipo_afastamento       a
            inner join siw.gp_afastamento b on (a.sq_tipo_afastamento = b.sq_tipo_afastamento);
   ElsIf p_restricao = 'MODALIDADES' Then
      open p_result for
         select a.sq_tipo_afastamento as chave, a.cliente, a.nome, a.sigla,
                a.limite_dias, a.sexo, a.percentual_pagamento, a.contagem_dias, a.periodo,
                a.sobrepoe_ferias, a.ativo, c.nome as nm_modalidade, b.sq_modalidade_contrato,
                case contagem_dias when 'C' then 'Corridos' else 'Úteis' end as nm_contagem_dias,
                case sexo when 'M' then 'Masculino' else case sexo when 'F' then 'Feminino' else 'Ambos' end end as nm_sexo
           from siw.gp_tipo_afastamento  a
                left outer join siw.gp_afastamento_modalidade b on (a.sq_tipo_afastamento    = b.sq_tipo_afastamento)
                left outer join siw.gp_modalidade_contrato    c on (b.sq_modalidade_contrato = c.sq_modalidade_contrato)
          where a.cliente = p_cliente
            and ((p_chave is null) or (p_chave is not null and a.sq_tipo_afastamento   = p_chave));
   End If;
   return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetGPTipoAfast
   (p_cliente   numeric,
    p_chave     numeric,
    p_sigla     varchar,
    p_nome      varchar,
    p_ativo     varchar,
    p_chave_aux numeric,
    p_restricao varchar) OWNER TO siw;
