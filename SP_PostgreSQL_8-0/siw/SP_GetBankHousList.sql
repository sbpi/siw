create or replace function siw.SP_GetBankHousList
   (p_sq_banco   numeric,
    p_nome       varchar,
    p_codigo     varchar)
  RETURNS character varying AS
$BODY$declare

    p_result    refcursor;
 
begin
   -- Recupera os dados da agência bancária
   open p_result for
      select a.sq_agencia, b.codigo as sq_banco, a.nome, a.codigo,
             case a.padrao when 'S' then 'Sim' else 'Não' end as padrao,
             case a.ativo  when 'S' then 'Sim' else 'Não' end as ativo
        from siw.co_agencia a, siw.co_banco b
       where a.sq_banco   = b.sq_banco
         and b.sq_banco   = p_sq_banco
         and (p_nome   is null or (p_nome   is not null and acentos(a.nome) like '%'||acentos(p_nome)||'%'))
         and (p_codigo is null or (p_codigo is not null and a.codigo = p_codigo));
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetBankHousList
   (p_sq_banco   numeric,
    p_nome       varchar,
    p_codigo     varchar) OWNER TO siw;
