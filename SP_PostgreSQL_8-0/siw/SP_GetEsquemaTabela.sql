CREATE OR REPLACE FUNCTION siw.SP_GetEsquemaTabela
   (p_restricao         varchar,
    p_sq_esquema        numeric,
    p_sq_esquema_tabela numeric)

  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os tipos de apoio do status de um projeto
   open p_result for 
      select a.sq_esquema_tabela, a.sq_esquema, a.sq_tabela, a.ordem, a.elemento,
             b.nome as nm_tabela, c.qtd_coluna, d.campo_externo, d.ordem as or_coluna,
             d.mascara_data, d.valor_default,
             e.nome as cl_nome, e.obrigatorio as cl_obrigatorio, e.tamanho as cl_tamanho, a.remove_registro,
             case e.sq_dado_tipo when 1 then 'B_VARCHAR' 
                                 when 2 then case when coalesce(e.precisao,0)>0 then 'B_NUMERIC' else 'B_INTEGER' end
                                 when 3 then 'B_DATE'
                                 when 4 then 'B_VARCHAR' 
                                 when 6 then 'B_VARCHAR' end as nm_tipo,
             e.precisao, e.escala
        from siw.dc_esquema_tabela                     a 
             inner        join siw.dc_tabela           b on (a.sq_tabela = b.sq_tabela) 
             left   outer join (select x.sq_esquema_tabela, count(*) as qtd_coluna
                                  from siw.dc_esquema_atributo x
                              group by sq_esquema_tabela
                                )                  c on (a.sq_esquema_tabela = c.sq_esquema_tabela)
             left   outer join siw.dc_esquema_atributo d on (a.sq_esquema_tabela = d.sq_esquema_tabela)
               left outer join siw.dc_coluna           e on (d.sq_coluna           = e.sq_coluna)
       where a.sq_esquema = p_sq_esquema
         and ((p_sq_esquema_tabela is null) or (p_sq_esquema_tabela is not null and a.sq_esquema_tabela = p_sq_esquema_tabela));
         return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetEsquemaTabela
   (p_restricao         varchar,
    p_sq_esquema        numeric,
    p_sq_esquema_tabela numeric)
 OWNER TO siw;
