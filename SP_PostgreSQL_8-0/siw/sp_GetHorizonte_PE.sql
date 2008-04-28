CREATE OR REPLACE FUNCTION siw.sp_GetHorizonte_PE
   (p_chave   numeric,
    p_cliente numeric,
    p_nome    varchar,
    p_ativo   varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   -- Recupera os tipos de arquivos
   open p_result for 
      select a.sq_pehorizonte as chave, a.cliente, a.nome, a.ativo, 
             case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
        from siw.pe_horizonte a
       where ((p_chave   is null) or (p_chave   is not null and a.sq_pehorizonte = p_chave))
         and ((p_cliente is null) or (p_cliente is not null and a.cliente      = p_cliente))
         and ((p_nome    is null) or (p_nome    is not null and upper(a.nome) like '%'||upper(p_nome)||'%'))
         and ((p_ativo   is null) or (p_ativo   is not null and a.ativo        = p_ativo));
         return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.sp_GetHorizonte_PE
   (p_chave   numeric,
    p_cliente numeric,
    p_nome    varchar,
    p_ativo   varchar) OWNER TO siw;
