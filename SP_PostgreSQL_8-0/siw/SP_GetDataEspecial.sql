CREATE OR REPLACE FUNCTION siw.SP_GetDataEspecial
   (p_cliente   numeric,
    p_chave     numeric,
    p_ano       varchar,
    p_ativo     varchar,
    p_tipo      varchar,
    p_chave_aux numeric,
    p_restricao varchar,
    p_result    refcursor
   )
  RETURNS refcursor AS
$BODY$
begin
   If p_restricao is null Then
      -- Recupera todas ou muma das modalidades de contrata��o
      open p_result for 
         select a.sq_data_especial as chave, a.cliente, a.sq_pais, a.co_uf, a.sq_cidade, a.tipo,
                a.data_especial, a.nome, a.abrangencia, a.expediente, a.ativo,
                case a.tipo 
                     when 'E' then to_date(a.data_especial,'dd/mm/yyyy')
                     when 'I' then to_date(a.data_especial||'/'||coalesce(p_ano,to_char(now(),'yyyy')),'dd/mm/yyyy')
                     else          VerificaDataMovel(coalesce(p_ano,to_char(now(),'yyyy')), a.tipo)
                end as data_formatada,
                case a.expediente
                     when 'S' then ' (Exp. normal)'
                     when 'M' then ' (Exp. apenas manh�)'
                     when 'T' then ' (Exp. apenas tarde)'
                     when 'N' then ' (Sem expediente)'
                end as nm_expediente
           from eo_data_especial  a
          where a.cliente = p_cliente
            and ((p_chave is null) or (p_chave is not null and a.sq_data_especial = p_chave))
            and ((p_ativo is null) or (p_ativo is not null and a.ativo            = p_ativo))
            and ((p_tipo  is null) or (p_tipo  is not null and a.tipo             = p_tipo))
            and ((p_ano   is null) or (p_ano   is not null and (a.tipo <> 'E' or (a.tipo = 'E' and substr(a.data_especial, 7, 4) = p_ano))));
   ElsIf p_restricao = 'VERIFICATIPO' Then
      -- Verifica se o tipo j� foi cadastrado
      open p_result for 
         select a.tipo
           from eo_data_especial  a
          where a.cliente = p_cliente
            and a.tipo not in ('I','E');
   End If;
   return p_result;
end; $BODY$ language 'plpgsql' volatile;
