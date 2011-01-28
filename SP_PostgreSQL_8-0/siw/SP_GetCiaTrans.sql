create or replace FUNCTION SP_GetCiaTrans
   (p_cliente          numeric,
    p_chave            numeric,
    p_nome             varchar,
    p_sigla            varchar,    
    p_aereo            varchar,
    p_rodoviario       varchar,
    p_aquaviario       varchar,
    p_padrao           varchar,
    p_ativo            varchar,
    p_chave_aux        numeric,
    p_restricao        varchar,
    p_result          REFCURSOR
   ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera as companhias de viagem
   open p_result for
      select a.sq_cia_transporte as chave, a.cliente, a.nome, a.sigla,
             case a.aereo      when 'S' then 'Sim' else 'Não' end as nm_aereo, a.aereo,
             case a.rodoviario when 'S' then 'Sim' else 'Não' end as nm_rodoviario, a.rodoviario,
             case a.aquaviario when 'S' then 'Sim' else 'Não' end as nm_aquaviario, a.aquaviario,
             case a.ativo      when 'S' then 'Sim' else 'Não' end as nm_ativo, a.ativo,
             case a.padrao     when 'S' then 'Sim' else 'Não' end as nm_padrao, a.padrao
        from pd_cia_transporte a
       where a.cliente = p_cliente
         and (p_chave      is null or (p_chave      is not null and a.sq_cia_transporte = p_chave))
         and (p_nome       is null or (p_nome       is not null and acentos(a.nome)     like acentos(p_nome)))
         and (p_sigla      is null or (p_sigla      is not null and acentos(a.sigla)    like acentos(p_sigla)))
         and (p_aereo      is null or (p_aereo      is not null and a.aereo             = p_aereo))
         and (p_rodoviario is null or (p_rodoviario is not null and a.rodoviario        = p_rodoviario))
         and (p_aquaviario is null or (p_aquaviario is not null and a.aquaviario        = p_aquaviario))
         and (p_padrao     is null or (p_padrao     is not null and a.padrao            = p_padrao))
         and (p_ativo      is null or (p_ativo      is not null and a.ativo             = p_ativo))
         and (p_chave_aux  is null or (p_chave_aux  is not null and a.sq_cia_transporte <> p_chave_aux))
         and (p_restricao  is null or (p_restricao  is not null and ((a.aereo      = 'S' and 'S' = (select aereo       from pd_meio_transporte where sq_meio_transporte = to_number(p_restricao))) or
                                                                     (a.rodoviario = 'S' and 'S' = (select rodoviario  from pd_meio_transporte where sq_meio_transporte = to_number(p_restricao))) or
                                                                     (a.rodoviario = 'S' and 'S' = (select ferroviario from pd_meio_transporte where sq_meio_transporte = to_number(p_restricao))) or
                                                                     (a.aquaviario = 'S' and 'S' = (select aquaviario  from pd_meio_transporte where sq_meio_transporte = to_number(p_restricao)))
                                                                    )
                                      )
             );
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;