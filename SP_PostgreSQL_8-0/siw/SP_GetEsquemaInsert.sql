CREATE OR REPLACE FUNCTION siw.SP_GetEsquemaInsert
   (p_restricao         varchar,
    p_sq_esquema_insert numeric,
    p_sq_esquema_tabela numeric,
    p_sq_coluna         numeric,
    p_registro          numeric)

  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera a lista ou o registro da tabela dc_esquema_insert
   open p_result for 
      select a.sq_esquema_insert, a.registro, a.sq_coluna, a.ordem, a.ordem, a.valor,
             b.sq_esquema_tabela, b.sq_esquema, b.sq_tabela,
             f.nome as nm_tabela, c.qtd_coluna, d.campo_externo, d.ordem as or_coluna,
             e.nome as cl_nome, e.obrigatorio as cl_obrigatorio, e.tamanho as cl_tamanho,
             case e.sq_dado_tipo when 1 then 'B_VARCHAR' 
                                 when 2 then 'B_INTEGER' 
                                 when 3 then 'B_DATE'
                                 when 4 then 'B_VARCHAR' 
                                 when 6 then 'B_VARCHAR' end as nm_tipo             
        from siw.dc_esquema_insert                     a 
             inner        join siw.dc_esquema_tabela   b on (a.sq_esquema_tabela = b.sq_esquema_tabela) 
             inner        join siw.dc_tabela           f on (b.sq_tabela         = f.sq_tabela) 
             left   outer join (select x.sq_esquema_tabela, count(*) as qtd_coluna
                                  from siw.dc_esquema_atributo x
                              group by sq_esquema_tabela
                                )                  c on (a.sq_esquema_tabela = c.sq_esquema_tabela)
             left   outer join siw.dc_esquema_atributo d on (a.sq_esquema_tabela = d.sq_esquema_tabela and
                                                         a.sq_coluna         = d.sq_coluna)
               left outer join siw.dc_coluna           e on (d.sq_coluna           = e.sq_coluna       and
                                                         a.sq_coluna           = e.sq_coluna)
       where a.sq_esquema_tabela = p_sq_esquema_tabela
         and ((p_sq_esquema_insert is null) or (p_sq_esquema_insert is not null and a.sq_esquema_insert = p_sq_esquema_insert))
         and ((p_sq_coluna is null)         or (p_sq_coluna         is not null and a.sq_coluna         = p_sq_coluna))
         and ((p_registro is null)          or (p_registro          is not null and a.registro          = p_registro));
         return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetEsquemaInsert
   (p_restricao         varchar,
    p_sq_esquema_insert numeric,
    p_sq_esquema_tabela numeric,
    p_sq_coluna         numeric,
    p_registro          numeric) OWNER TO siw;
