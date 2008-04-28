CREATE OR REPLACE FUNCTION siw.SP_GetCiaTrans
   (p_cliente         numeric,
    p_chave           numeric,
    p_nome            Varchar,
    p_aereo           Varchar,
    p_rodoviario      Varchar,
    p_aquaviario      Varchar,
    p_padrao          Varchar,
    p_ativo           Varchar,
    p_chave_aux       numeric,
    p_restricao       Varchar)
  RETURNS refcursor AS
$BODY$

DECLARE
 
    
    p_result          refcursor;
begin
   -- Recupera as companhias de viagem
   open p_result for
      select a.sq_cia_transporte as chave, a.cliente, a.nome,
             case a.aereo when 'S' then 'Sim' else 'Não' end as nm_aereo, a.aereo,
             case a.rodoviario when 'S' then 'Sim' else 'Não' end  as nm_rodoviario, a.rodoviario,
             case a.aquaviario when 'S' then 'Sim' else 'Não' end as nm_aquaviario, a.aquaviario,
             case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo, a.ativo,
             case a.padrao when 'S' then 'Sim' else 'Não' end as nm_padrao, a.padrao
        from siw.pd_cia_transporte a
       where a.cliente = p_cliente
         and (p_chave      is null or (p_chave      is not null and a.sq_cia_transporte = p_chave))
         and (p_nome       is null or (p_nome       is not null and upper(acentos(a.nome)) like upper(acentos(p_nome))))
         and (p_aereo      is null or (p_aereo      is not null and a.aereo = p_aereo))
         and (p_rodoviario is null or (p_rodoviario is not null and a.rodoviario = p_rodoviario))
         and (p_aquaviario is null or (p_aquaviario is not null and a.aquaviario = p_aquaviario))
         and (p_padrao     is null or (p_padrao     is not null and a.padrao = p_padrao))
         and (p_ativo      is null or (p_ativo      is not null and a.ativo  = p_ativo))
         and (p_chave_aux  is null or (p_chave_aux  is not null and a.sq_cia_transporte <> p_chave_aux));
end 
 $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCiaTrans
   (p_cliente         numeric,
    p_chave           numeric,
    p_nome            Varchar,
    p_aereo           Varchar,
    p_rodoviario      Varchar,
    p_aquaviario      Varchar,
    p_padrao          Varchar,
    p_ativo           Varchar,
    p_chave_aux       numeric,
    p_restricao       Varchar) OWNER TO siw;
